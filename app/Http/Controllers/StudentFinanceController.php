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
            'store_id'     => config('services.sslcommerz.store_id'),
            'store_passwd' => config('services.sslcommerz.store_password'),
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
            $response = Http::withoutVerifying()
                ->asForm()
                ->post('https://sandbox.sslcommerz.com/gwprocess/v4/api.php', $postData);
        } catch (\Exception $e) {
            $transaction->update(['status' => 'failed']);
            return redirect()->route('student.finance.index')
                ->withErrors(['error' => 'Could not connect to SSLCommerz: ' . $e->getMessage()]);
        }

        $body = $response->json();

        // Check that we got a non-empty GatewayPageURL
        if ($response->successful() && !empty($body['GatewayPageURL'])) {
            return redirect($body['GatewayPageURL']);
        }

        // API returned an error — mark transaction failed and show reason
        $transaction->update(['status' => 'failed']);
        $reason = $body['failedreason'] ?? ($body['status'] ?? 'Unknown error from SSLCommerz');
        return redirect()->route('student.finance.index')
            ->withErrors(['error' => 'SSLCommerz Error: ' . $reason]);
    }

    public function success(Request $request)
    {
        $trxId  = $request->input('tran_id');
        $valId  = $request->input('val_id');
        $status = $request->input('status');

        $transaction = Transaction::where('trx_id', $trxId)->first();

        if (!$transaction) {
            return redirect('/')->withErrors(['error' => 'Transaction not found.']);
        }

        if ($transaction->status !== 'pending') {
            return redirect()->route('student.finance.index')
                ->with('success', 'Payment was already processed.');
        }

        // Only proceed if SSLCommerz says it's VALID
        if ($status !== 'VALID' && $status !== 'VALIDATED') {
            $transaction->update(['status' => 'failed']);
            return redirect()->route('student.finance.index')
                ->withErrors(['error' => 'Payment was not validated by SSLCommerz (status: ' . $status . ')']);
        }

        // Server-side validation via SSLCommerz Validation API
        try {
            $validationResponse = Http::withoutVerifying()->get(
                'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php',
                [
                    'val_id'       => $valId,
                    'store_id'     => config('services.sslcommerz.store_id'),
                    'store_passwd' => config('services.sslcommerz.store_password'),
                    'v'            => 1,
                    'format'       => 'json',
                ]
            );

            $result = $validationResponse->json();

            if (isset($result['status']) && ($result['status'] === 'VALID' || $result['status'] === 'VALIDATED')) {
                $transaction->update(['status' => 'completed']);
                $transaction->user->increment('balance', $transaction->amount);
                return redirect()->route('student.finance.index')
                    ->with('success', 'Payment successful! ৳' . number_format($transaction->amount, 2) . ' added to your wallet.');
            }
        } catch (\Exception $e) {
            // Validation API call failed
        }

        $transaction->update(['status' => 'failed']);
        return redirect()->route('student.finance.index')
            ->withErrors(['error' => 'Payment could not be verified. Please contact support.']);
    }

    public function fail(Request $request)
    {
        $trxId = $request->input('tran_id');
        if ($trxId) {
            Transaction::where('trx_id', $trxId)->where('status', 'pending')->update(['status' => 'failed']);
        }
        return redirect()->route('student.finance.index')
            ->withErrors(['error' => 'Payment failed. No amount was charged.']);
    }

    public function cancel(Request $request)
    {
        $trxId = $request->input('tran_id');
        if ($trxId) {
            Transaction::where('trx_id', $trxId)->where('status', 'pending')->update(['status' => 'failed']);
        }
        return redirect()->route('student.finance.index')
            ->withErrors(['error' => 'Payment was cancelled.']);
    }
}
