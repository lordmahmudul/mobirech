<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    /**
     * Display a listing of the banks.
     */
    public function index()
    {
        $banks = Bank::latest()->get(); // Get all banks, newest first
        return view('banks.index', compact('banks'));
    }

    /**
     * Show the form for creating a new bank.
     */
    public function create()
    {
        return view('banks.create');
    }

    /**
     * Store a newly created bank in the database.
     */
    public function store(Request $request)
    {
        // 1. Validate the input
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:50',
        ]);

        // 2. Create the data
        Bank::create($request->only(['bank_name', 'account_number']));

        // 3. Redirect back with a message
        return redirect()->route('banks.index')
                         ->with('success', 'Bank created successfully.');
    }

    /**
     * Show the form for editing the specified bank.
     */
    public function edit(Bank $bank)
    {
        return view('banks.edit', compact('bank'));
    }

    /**
     * Update the specified bank in storage.
     */
    public function update(Request $request, Bank $bank)
    {
        // 1. Validate
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:50',
        ]);

        // 2. Update
        $bank->update($request->only(['bank_name', 'account_number']));

        // 3. Redirect
        return redirect()->route('banks.index')->with('success', 'Bank updated successfully.');
    }

    /**
     * Remove the specified bank from storage.
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();

        return redirect()->route('banks.index')->with('success', 'Bank deleted successfully.');
    }
}