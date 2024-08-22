<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function show()
    {
        $customers = Customer::with('user')->get();

        return Inertia::render('Customer/Customers', [
            'customers' => $customers
        ]);
    }

    public function store(Request $request)
    {
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
            'password' => Hash::make('@amitech'),
        ]);

        Customer::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('show.users');
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore(User::whereHas('customer', function ($query) use ($request) {
                    $query->where('id', $request->id);
                })->first()->id),
            ],
            'phone' => [
                'required',
                'string',
                'max:255',
                Rule::unique('customers')->ignore($request->id),
            ],
            'address' => 'required|string|max:255',
        ]);

        $customer = Customer::findOrFail($request->id);
        $user = User::findOrFail($customer->user_id);

        // Update user information
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update customer information
        $customer->update([
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('show.users');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:customers,id',
        ]);

        $customer = Customer::findOrFail($request->id);
        $user = User::findOrFail($customer->user_id);

        $customer->delete();
        $user->delete();

        return redirect()->route('show.users');
    }
}
