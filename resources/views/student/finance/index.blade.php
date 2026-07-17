<x-app-layout>
    <x-slot name="header">My Wallet</x-slot>
    <x-slot name="subheader">Manage your credits and view transaction history.</x-slot>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">❌ {{ $errors->first() }}</div>
    @endif

    <div class="grid-2">
        <div class="card">
            <div class="card-body" style="text-align:center; padding: 40px;">
                <div class="text-sm text-muted font-bold" style="text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Current Balance</div>
                <div style="font-size: 3.5rem; font-weight: 900; color: var(--brand); line-height: 1;">
                    ৳{{ number_format(auth()->user()->balance, 2) }}
                </div>
                <div class="text-sm text-muted mt-2">1 TK = 1 Credit</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3>💳 Add Funds</h3></div>
            <div class="card-body">
                <form action="{{ route('student.finance.deposit') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Amount (TK)</label>
                        <input type="number" name="amount" class="form-control" placeholder="e.g. 500" min="10" required>
                        @error('amount')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-full" style="padding:12px; font-size:1rem;">
                        Pay with SSLCommerz
                    </button>
                    <div class="text-sm text-muted mt-4 text-center">
                        Secure payment gateway. You will be redirected to complete your transaction.
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><h3>🧾 Transaction History</h3></div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Trx ID</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td class="text-muted">{{ $trx->created_at->format('M d, Y h:i A') }}</td>
                        <td style="font-family:monospace; color:var(--text-secondary)">{{ $trx->trx_id ?? '—' }}</td>
                        <td>{{ $trx->description }}</td>
                        <td style="font-weight:700; color: {{ $trx->type === 'deposit' || $trx->type === 'earning' ? '#16a34a' : '#dc2626' }}">
                            {{ $trx->type === 'deposit' || $trx->type === 'earning' ? '+' : '-' }}৳{{ number_format($trx->amount, 2) }}
                        </td>
                        <td>
                            @if($trx->status === 'completed')
                                <span class="badge badge-green">Completed</span>
                            @elseif($trx->status === 'pending')
                                <span class="badge badge-yellow">Pending</span>
                            @else
                                <span class="badge badge-red">Failed</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted" style="padding: 20px;">No transactions yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
