<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Models\Role\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        // Cek apakah user memiliki peran admin
        if (!Auth::user()->hasRole('admin')) {
            abort(Response::HTTP_NOT_FOUND);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'phone' => 'required|string|max:255|unique:' . Customer::class,
            'address' => 'required|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password'),
        ]);

        Customer::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Tambahkan role "user" ke user yang baru dibuat
        $role = Role::where('name', 'user')->first();
        if ($role) {
            $user->roles()->attach($role->id, ['created_at' => now(), 'updated_at' => now()]);
        }

        return redirect()->route('show.users');
    }
}
