<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Withdrawal;
use App\Models\Transaction;

class AdminFinanceController extends Controller
{
    public function index()
    {
        $totalDeposits = Transaction::where('type', 'deposit')->where('status', 'completed')->sum('amount');
        $totalEarnings = Transaction::where('type', 'earning')->where('status', 'completed')->sum('amount');
        $netRevenue = $totalDeposits - $totalEarnings;

        $withdrawals = Withdrawal::with('user')->orderBy('created_at', 'desc')->paginate(50);

        return view('admin.finance.index', compact('totalDeposits', 'totalEarnings', 'netRevenue', 'withdrawals'));
    }

    public function approveWithdrawal(Withdrawal $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'Withdrawal is already processed.');
        }

        $withdrawal->update(['status' => 'approved']);
        
        // Find the pending transaction linked to this withdrawal
        $trx = Transaction::where('user_id', $withdrawal->user_id)
                          ->where('type', 'withdrawal')
                          ->where('amount', $withdrawal->amount)
                          ->where('status', 'pending')
                          ->orderBy('created_at', 'desc')
                          ->first();
        if ($trx) {
            $trx->update(['status' => 'completed']);
        }

        return back()->with('success', 'Withdrawal request approved successfully.');
    }
}
