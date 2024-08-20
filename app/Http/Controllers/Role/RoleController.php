<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role\Role;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function assignRole(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'role' => 'required|string',
        ]);

        $roleName = $validatedData['role'];
        $email = $validatedData['email'];

        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            return redirect()->route('show.users');
        }

        $users = User::where('email', $email)->get();

        if ($users->isEmpty()) {
            return redirect()->route('show.users');
        }

        foreach ($users as $user) {
            $user->roles()->syncWithoutDetaching([$role->id => ['created_at' => now(), 'updated_at' => now()]]);
        }

        return redirect()->route('show.users');
    }

    public function removeRole(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'role' => 'required|string',
        ]);

        $roleName = $validatedData['role'];
        $email = $validatedData['email'];

        $role = Role::where('name', $roleName)->first();

        if (!$role) {
            return redirect()->route('show.users');
        }

        $users = User::where('email', $email)->get();

        if ($users->isEmpty()) {
            return redirect()->route('show.users');
        }

        foreach ($users as $user) {
            $user->roles()->detach($role->id);
        }

        return redirect()->route('show.users');
    }
}
