<x-app-layout>
    <x-slot name="header">{{ $exam->title }}</x-slot>
    <x-slot name="subheader">{{ $exam->course->name ?? '' }} · {{ $exam->questions->count() }} questions · {{ $exam->total_marks }} marks total</x-slot>
    <x-slot name="headerActions">
        <a href="{{ route('student.dashboard') }}" class="btn btn-outline btn-sm">← Back</a>
    </x-slot>

    @php
        $isActive = now() >= $exam->start_time && now() <= $exam->end_time;
        $isUpcoming = now() < $exam->start_time;
        $isEnded = now() > $exam->end_time;
    @endphp

    <!-- Exam Status Banner -->
    @if($isActive && !$myScript)
    <div class="alert alert-success" style="border-left:4px solid #10b981">
        🟢 This exam is <strong>currently active</strong>. You can upload your answer script below before <strong>{{ $exam->end_time->format('M d, Y g:i A') }}</strong>.
    </div>
    @elseif($isUpcoming)
    <div class="alert" style="background:#fef9c3;border:1px solid #fde68a;color:#92400e;border-left:4px solid #f59e0b">
        🔜 This exam starts at <strong>{{ $exam->start_time->format('M d, Y g:i A') }}</strong>. Come back then to upload your script.
    </div>
    @elseif($isEnded && !$myScript)
    <div class="alert alert-error">
        ❌ This exam has ended and you did not submit a script.
    </div>
    @endif

    <div class="grid-2" style="align-items:start">
        <!-- Question Paper -->
        <div class="card">
            <div class="card-header">
                <h3>📋 Question Paper</h3>
                <span class="badge badge-blue">{{ $exam->total_marks }} marks</span>
            </div>
            <div class="card-body">
                @if($isUpcoming)
                    <div class="text-muted" style="text-align:center;padding:40px">
                        <div style="font-size:2rem;margin-bottom:12px">🔒</div>
                        <div class="font-bold">Paper Locked</div>
                        <div class="text-sm mt-1">The question paper will be revealed when the exam starts.</div>
                    </div>
                @elseif($exam->questions->isEmpty())
                    <div class="text-muted">No questions have been added to this exam yet.</div>
                @else
                <div style="display:flex;flex-direction:column;gap:16px">
                    @foreach($exam->questions as $i => $question)
                    <div style="background:var(--surface-alt);border:1px solid var(--border);border-radius:10px;padding:16px">
                        <div class="flex items-center justify-between" style="margin-bottom:8px">
                            <span style="font-weight:700;color:var(--brand)">Question {{ $i+1 }}</span>
                            <span class="badge badge-purple">{{ $question->marks }} marks</span>
                        </div>
                        <div style="color:var(--text-primary);line-height:1.6">{{ $question->body }}</div>
                        @if($question->media_path)
                            <div style="margin-top:12px;">
                                <a href="{{ asset('storage/' . $question->media_path) }}" target="_blank" class="btn btn-outline btn-sm" style="display:inline-flex; align-items:center; gap:6px;">
                                    📄 Open Attached PDF
                                </a>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Upload / Status Panel -->
        <div>
            @if($myScript)
            <!-- Already Submitted -->
            <div class="card" style="border-top:4px solid #10b981">
                <div class="card-header"><h3>✅ Submission Status</h3></div>
                <div class="card-body">
                    <div class="flex gap-3 items-center" style="margin-bottom:16px; justify-content: space-between;">
                        <div class="flex gap-3 items-center">
                            <div style="font-size:2.5rem">📄</div>
                            <div>
                                <div class="font-bold">Script Uploaded</div>
                                <div class="text-sm text-muted">{{ $myScript->created_at->format('M d, Y g:i A') }}</div>
                            </div>
                        </div>
                        <a href="{{ asset('storage/' . $myScript->file_path) }}" target="_blank" class="btn btn-outline btn-sm">↗ Open Full</a>
                    </div>

                    <div style="border:1px solid var(--border); border-radius:8px; overflow:hidden; margin-bottom:20px; height: 350px;">
                        <iframe src="{{ asset('storage/' . $myScript->file_path) }}#toolbar=0&navpanes=0&view=FitH" width="100%" height="100%" style="border:none;"></iframe>
                    </div>

                    @if($myScript->status === 'evaluated')
                    <!-- Total Score -->
                    <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:20px;text-align:center;margin-bottom:16px">
                        <div style="font-size:2.5rem;font-weight:900;color:#15803d;line-height:1">
                            {{ $myScript->marks_obtained }}<span style="font-size:1.1rem;color:#86efac;font-weight:600">/{{ $exam->total_marks }}</span>
                        </div>
                        <div class="text-sm" style="color:#166534;margin-top:6px;font-weight:600">🎉 Your Final Score</div>
                    </div>

                    <!-- Per-question breakdown -->
                    @if($myScript->scriptMarks->isNotEmpty())
                    <div style="border:1px solid #bbf7d0;border-radius:10px;overflow:hidden">
                        <div style="background:#f0fdf4;padding:10px 14px;font-size:.78rem;font-weight:700;color:#15803d;border-bottom:1px solid #bbf7d0">
                            📊 Mark Breakdown by Question
                        </div>
                        @foreach($exam->questions as $i => $question)
                        @php
                            $mark = $myScript->scriptMarks->where('question_id', $question->id)->first();
                        @endphp
                        <div style="padding:10px 14px;border-bottom:1px solid #dcfce7;display:flex;justify-content:space-between;align-items:center;font-size:.83rem;{{ $loop->last ? 'border-bottom:none' : '' }}">
                            <div style="display:flex;gap:8px;align-items:flex-start;flex:1">
                                <span style="background:#4f46e5;color:#fff;border-radius:5px;padding:2px 7px;font-size:.7rem;font-weight:700;flex-shrink:0">Q{{ $i+1 }}</span>
                                <span style="color:#374151;line-height:1.4">{{ Str::limit($question->body, 70) }}</span>
                            </div>
                            <div style="font-weight:700;color:#166534;white-space:nowrap;margin-left:12px">
                                {{ $mark ? $mark->marks_obtained : '—' }}
                                <span style="color:#86efac;font-weight:400">/{{ $question->marks }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    @else
                    <div style="background:#fef9c3;border:1px solid #fde68a;border-radius:10px;padding:16px;text-align:center;color:#92400e">
                        ⏳ Your script is awaiting evaluation.
                    </div>
                    @endif
                </div>
            </div>
            @elseif($isActive)
            <!-- Upload Form -->
            <div class="card" style="border-top:4px solid var(--brand)">
                <div class="card-header"><h3>📤 Upload Answer Script</h3></div>
                <div class="card-body">
                    <form action="{{ route('student.scripts.upload', $exam) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="background:#ede9fe;border:1px solid #c4b5fd;border-radius:10px;padding:16px;margin-bottom:20px;font-size:.85rem;color:#5b21b6">
                            <strong>Instructions:</strong><br>
                            • Write your answers on paper.<br>
                            • Click photos with a PDF generator app (e.g. CamScanner) and upload the PDF.<br>
                            • PDF files only (max 20MB).<br>
                            • You can only submit <strong>once</strong> per exam.<br>
                            • Make sure your script is complete before uploading.
                        </div>
                        <div class="form-group">
                            <label class="form-label">Select PDF File</label>
                            <input type="file" name="answer_script" accept=".pdf" required
                                   style="width:100%;padding:10px;border:2px dashed var(--border);border-radius:8px;font-size:.875rem;cursor:pointer;background:var(--surface-alt)">
                            @error('answer_script')<div class="form-error">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-primary w-full" style="font-size:1rem;padding:12px">
                            🚀 Submit Answer Script
                        </button>
                    </form>
                </div>
            </div>
            @else
            <!-- Not active -->
            <div class="card">
                <div class="card-body text-muted" style="text-align:center;padding:40px">
                    @if($isUpcoming)
                    <div style="font-size:2rem;margin-bottom:12px">⏳</div>
                    <div class="font-bold">Exam hasn't started yet</div>
                    <div class="text-sm mt-1">Upload will be available when the exam begins.</div>
                    @else
                    <div style="font-size:2rem;margin-bottom:12px">🔒</div>
                    <div class="font-bold">Submission window closed</div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Exam Info -->
            <div class="card" style="margin-top:0">
                <div class="card-header"><h3>ℹ️ Exam Info</h3></div>
                <div class="card-body">
                    <table style="width:100%;font-size:.875rem;border-collapse:collapse">
                        <tr><td class="text-muted" style="padding:6px 0;width:100px">Course</td><td><span class="badge badge-blue">{{ $exam->course->code ?? '—' }}</span></td></tr>
                        <tr><td class="text-muted" style="padding:6px 0">Questions</td><td>{{ $exam->questions->count() }}</td></tr>
                        <tr><td class="text-muted" style="padding:6px 0">Total Marks</td><td><strong>{{ $exam->total_marks }}</strong></td></tr>
                        <tr><td class="text-muted" style="padding:6px 0">Starts</td><td>{{ $exam->start_time->format('M d, Y g:i A') }}</td></tr>
                        <tr><td class="text-muted" style="padding:6px 0">Ends</td><td>{{ $exam->end_time->format('M d, Y g:i A') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
