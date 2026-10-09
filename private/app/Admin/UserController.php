<?php
declare(strict_types=1);

namespace App\Admin;

use App\Core\Audit;
use App\Core\Auth;
use App\Core\DB;

final class UserController extends BaseController
{
    public function index(): string
    {
        $this->guard('users');
        $rows = DB::all('SELECT * FROM users ORDER BY is_active DESC, name');
        return $this->render('users/index', ['rows' => $rows, 'title' => 'Users & roles']);
    }

    public function edit(?string $id = null): string
    {
        $this->guard('users');
        $u = $id ? DB::one('SELECT * FROM users WHERE id = ?', [(int) $id]) : ['role' => 'sales', 'is_active' => 1];
        if (!$u) {
            redirect(admin_url('users'));
        }
        return $this->render('users/form', ['u' => $u, 'id' => $id ? (int) $id : null, 'title' => $id ? 'Edit user' : 'New user']);
    }

    public function save(?string $id = null): void
    {
        $this->guard('users', 'edit');
        $id = $id ? (int) $id : null;
        $name = str_input('name', 100);
        $email = strtolower(str_input('email', 150));
        $role = str_input('role', 30);
        $pass = (string) ($_POST['password'] ?? '');
        $back = admin_url($id ? "users/$id" : 'users/new');
        if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || !isset(Auth::ROLES[$role])) {
            flash('error', 'Name, valid email and role are required.');
            redirect($back);
        }
        if (DB::val('SELECT COUNT(*) FROM users WHERE email = ? AND id <> ?', [$email, $id ?? 0])) {
            flash('error', 'Another user already uses that email.');
            redirect($back);
        }
        if ((!$id && strlen($pass) < 10) || ($pass !== '' && strlen($pass) < 10)) {
            flash('error', 'Password must be at least 10 characters.');
            redirect($back);
        }
        $active = input('is_active') ? 1 : 0;
        if ($id === Auth::id() && ($role !== 'super_admin' || !$active)) {
            flash('error', 'You cannot remove your own super admin access.');
            redirect($back);
        }
        $data = ['name' => $name, 'email' => $email, 'role' => $role, 'is_active' => $active, 'updated_at' => now()];
        if ($pass !== '') {
            $data['password_hash'] = Auth::hash($pass);
        }
        if ($id) {
            DB::update('users', $data, 'id = ?', [$id]);
            Audit::log('user_update', 'users', $id, ['role' => $role, 'active' => $active, 'password_changed' => $pass !== '']);
        } else {
            $id = DB::insert('users', $data + ['created_at' => now()]);
            Audit::log('user_create', 'users', $id, ['role' => $role]);
        }
        flash('success', 'User saved.');
        redirect(admin_url('users'));
    }

    public function delete(string $id): void
    {
        $this->guard('users', 'edit');
        if ((int) $id === Auth::id()) {
            flash('error', 'You cannot delete your own account.');
            redirect(admin_url('users'));
        }
        DB::update('enquiries', ['assigned_user_id' => null], 'assigned_user_id = ?', [(int) $id]);
        DB::delete('users', 'id = ?', [(int) $id]);
        Audit::log('user_delete', 'users', (int) $id);
        flash('success', 'User deleted.');
        redirect(admin_url('users'));
    }
}
