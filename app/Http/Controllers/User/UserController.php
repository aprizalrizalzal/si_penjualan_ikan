<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function show()
    {
        $users = User::with('roles')->get();

        return Inertia::render('Users/Users', [
            'users' => $users
        ]);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->id);
        $user->delete();

        return redirect()->route('show.users');
    }
}
