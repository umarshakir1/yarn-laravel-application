<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of accounts.
     */
    public function index()
    {
        $accounts = Account::latest()->get();
        return view('accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new account.
     */
    public function create()
    {
        return view('accounts.create');
    }

    /**
     * Store a newly created account in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:255',
            'type'            => 'required|in:bank,cash',
            'account_number'  => 'nullable|string|max:100',
            'bank_name'       => 'nullable|string|max:255',
            'opening_balance' => 'required|numeric|min:0',
            'notes'           => 'nullable|string',
        ]);

        $data['current_balance'] = $data['opening_balance'];

        Account::create($data);

        return redirect()->route('accounts.index')
                         ->with('success', 'Account created successfully.');
    }

    /**
     * Show the form for editing an account.
     */
    public function edit(Account $account)
    {
        return view('accounts.edit', compact('account'));
    }

    /**
     * Update the specified account in storage.
     */
    public function update(Request $request, Account $account)
    {
        $data = $request->validate([
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:bank,cash',
            'account_number' => 'nullable|string|max:100',
            'bank_name'      => 'nullable|string|max:255',
            'notes'          => 'nullable|string',
            'is_active'      => 'boolean',
        ]);

        $account->update($data);

        return redirect()->route('accounts.index')
                         ->with('success', 'Account updated successfully.');
    }

    /**
     * Remove the specified account from storage.
     */
    public function destroy(Account $account)
    {
        $account->delete();

        return redirect()->route('accounts.index')
                         ->with('success', 'Account deleted successfully.');
    }
}
