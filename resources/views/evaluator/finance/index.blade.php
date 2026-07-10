<x-app-layout>
    <x-slot name="header">Earnings & Withdrawals</x-slot>
    <x-slot name="subheader">Track your evaluation earnings and request withdrawals.</x-slot>

    <div class="grid-2">
        <div class="card">
            <div class="card-body" style="padding: 30px;">
                <div class="text-sm text-muted font-bold" style="text-transform:uppercase; letter-spacing:1px; margin-bottom:8px;">Available Balance</div>
                <div style="font-size: 3rem; font-weight: 900; color: #10b981; line-height: 1;">
                    ৳{{ number_format($user->balance, 2) }}
                </div>
                <div class="text-sm text-muted mt-2">Total Earned All Time: ৳{{ number_format($totalEarned, 2) }}</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><h3>💸 Request Withdrawal</h3></div>
            <div class="card-body">
                <form action="{{ route('evaluator.finance.withdraw') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Amount (TK)</label>
                        <input type="number" name="amount" class="form-control" placeholder="Min 100 TK" min="100" max="{{ $user->balance }}" required>
                        @error('amount')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Withdrawal Method</label>
                        <select name="method" class="form-control" required>
                            <option value="bkash">bKash</option>
                            <option value="nagad">Nagad</option>
                            <option value="dbbl">DBBL / Rocket</option>
                        </select>
                        @error('method')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Account Number</label>
                        <input type="text" name="account_number" class="form-control" placeholder="e.g. 01700000000" required>
                        @error('account_number')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-full" {{ $user->balance < 100 ? 'disabled' : '' }}>
                        Submit Request
                    </button>
                    @if($user->balance < 100)
                        <div class="text-sm text-muted mt-2 text-center" style="color:#ef4444">Minimum withdrawal amount is 100 TK.</div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    @if($withdrawals->isNotEmpty())
    <div class="card">
        <div class="card-header"><h3>⏳ Withdrawal History</h3></div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Method</th>
                        <th>Account</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawals as $w)
                    <tr>
                        <td class="text-muted">{{ $w->created_at->format('M d, Y') }}</td>
                        <td style="text-transform:capitalize; font-weight:700;">{{ $w->method }}</td>
                        <td style="font-family:monospace">{{ $w->account_number }}</td>
                        <td style="font-weight:700">৳{{ number_format($w->amount, 2) }}</td>
                        <td>
                            @if($w->status === 'approved')
                                <span class="badge badge-green">Approved</span>
                            @elseif($w->status === 'pending')
                                <span class="badge badge-yellow">Pending</span>
                            @else
                                <span class="badge badge-red">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <div class="card">
        <div class="card-header"><h3>🧾 Transaction Log</h3></div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Description</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td class="text-muted">{{ $trx->created_at->format('M d, Y h:i A') }}</td>
                        <td>{{ $trx->description }}</td>
                        <td style="font-weight:700; color: {{ $trx->type === 'earning' ? '#16a34a' : '#dc2626' }}">
                            {{ $trx->type === 'earning' ? '+' : '-' }}৳{{ number_format($trx->amount, 2) }}
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
                        <td colspan="4" class="text-center text-muted" style="padding: 20px;">No transactions yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
