<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Withdrawal;
use App\Models\Transaction;

class EvaluatorFinanceController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $totalEarned = $user->transactions()->where('type', 'earning')->sum('amount');
        $transactions = $user->transactions()->orderByDesc('created_at')->limit(50)->get();
        $withdrawals = $user->withdrawals()->orderByDesc('created_at')->limit(20)->get();

        return view('evaluator.finance.index', compact('user', 'totalEarned', 'transactions', 'withdrawals'));
    }

    public function withdraw(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'amount' => 'required|numeric|min:100|max:' . $user->balance,
            'method' => 'required|in:bkash,nagad,dbbl',
            'account_number' => 'required|string|max:50',
        ], [
            'amount.max' => 'You cannot withdraw more than your available balance (৳' . number_format($user->balance, 2) . ').'
        ]);

        // Deduct balance and create withdrawal request
        $user->decrement('balance', $request->amount);

        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'method' => $request->method,
            'account_number' => $request->account_number,
            'status' => 'pending',
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'status' => 'pending', // Will be completed when admin approves
            'description' => 'Withdrawal Request via ' . ucfirst($request->method),
        ]);

        return redirect()->route('evaluator.finance.index')->with('success', 'Withdrawal request submitted successfully.');
    }
}
