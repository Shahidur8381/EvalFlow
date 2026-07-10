<x-app-layout>
    <x-slot name="header">{{ $exam->title }}</x-slot>
    <x-slot name="subheader">{{ $exam->course->name ?? '' }} · {{ $exam->questions->count() }} questions · {{ $exam->total_marks }} marks total</x-slot>
    <x-slot name="headerActions">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm">← Back</a>
    </x-slot>

    <div class="grid-3" style="margin-bottom:24px;">
        <div class="stat-card">
            <div class="stat-icon">📝</div>
            <div class="stat-value">{{ $exam->questions->count() }}</div>
            <div class="stat-label">Questions</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏆</div>
            <div class="stat-value">{{ $exam->total_marks }}</div>
            <div class="stat-label">Total Marks</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📄</div>
            <div class="stat-value">{{ $exam->scripts->count() }}</div>
            <div class="stat-label">Submissions</div>
        </div>
    </div>

    <div class="grid-2">
        <!-- Left Column: Questions -->
        <div>
            <!-- Add Question Form -->
            <div class="card">
                <div class="card-header"><h3>➕ Add Question</h3></div>
                <div class="card-body">
                    <form action="{{ route('admin.exams.questions.store', $exam) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Question Body</label>
                            <textarea name="body" class="form-control" rows="3" placeholder="Enter the question text..." required style="resize:vertical">{{ old('body') }}</textarea>
                            @error('body')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <div class="grid-2">
                            <div class="form-group">
                                <label class="form-label">Marks</label>
                                <input type="number" name="marks" class="form-control" placeholder="e.g. 10" min="1" max="999" value="{{ old('marks', 10) }}" required>
                                @error('marks')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Media (Optional PDF)</label>
                                <input type="file" name="media" class="form-control" accept=".pdf" style="padding:6px; font-size:.85rem;">
                                @error('media')<div class="form-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-full">Add Question</button>
                    </form>
                </div>
            </div>

            <!-- Questions List -->
            <div class="card">
                <div class="card-header">
                    <h3>📋 Questions</h3>
                    <span class="text-muted text-sm">Total: {{ $exam->total_marks }} marks</span>
                </div>
                @if($exam->questions->isEmpty())
                    <div class="card-body text-muted">No questions added yet. Add your first question above.</div>
                @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Question</th>
                                <th>Marks</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($exam->questions as $i => $question)
                            <tr>
                                <td style="color:#94a3b8;width:40px">Q{{ $i+1 }}</td>
                                <td>
                                    <div>{{ $question->body }}</div>
                                    @if($question->media_path)
                                        <div style="margin-top:6px;">
                                            <a href="{{ asset('storage/' . $question->media_path) }}" target="_blank" class="btn btn-outline btn-xs" style="display:inline-flex; align-items:center; gap:4px; padding:2px 8px; font-size:.7rem;">
                                                📄 View Media
                                            </a>
                                        </div>
                                    @endif
                                </td>
                                <td><span class="badge badge-blue">{{ $question->marks }} pts</span></td>
                                <td>
                                    <form action="{{ route('admin.exams.questions.destroy', [$exam, $question]) }}" method="POST" onsubmit="return confirm('Remove this question?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Exam Info + Evaluator Assignment + Submissions -->
        <div>
            <!-- Exam Info -->
            <div class="card">
                <div class="card-header"><h3>ℹ️ Exam Details</h3></div>
                <div class="card-body">
                    <table style="width:100%;font-size:.875rem;border-collapse:collapse">
                        <tr><td class="text-muted" style="padding:6px 0;width:110px">Course</td><td><span class="badge badge-blue">{{ $exam->course->code ?? '—' }}</span></td></tr>
                        <tr><td class="text-muted" style="padding:6px 0">Status</td>
                            <td>
                                @if(now() < $exam->start_time)
                                    <span class="badge badge-yellow">Upcoming</span>
                                @elseif(now() > $exam->end_time)
                                    <span class="badge badge-gray">Ended</span>
                                @else
                                    <span class="badge badge-green">Active Now</span>
                                @endif
                            </td>
                        </tr>
                        <tr><td class="text-muted" style="padding:6px 0">Start</td><td>{{ $exam->start_time->format('M d, Y g:i A') }}</td></tr>
                        <tr><td class="text-muted" style="padding:6px 0">End</td><td>{{ $exam->end_time->format('M d, Y g:i A') }}</td></tr>
                    </table>
                </div>
            </div>

            <!-- Evaluator Assignment -->
            <div class="card">
                <div class="card-header"><h3>👨‍💼 Assign Evaluator</h3></div>
                <div class="card-body">
                    @if($exam->assignedEvaluator)
                        <div class="alert alert-success" style="margin-bottom:16px;">
                            Currently assigned to <strong>{{ $exam->assignedEvaluator->name }}</strong>
                        </div>
                    @else
                        <div class="alert alert-error" style="margin-bottom:16px;">
                            No evaluator assigned. Scripts cannot be graded yet.
                        </div>
                    @endif

                    <form action="{{ route('admin.exams.assign', $exam) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Select Evaluator</label>
                            <select name="evaluator_id" class="form-control">
                                <option value="">— Remove Assignment —</option>
                                @foreach($evaluators as $ev)
                                    <option value="{{ $ev->id }}" {{ $exam->assigned_evaluator_id == $ev->id ? 'selected' : '' }}>
                                        {{ $ev->name }} ({{ $ev->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Update Assignment</button>
                    </form>
                </div>
            </div>

            <!-- Submissions -->
            <div class="card">
                <div class="card-header">
                    <h3>📄 Submissions</h3>
                    <span class="text-muted text-sm">{{ $exam->scripts->count() }} total</span>
                </div>
                @if($exam->scripts->isEmpty())
                    <div class="card-body text-muted">No submissions yet.</div>
                @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr><th>Student</th><th>Status</th><th>Mark</th></tr>
                        </thead>
                        <tbody>
                            @foreach($exam->scripts as $script)
                            <tr>
                                <td>{{ $script->student->name }}</td>
                                <td>
                                    @if($script->status === 'evaluated')
                                        <span class="badge badge-green">Evaluated</span>
                                    @else
                                        <span class="badge badge-yellow">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($script->marks_obtained !== null)
                                        {{ $script->marks_obtained }}/{{ $exam->total_marks }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
