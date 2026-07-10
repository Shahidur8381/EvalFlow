<x-app-layout>
    <x-slot name="header">User Management</x-slot>
    <x-slot name="subheader">Manage student and evaluator accounts.</x-slot>

    @php
        $evaluators = $allUsers->where('role', 'evaluator');
        $students   = $allUsers->where('role', 'student');
    @endphp

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div class="stat-value">{{ $allUsers->count() }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-value">{{ $evaluators->count() }}</div>
            <div class="stat-label">Evaluators</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📚</div>
            <div class="stat-value">{{ $students->count() }}</div>
            <div class="stat-label">Students</div>
        </div>
    </div>

    <div class="grid-2">
        <!-- Create Evaluator Form -->
        <div class="card">
            <div class="card-header"><h3>➕ Create Evaluator Account</h3></div>
            <div class="card-body">
                <form action="{{ route('admin.users.storeEvaluator') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Jane Doe" value="{{ old('name') }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="evaluator@example.com" value="{{ old('email') }}" required>
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Create Evaluator</button>
                </form>
            </div>
        </div>

        <!-- User List -->
        <div class="card">
            <div class="card-header" style="display:block">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <h3>📋 All Users ({{ $users->count() }})</h3>
                    @if(request()->hasAny(['role', 'search']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline btn-xs">Clear Filters</a>
                    @endif
                </div>
                <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex; gap:10px;">
                    <select name="role" class="form-control" style="width:140px; padding:6px 10px; font-size:.85rem;">
                        <option value="">All Roles</option>
                        <option value="evaluator" {{ request('role') === 'evaluator' ? 'selected' : '' }}>Evaluators</option>
                        <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Students</option>
                    </select>
                    <input type="text" name="search" class="form-control" placeholder="Search name or email..." value="{{ request('search') }}" style="flex:1; padding:6px 10px; font-size:.85rem;">
                    <button type="submit" class="btn btn-primary" style="padding:6px 12px; font-size:.85rem;">Filter</button>
                </form>
            </div>
            <div class="table-wrap" style="max-height: 500px; overflow-y: auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th style="text-align:right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="font-bold">{{ $user->name }}</div>
                                <div class="text-sm text-muted">{{ $user->email }}</div>
                            </td>
                            <td>
                                @if($user->role === 'evaluator')
                                    <span class="badge badge-purple">Evaluator</span>
                                @else
                                    <span class="badge badge-green">Student</span>
                                @endif
                            </td>
                            <td style="text-align:right">
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this {{ $user->role }}? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline btn-xs" style="color:#ef4444;border-color:rgba(239,68,68,.3)">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted" style="padding: 20px;">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
