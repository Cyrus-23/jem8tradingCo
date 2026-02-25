<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index()
    {
        return response()->json(Account::all());
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:accounts,email',
            'password' => 'required|string|min:6',
        ]);

        // Create account with hashed password
        $account = Account::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return response()->json([
            'message' => 'Account created successfully',
            'data' => $account
        ], 201);
    }

    public function show(string $id)
    {
        $account = Account::findOrFail($id);
        return response()->json($account);
    }

    public function update(Request $request, string $id)
    {
        $account = Account::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|unique:accounts,email,' . $id,
            'password' => 'sometimes|string|min:6',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $account->update($validated);

        return response()->json([
            'message' => 'Account updated successfully',
            'data' => $account
        ]);
    }

    

    public function destroy(string $id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return response()->json([
            'message' => 'Account deleted successfully'
        ]);
    }
}