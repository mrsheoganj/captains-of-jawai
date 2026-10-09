<?php
declare(strict_types=1);

namespace App\Admin;

use App\Controllers\ApiController;
use App\Core\Audit;
use App\Core\Auth;
use App\Core\DB;

final class EnquiryController extends BaseController
{
    /** CRM pipeline (Details/docs/admin/crm.md) */
    public const STATUSES = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'qualified' => 'Qualified',
        'proposal_sent' => 'Proposal sent',
        'follow_up' => 'Follow-up',
        'confirmed' => 'Confirmed (offline)',
        'active' => 'Expedition active',
        'completed' => 'Completed',
        'lost' => 'Lost',
        'spam' => 'Spam',
    ];

    private function filters(): array
    {
        $where = '1=1';
        $params = [];
        $f = [
            'q' => str_input('q', 100), 'status' => str_input('status', 30), 'type' => str_input('type', 20),
            'assigned' => str_input('assigned', 10), 'from' => str_input('from', 10), 'to' => str_input('to', 10),
        ];
        if ($f['q'] !== '') {
            $where .= ' AND (full_name LIKE ? OR email LIKE ? OR phone LIKE ? OR code LIKE ? OR message LIKE ?)';
            array_push($params, ...array_fill(0, 5, '%' . $f['q'] . '%'));
        }
        if ($f['status'] === '') {
            $where .= " AND status <> 'spam'";
        } elseif ($f['status'] === 'open') {
            $where .= " AND status NOT IN ('completed', 'lost', 'spam')";
        } elseif (isset(self::STATUSES[$f['status']])) {
            $where .= ' AND status = ?';
            $params[] = $f['status'];
        }
        if (in_array($f['type'], ['journey', 'contact'], true)) {
            $where .= ' AND type = ?';
            $params[] = $f['type'];
        }
        if ($f['assigned'] === 'me') {
            $where .= ' AND assigned_user_id = ?';
            $params[] = Auth::id();
        } elseif ($f['assigned'] === 'none') {
            $where .= ' AND assigned_user_id IS NULL';
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $f['from'])) {
            $where .= ' AND created_at >= ?';
            $params[] = $f['from'] . ' 00:00:00';
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $f['to'])) {
            $where .= ' AND created_at <= ?';
            $params[] = $f['to'] . ' 23:59:59';
        }
        return [$where, $params, $f];
    }

    public function index(): string
    {
        $this->guard('enquiries');
        [$where, $params, $f] = $this->filters();
        $page = max(1, (int) input('page', 1));
        $per = 25;
        $total = (int) DB::val("SELECT COUNT(*) FROM enquiries WHERE $where", $params);
        $rows = DB::all("SELECT e.*, u.name AS assignee FROM enquiries e LEFT JOIN users u ON u.id = e.assigned_user_id WHERE $where ORDER BY e.id DESC LIMIT $per OFFSET " . (($page - 1) * $per), $params);
        $counts = [];
        foreach (DB::all('SELECT status, COUNT(*) AS n FROM enquiries GROUP BY status') as $c) {
            $counts[$c['status']] = (int) $c['n'];
        }
        return $this->render('enquiries/index', ['rows' => $rows, 'f' => $f, 'total' => $total, 'page' => $page, 'per' => $per, 'counts' => $counts, 'title' => 'Enquiries']);
    }

    public function show(string $id): string
    {
        $this->guard('enquiries');
        $e = DB::one('SELECT * FROM enquiries WHERE id = ?', [(int) $id]);
        if (!$e) {
            flash('error', 'Enquiry not found.');
            redirect(admin_url('enquiries'));
        }
        $notes = DB::all('SELECT n.*, u.name AS user_name FROM enquiry_notes n LEFT JOIN users u ON u.id = n.user_id WHERE n.enquiry_id = ? ORDER BY n.id DESC', [$e['id']]);
        $history = DB::all("SELECT a.*, u.name AS user_name FROM audit_log a LEFT JOIN users u ON u.id = a.user_id WHERE a.entity = 'enquiries' AND a.entity_id = ? ORDER BY a.id DESC LIMIT 30", [$e['id']]);
        $users = DB::all("SELECT id, name FROM users WHERE is_active = 1 AND role IN ('super_admin', 'manager', 'sales') ORDER BY name");
        $others = DB::all('SELECT id, code, created_at, status FROM enquiries WHERE email = ? AND id <> ? ORDER BY id DESC LIMIT 5', [$e['email'], $e['id']]);
        $prevId = DB::val('SELECT MAX(id) FROM enquiries WHERE id < ?', [$e['id']]);
        $nextId = DB::val('SELECT MIN(id) FROM enquiries WHERE id > ?', [$e['id']]);
        return $this->render('enquiries/show', compact('e', 'notes', 'history', 'users', 'others', 'prevId', 'nextId') + ['title' => $e['code'] . ' — ' . $e['full_name'], 'canEdit' => Auth::can('enquiries', 'edit')]);
    }

    public function update(string $id): void
    {
        $this->guard('enquiries', 'edit');
        $e = DB::one('SELECT * FROM enquiries WHERE id = ?', [(int) $id]);
        if (!$e) {
            redirect(admin_url('enquiries'));
        }
        if (input('action') === 'resend') {
            ApiController::notify((int) $e['id'], $e['type'] ?: 'journey');
            Audit::log('resend_notification', 'enquiries', (int) $e['id']);
            flash('success', 'Notification emails sent again (see Email log for delivery status).');
            redirect(admin_url('enquiries/' . $e['id']));
        }
        $status = str_input('status', 30);
        $data = [
            'status' => isset(self::STATUSES[$status]) ? $status : $e['status'],
            'assigned_user_id' => (int) input('assigned_user_id', 0) ?: null,
            'follow_up_date' => preg_match('/^\d{4}-\d{2}-\d{2}$/', str_input('follow_up_date', 10)) ? str_input('follow_up_date', 10) : null,
            'updated_at' => now(),
        ];
        if ($e['status'] === 'new' && !in_array($data['status'], ['new', 'spam'], true) && !$e['first_contacted_at']) {
            $data['first_contacted_at'] = now();
        }
        DB::update('enquiries', $data, 'id = ?', [$e['id']]);
        $changes = [];
        $userName = fn ($uid) => $uid ? (string) DB::val('SELECT name FROM users WHERE id = ?', [$uid]) : null;
        foreach (['status' => 'status', 'assigned_user_id' => 'owner', 'follow_up_date' => 'follow-up'] as $k => $label) {
            if ((string) $e[$k] !== (string) $data[$k]) {
                $changes[$label] = $k === 'assigned_user_id' ? [$userName($e[$k]), $userName($data[$k])] : [$e[$k], $data[$k]];
            }
        }
        if ($changes) {
            Audit::log('lead_update', 'enquiries', (int) $e['id'], $changes);
        }
        if (($note = str_input('note', 5000)) !== '') {
            DB::insert('enquiry_notes', ['enquiry_id' => $e['id'], 'user_id' => Auth::id(), 'note' => $note, 'created_at' => now()]);
        }
        flash('success', 'Enquiry updated.');
        redirect(admin_url('enquiries/' . $e['id']));
    }

    public function note(string $id): void
    {
        $this->guard('enquiries', 'edit');
        $note = str_input('note', 5000);
        if ($note !== '' && DB::val('SELECT COUNT(*) FROM enquiries WHERE id = ?', [(int) $id])) {
            DB::insert('enquiry_notes', ['enquiry_id' => (int) $id, 'user_id' => Auth::id(), 'note' => $note, 'created_at' => now()]);
            DB::update('enquiries', ['updated_at' => now()], 'id = ?', [(int) $id]);
            flash('success', 'Note added.');
        }
        redirect(admin_url('enquiries/' . (int) $id) . '#notes');
    }

    public function delete(string $id): void
    {
        $this->guard('enquiries', 'edit');
        if (!in_array(Auth::role(), ['super_admin', 'manager'], true)) {
            flash('error', 'Only managers can delete enquiries.');
            redirect(admin_url('enquiries/' . (int) $id));
        }
        DB::delete('enquiry_notes', 'enquiry_id = ?', [(int) $id]);
        DB::delete('enquiries', 'id = ?', [(int) $id]);
        Audit::log('delete', 'enquiries', (int) $id);
        flash('success', 'Enquiry deleted.');
        redirect(admin_url('enquiries'));
    }

    public function bulk(): void
    {
        $this->guard('enquiries', 'edit');
        $ids = array_filter(array_map('intval', (array) input('ids', [])));
        $action = str_input('bulk_action', 30);
        if (!$ids) {
            flash('error', 'Select at least one enquiry.');
            back(admin_url('enquiries'));
        }
        $in = implode(',', array_fill(0, count($ids), '?'));
        if ($action === 'delete') {
            if (!in_array(Auth::role(), ['super_admin', 'manager'], true)) {
                flash('error', 'Only managers can delete enquiries.');
                back(admin_url('enquiries'));
            }
            DB::q("DELETE FROM enquiry_notes WHERE enquiry_id IN ($in)", $ids);
            DB::q("DELETE FROM enquiries WHERE id IN ($in)", $ids);
            Audit::log('bulk_delete', 'enquiries', null, ['ids' => $ids]);
            flash('success', count($ids) . ' enquiries deleted.');
        } elseif (isset(self::STATUSES[$action])) {
            DB::q("UPDATE enquiries SET status = ?, updated_at = ? WHERE id IN ($in)", [$action, now(), ...$ids]);
            Audit::log('bulk_status', 'enquiries', null, ['ids' => $ids, 'status' => $action]);
            flash('success', count($ids) . ' enquiries marked “' . self::STATUSES[$action] . '”.');
        } elseif ($action === 'assign_me') {
            DB::q("UPDATE enquiries SET assigned_user_id = ?, updated_at = ? WHERE id IN ($in)", [Auth::id(), now(), ...$ids]);
            flash('success', count($ids) . ' enquiries assigned to you.');
        }
        back(admin_url('enquiries'));
    }

    public function export(): void
    {
        $this->guard('enquiries');
        [$where, $params] = $this->filters();
        $rows = DB::all("SELECT e.*, u.name AS assignee FROM enquiries e LEFT JOIN users u ON u.id = e.assigned_user_id WHERE $where ORDER BY e.id DESC", $params);
        Audit::log('export', 'enquiries', null, ['count' => count($rows)]);
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="enquiries-' . date('Y-m-d') . '.csv"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF");
        $cols = ['code', 'created_at', 'type', 'status', 'full_name', 'email', 'phone', 'country', 'interests', 'travel_window', 'nights', 'adults', 'children', 'private_vehicle', 'accommodation', 'transfer', 'message', 'assignee', 'follow_up_date', 'source_page', 'utm_source', 'utm_medium', 'utm_campaign'];
        fputcsv($out, $cols);
        foreach ($rows as $r) {
            // Prevent spreadsheet formula injection
            fputcsv($out, array_map(fn ($c) => preg_match('/^(?:[=@\t]|[+\-](?![\d\s]))/', (string) ($r[$c] ?? '')) ? "'" . $r[$c] : ($r[$c] ?? ''), $cols));
        }
        fclose($out);
        exit;
    }
}
