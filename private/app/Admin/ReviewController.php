<?php
declare(strict_types=1);

namespace App\Admin;

use App\Core\Audit;
use App\Core\Auth;
use App\Core\DB;

/** Guest reviews (testimonials table): approve, hide or delete. Available in the admin and in the CRM. */
final class ReviewController extends BaseController
{
    public const TABS = ['pending' => 'Awaiting approval', 'published' => 'On the website', 'draft' => 'Hidden'];

    public function index(): string
    {
        $this->guard('reviews');
        $tab = isset(self::TABS[$_GET['tab'] ?? '']) ? $_GET['tab'] : 'pending';
        $counts = [];
        foreach (DB::all('SELECT status, COUNT(*) AS n FROM testimonials GROUP BY status') as $r) {
            $counts[$r['status']] = (int) $r['n'];
        }
        $rows = DB::all('SELECT * FROM testimonials WHERE status = ? ORDER BY ' . ($tab === 'published' ? 'sort_order, id' : 'id DESC'), [$tab]);
        return $this->render('reviews', ['rows' => $rows, 'tab' => $tab, 'counts' => $counts, 'title' => 'Reviews']);
    }

    public function act(string $id, string $action): never
    {
        $this->guard('reviews', 'edit');
        $id = (int) $id;
        $row = DB::one('SELECT id, author, status FROM testimonials WHERE id = ?', [$id]);
        if (!$row) {
            flash('error', 'That review no longer exists.');
            redirect(admin_url('reviews'));
        }
        if ($action === 'approve') {
            DB::update('testimonials', ['status' => 'published'], 'id = ?', [$id]);
            Audit::log('review_approve', 'testimonials', $id, ['author' => $row['author']]);
            flash('success', 'Review approved: it is now on the website.');
        } elseif ($action === 'hide') {
            DB::update('testimonials', ['status' => 'draft'], 'id = ?', [$id]);
            Audit::log('review_hide', 'testimonials', $id, ['author' => $row['author']]);
            flash('success', 'Review hidden from the website.');
        } elseif ($action === 'delete' && in_array(Auth::role(), ['super_admin', 'manager'], true)) {
            DB::delete('testimonials', 'id = ?', [$id]);
            Audit::log('review_delete', 'testimonials', $id, ['author' => $row['author']]);
            flash('success', 'Review deleted.');
        }
        redirect(admin_url('reviews?tab=' . $row['status']));
    }

    public static function pendingCount(): int
    {
        try {
            return (int) DB::val("SELECT COUNT(*) FROM testimonials WHERE status = 'pending'");
        } catch (\Throwable) {
            return 0;
        }
    }
}
