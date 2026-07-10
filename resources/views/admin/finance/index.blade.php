<x-app-layout>
    <x-slot name="header">Financial Dashboard</x-slot>
    <x-slot name="subheader">System revenue and withdrawal management.</x-slot>

    <!-- Stats -->
    <div class="grid-3" style="margin-bottom: 24px;">
        <div class="card">
            <div class="card-body">
                <div class="stat-label">Total Deposits (Students)</div>
                <div class="stat-value" style="color:#3b82f6">৳{{ number_format($totalDeposits, 2) }}</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="stat-label">Total Evaluator Earnings</div>
                <div class="stat-value" style="color:#eab308">৳{{ number_format($totalEarnings, 2) }}</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body" style="background:#f0fdf4; border-color:#bbf7d0;">
                <div class="stat-label" style="color:#166534">Net System Revenue</div>
                <div class="stat-value" style="color:#15803d">৳{{ number_format($netRevenue, 2) }}</div>
            </div>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="card">
        <div class="card-header">
            <h3>Withdrawal Requests</h3>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Evaluator</th>
                        <th>Method</th>
                        <th>Account Number</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($withdrawals as $w)
                        <tr>
                            <td class="text-muted">{{ $w->created_at->format('M d, Y h:i A') }}</td>
                            <td class="font-bold">{{ $w->user->name }}<br><span class="text-sm text-muted font-normal">{{ $w->user->email }}</span></td>
                            <td style="text-transform:capitalize">{{ $w->method }}</td>
                            <td style="font-family:monospace">{{ $w->account_number }}</td>
                            <td class="font-bold">৳{{ number_format($w->amount, 2) }}</td>
                            <td>
                                @if($w->status === 'approved')
                                    <span class="badge badge-green">Approved</span>
                                @elseif($w->status === 'pending')
                                    <span class="badge badge-yellow">Pending</span>
                                @else
                                    <span class="badge badge-red">Rejected</span>
                                @endif
                            </td>
                            <td class="text-right">
                                @if($w->status === 'pending')
                                <form action="{{ route('admin.finance.withdrawals.approve', $w) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Confirm payment has been manually transferred to evaluator?')">
                                        Approve & Clear
                                    </button>
                                </form>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted" style="padding: 24px;">No withdrawal requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="padding:16px;">
            {{ $withdrawals->links() }}
        </div>
    </div>
</x-app-layout>
