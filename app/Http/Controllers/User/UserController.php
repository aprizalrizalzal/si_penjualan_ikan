<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Models\Customer\CustomerImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserController extends Controller
{
    public function show()
    {
        // Mengambil semua pengguna dengan peran terkait
        $users = User::with('customer', 'roles')->get();

        return Inertia::render('Users/Users', [
            'users' => $users
        ]);
    }

    public function destroy(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->id);

        // Menghapus pengguna
        $user->delete();

        return redirect()->route('show.users');
    }
}
