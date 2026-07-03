<x-app-layout>
    <x-slot name="header">Evaluator Dashboard</x-slot>
    <x-slot name="subheader">Scripts assigned to you for grading.</x-slot>

    <!-- Stats -->
    <div class="stats-grid" style="grid-template-columns:repeat(auto-fit,minmax(150px,1fr))">
        <div class="stat-card">
            <div class="stat-icon">📄</div>
            <div class="stat-value">{{ $scripts->count() }}</div>
            <div class="stat-label">Total Scripts</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">⏳</div>
            <div class="stat-value">{{ $pending }}</div>
            <div class="stat-label">Pending</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-value">{{ $evaluated }}</div>
            <div class="stat-label">Evaluated</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📊</div>
            <div class="stat-value">{{ $scripts->count() > 0 ? round(($evaluated/$scripts->count())*100) : 0 }}%</div>
            <div class="stat-label">Progress</div>
        </div>
    </div>

    @if($scripts->isEmpty())
    <div class="card">
        <div class="card-body" style="text-align:center;padding:60px;color:var(--text-secondary)">
            <div style="font-size:3rem;margin-bottom:16px">📭</div>
            <div class="font-bold" style="font-size:1.1rem">No scripts assigned to you</div>
            <div class="mt-1 text-sm">The administrator will assign exam papers to you. Check back later.</div>
        </div>
    </div>
    @else

    @php
        $byExam = $scripts->groupBy('exam_id');
    @endphp

    @foreach($byExam as $examId => $examScripts)
    @php $exam = $examScripts->first()->exam; @endphp
    <div class="card" style="margin-bottom:24px">
        <div class="card-header">
            <div>
                <h3>{{ $exam->title }}</h3>
                <div class="text-sm text-muted mt-1">{{ $exam->course->name ?? '' }} · {{ $exam->total_marks }} marks total</div>
            </div>
            <div class="flex gap-2 items-center">
                <span class="badge badge-green">{{ $examScripts->where('status','evaluated')->count() }}/{{ $examScripts->count() }} graded</span>
                @if(now() > $exam->end_time)
                    <span class="badge badge-gray">Closed</span>
                @else
                    <span class="badge badge-green">Active</span>
                @endif
            </div>
        </div>
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Mark</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($examScripts as $script)
                    <tr>
                        <td>
                            <div class="font-bold">{{ $script->student->name }}</div>
                            <div class="text-sm text-muted">{{ $script->student->email }}</div>
                        </td>
                        <td class="text-sm text-muted">{{ $script->created_at->format('M d, g:i A') }}</td>
                        <td>
                            @if($script->status === 'evaluated')
                                <span class="badge badge-green">Evaluated</span>
                            @else
                                <span class="badge badge-yellow">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if($script->marks_obtained !== null)
                                <strong>{{ $script->marks_obtained }}/{{ $exam->total_marks }}</strong>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('evaluator.scripts.show', $script) }}" class="btn btn-primary btn-xs">
                                {{ $script->status === 'evaluated' ? 'Edit Grade' : 'Grade' }}
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
    @endif
</x-app-layout>
