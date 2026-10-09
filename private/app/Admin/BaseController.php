<?php
declare(strict_types=1);

namespace App\Admin;

use App\Core\Auth;
use App\Core\Csrf;
use App\Core\View;

abstract class BaseController
{
    protected function render(string $view, array $data = []): string
    {
        return View::render('admin/' . $view, $data, 'admin/layout');
    }

    /** Require login + permission; for POST also verify CSRF. */
    protected function guard(string $area, string $mode = 'view'): void
    {
        Auth::require($area, $mode);
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            Csrf::verify();
        }
    }
}
