<?php
declare(strict_types=1);

namespace App\Admin;

use App\Core\Audit;
use App\Core\Auth;
use App\Core\Csrf;
use App\Core\DB;
use App\Core\Session;
use App\Core\View;

final class AuthController extends BaseController
{
    public function loginForm(): string
    {
        if (Auth::check()) {
            redirect(admin_url());
        }
        return View::render('admin/login', ['messages' => Session::messages()]);
    }

    public function login(): void
    {
        Csrf::verify();
        $result = Auth::attempt(str_input('email', 200), (string) ($_POST['password'] ?? ''));
        if ($result === true) {
            $to = (string) Session::get('intended', admin_url());
            Session::forget('intended');
            redirect(str_starts_with($to, admin_path()) ? $to : admin_url());
        }
        Session::keepInput(['email' => str_input('email', 200)]);
        flash('error', is_string($result) ? $result : 'Incorrect email or password.');
        redirect(admin_url('login'));
    }

    public function logout(): void
    {
        Csrf::verify();
        Audit::log('logout', 'users', Auth::id());
        Auth::logout();
        flash('success', 'You have been signed out.');
        redirect(admin_url('login'));
    }

    public function profile(): string
    {
        $this->guard('dashboard');
        return $this->render('profile', ['title' => 'My profile', 'u' => DB::one('SELECT * FROM users WHERE id = ?', [Auth::id()])]);
    }

    public function saveProfile(): void
    {
        $this->guard('dashboard', 'edit');
        $u = DB::one('SELECT * FROM users WHERE id = ?', [Auth::id()]);
        $name = str_input('name', 100);
        $email = strtolower(str_input('email', 150));
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Name and a valid email are required.');
            redirect(admin_url('profile'));
        }
        if (DB::val('SELECT COUNT(*) FROM users WHERE email = ? AND id <> ?', [$email, $u['id']])) {
            flash('error', 'That email is used by another account.');
            redirect(admin_url('profile'));
        }
        $data = ['name' => $name, 'email' => $email, 'updated_at' => now()];
        $new = (string) ($_POST['new_password'] ?? '');
        if ($new !== '') {
            if (!password_verify((string) ($_POST['current_password'] ?? ''), $u['password_hash'])) {
                flash('error', 'Your current password is incorrect.');
                redirect(admin_url('profile'));
            }
            if (strlen($new) < 10) {
                flash('error', 'New password must be at least 10 characters.');
                redirect(admin_url('profile'));
            }
            $data['password_hash'] = Auth::hash($new);
            Audit::log('password_change', 'users', (int) $u['id']);
        }
        DB::update('users', $data, 'id = ?', [$u['id']]);
        flash('success', 'Profile updated.');
        redirect(admin_url('profile'));
    }
}
