<x-app-layout>
    <x-slot name="header">My Exams</x-slot>
    <x-slot name="subheader">View your exam schedule, question papers, and submission status.</x-slot>

    @php
        $activeExams = $exams->filter(fn($e) => now() >= $e->start_time && now() <= $e->end_time);
        $upcomingExams = $exams->filter(fn($e) => now() < $e->start_time);
        $pastExams = $exams->filter(fn($e) => now() > $e->end_time);
    @endphp

    <!-- Stats -->
    <div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(150px,1fr))">
        <div class="stat-card">
            <div class="stat-icon">🟢</div>
            <div class="stat-value">{{ $activeExams->count() }}</div>
            <div class="stat-label">Active Now</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🔜</div>
            <div class="stat-value">{{ $upcomingExams->count() }}</div>
            <div class="stat-label">Upcoming</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-value">{{ $myScripts->count() }}</div>
            <div class="stat-label">Submitted</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏆</div>
            <div class="stat-value">{{ $myScripts->where('status','evaluated')->count() }}</div>
            <div class="stat-label">Evaluated</div>
        </div>
    </div>

    <!-- Active Exams -->
    @if($activeExams->isNotEmpty())
    <div class="card" style="border-left:4px solid #10b981">
        <div class="card-header"><h3>🟢 Active Exams — Upload Now!</h3></div>
        @foreach($activeExams as $exam)
        @php $myScript = $myScripts->get($exam->id); @endphp
        <div style="padding:20px 24px;border-bottom:1px solid var(--border)">
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-bold" style="font-size:1.05rem">{{ $exam->title }}</div>
                    <div class="text-sm text-muted mt-1">{{ $exam->course->name ?? '' }} · {{ $exam->questions->count() }} questions · {{ $exam->total_marks }} marks</div>
                    <div class="text-sm text-muted mt-1">Closes: {{ $exam->end_time->format('M d, Y g:i A') }}</div>
                </div>
                <div class="flex gap-2 items-center">
                    @if($myScript)
                        <span class="badge badge-green">✓ Submitted</span>
                    @else
                        @if(auth()->user()->balance >= $exam->total_marks)
                            <a href="{{ route('student.exams.show', $exam) }}" class="btn btn-primary btn-sm">View Paper & Upload</a>
                        @else
                            <div class="flex flex-col items-end gap-1">
                                <button class="btn btn-primary btn-sm" disabled style="opacity:0.6; cursor:not-allowed;" title="Insufficient Balance">View Paper & Upload</button>
                                <div class="text-xs text-red-500 font-bold" style="color:#ef4444">Needs {{ $exam->total_marks }} TK</div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
            @if($myScript)
            <div class="mt-2" style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:12px 16px;font-size:.85rem;color:#166534">
                ✅ You submitted your answer script on {{ $myScript->created_at->format('M d g:i A') }}.
                @if($myScript->status === 'evaluated')
                    <strong>Result: {{ $myScript->marks_obtained }}/{{ $exam->total_marks }}</strong>
                @else
                    Awaiting evaluation.
                @endif
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    <!-- Upcoming Exams -->
    @if($upcomingExams->isNotEmpty())
    <div class="card">
        <div class="card-header"><h3>🔜 Upcoming Exams</h3></div>
        @foreach($upcomingExams as $exam)
        <div style="padding:18px 24px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center">
            <div>
                <div class="font-bold">{{ $exam->title }}</div>
                <div class="text-sm text-muted mt-1">{{ $exam->course->name ?? '' }} · {{ $exam->questions->count() }} questions · {{ $exam->total_marks }} marks</div>
                <div class="text-sm text-muted">Starts: {{ $exam->start_time->format('M d, Y g:i A') }}</div>
            </div>
            <div class="flex gap-2">
                <span class="badge badge-yellow">Upcoming</span>
                <a href="{{ route('student.exams.show', $exam) }}" class="btn btn-outline btn-xs">View Details</a>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <!-- Past Exams -->
    @if($pastExams->isNotEmpty())
    <div class="card">
        <div class="card-header"><h3>📁 Past Exams</h3></div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr><th>Exam</th><th>Course</th><th>Ended</th><th>Status</th><th>Result</th></tr>
                </thead>
                <tbody>
                    @foreach($pastExams as $exam)
                    @php $myScript = $myScripts->get($exam->id); @endphp
                    <tr>
                        <td class="font-bold">{{ $exam->title }}</td>
                        <td><span class="badge badge-blue">{{ $exam->course->code ?? '—' }}</span></td>
                        <td class="text-sm text-muted">{{ $exam->end_time->format('M d, Y') }}</td>
                        <td>
                            @if(!$myScript)
                                <span class="badge badge-red">Not Submitted</span>
                            @elseif($myScript->status === 'evaluated')
                                <span class="badge badge-green">Evaluated</span>
                            @else
                                <span class="badge badge-yellow">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($myScript && $myScript->status === 'evaluated')
                                <strong>{{ $myScript->marks_obtained }}/{{ $exam->total_marks }}</strong>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @if($exams->isEmpty())
    <div class="card">
        <div class="card-body text-muted" style="text-align:center;padding:60px">
            <div style="font-size:3rem;margin-bottom:16px">📋</div>
            <div class="font-bold" style="font-size:1.1rem">No exams scheduled yet</div>
            <div class="mt-1">Check back later or contact your administrator.</div>
        </div>
    </div>
    @endif
</x-app-layout>
