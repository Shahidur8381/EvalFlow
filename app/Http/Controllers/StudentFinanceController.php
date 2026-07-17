<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Transaction;
use Illuminate\Support\Str;

class StudentFinanceController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()->transactions()->orderBy('created_at', 'desc')->get();
        return view('student.finance.index', compact('transactions'));
    }

    public function initiatePayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
        ]);

        $user = auth()->user();
        $trxId = 'SSL' . uniqid();

        // Log the pending transaction
        $transaction = Transaction::create([
            'user_id'     => $user->id,
            'type'        => 'deposit',
            'amount'      => $request->amount,
            'trx_id'      => $trxId,
            'status'      => 'pending',
            'description' => 'Wallet Top-up via SSLCommerz',
        ]);

        $postData = [
            'store_id'     => env('SSLCOMMERZ_STORE_ID', 'testbox'),
            'store_passwd' => env('SSLCOMMERZ_STORE_PASSWORD', 'testpassword'),
            'total_amount' => $request->amount,
            'currency'     => 'BDT',
            'tran_id'      => $trxId,
            'success_url'  => route('student.finance.success'),
            'fail_url'     => route('student.finance.fail'),
            'cancel_url'   => route('student.finance.cancel'),
            'emi_option'   => 0,
            'cus_name'     => $user->name,
            'cus_email'    => $user->email,
            'cus_phone'    => '01700000000',
            'cus_add1'     => 'Dhaka',
            'cus_city'     => 'Dhaka',
            'cus_country'  => 'Bangladesh',
            'shipping_method' => 'NO',
            'product_name' => 'Wallet Credit',
            'product_category' => 'Service',
            'product_profile' => 'non-physical-goods',
        ];

        try {
            $response = Http::withoutVerifying()->asForm()->post('https://sandbox.sslcommerz.com/gwprocess/v4/api.php', $postData);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Could not connect to SSLCommerz Sandbox: ' . $e->getMessage()]);
        }

        if ($response && $response->successful() && isset($response['GatewayPageURL'])) {
            return redirect($response['GatewayPageURL']);
        }

        return back()->withErrors(['error' => 'SSLCommerz Gateway Error: ' . ($response['failedreason'] ?? 'Invalid credentials or configuration.')]);
    }

    public function success(Request $request)
    {
        $trxId = $request->input('tran_id');
        $valId = $request->input('val_id');
        $status = $request->input('status');

        $transaction = Transaction::where('trx_id', $trxId)->firstOrFail();

        if ($transaction->status === 'pending') {
            if ($status === 'VALID' || $status === 'VALIDATED') {
                // Call Validation API to ensure the callback wasn't spoofed
                try {
                    $validationUrl = 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php';
                    $response = Http::withoutVerifying()->get($validationUrl, [
                        'val_id' => $valId,
                        'store_id' => env('SSLCOMMERZ_STORE_ID', 'testbox'),
                        'store_passwd' => env('SSLCOMMERZ_STORE_PASSWORD', 'testpassword'),
                        'v' => 1,
                        'format' => 'json'
                    ]);

                    if ($response && $response->successful()) {
                        $result = $response->json();
                        if (isset($result['status']) && ($result['status'] === 'VALID' || $result['status'] === 'VALIDATED')) {
                            $transaction->update(['status' => 'completed']);
                            $transaction->user->increment('balance', $transaction->amount);
                            return redirect()->route('student.finance.index')->with('success', 'Payment successful! Balance added.');
                        }
                    }
                } catch (\Exception $e) {
                    // Fallthrough to failure
                }
            }

            // If validation failed or status was not valid
            $transaction->update(['status' => 'failed']);
            return redirect()->route('student.finance.index')->withErrors(['error' => 'Payment validation failed.']);
        }

        return redirect()->route('student.finance.index')->with('success', 'Payment was already processed.');
    }

    public function fail(Request $request)
    {
        $trxId = $request->input('tran_id');
        Transaction::where('trx_id', $trxId)->update(['status' => 'failed']);
        return redirect()->route('student.finance.index')->withErrors(['error' => 'Payment failed.']);
    }

    public function cancel(Request $request)
    {
        $trxId = $request->input('tran_id');
        Transaction::where('trx_id', $trxId)->update(['status' => 'failed']);
        return redirect()->route('student.finance.index')->withErrors(['error' => 'Payment cancelled.']);
    }
}
