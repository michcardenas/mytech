<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /** Roles que se pueden asignar desde este CRUD. */
    private const ROLES = ['admin', 'comercial'];

    /** Listado del equipo (admins y comerciales). */
    public function index()
    {
        $users = User::role(self::ROLES)->with('roles')->orderBy('name')->get();

        return view('admin.users.index', [
            'users'     => $users,
            'roles'     => self::ROLES,
            'pageTitle' => 'Usuarios',
        ]);
    }

    public function create()
    {
        return view('admin.users.form', [
            'user'      => new User(),
            'roles'     => self::ROLES,
            'pageTitle' => 'Nuevo usuario',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => ['required', Rule::in(self::ROLES)],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')->with('success', "Usuario «{$user->name}» creado.");
    }

    public function edit(User $user)
    {
        return view('admin.users.form', [
            'user'      => $user,
            'roles'     => self::ROLES,
            'pageTitle' => 'Editar usuario',
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => ['required', Rule::in(self::ROLES)],
        ]);

        // No permitir quitarle el rol admin al último admin
        if ($user->hasRole('admin') && $data['role'] !== 'admin' && $this->adminCount() <= 1) {
            return back()->withInput()->with('error', 'No puedes cambiar el rol del único administrador.');
        }

        $user->update([
            'name'  => $data['name'],
            'email' => $data['email'],
        ]);
        if (! empty($data['password'])) {
            $user->update(['password' => Hash::make($data['password'])]);
        }
        $user->syncRoles([$data['role']]);

        return redirect()->route('admin.users.index')->with('success', "Usuario «{$user->name}» actualizado.");
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }
        if ($user->hasRole('admin') && $this->adminCount() <= 1) {
            return back()->with('error', 'No puedes eliminar al único administrador.');
        }

        $nombre = $user->name;
        $user->delete();

        return back()->with('success', "Usuario «{$nombre}» eliminado.");
    }

    private function adminCount(): int
    {
        return User::role('admin')->count();
    }
}
