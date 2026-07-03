<x-app-layout>
    <x-slot name="header">Grade Script</x-slot>
    <x-slot name="subheader">{{ $script->student->name }} · {{ $script->exam->title }}</x-slot>
    <x-slot name="headerActions">
        <a href="{{ route('evaluator.dashboard') }}" class="btn btn-outline btn-sm">← Back</a>
    </x-slot>

    <div style="display:grid;grid-template-columns:1fr 340px;gap:24px;height:calc(100vh - 200px);min-height:500px">
        <!-- PDF Viewer -->
        <div class="card" style="margin:0;display:flex;flex-direction:column">
            <div class="card-header">
                <h3>📄 {{ $script->student->name }}'s Answer Script</h3>
                <a href="{{ asset('storage/' . $script->file_path) }}" target="_blank" class="btn btn-outline btn-xs">Open in New Tab ↗</a>
            </div>
            <iframe src="{{ asset('storage/' . $script->file_path) }}"
                    style="flex:1;border:none;width:100%;background:#525659"
                    title="Answer Script PDF"></iframe>
        </div>

        <!-- Grading Panel -->
        <div style="display:flex;flex-direction:column;gap:16px">

            <!-- Questions Reference -->
            <div class="card" style="margin:0;overflow-y:auto;max-height:300px">
                <div class="card-header"><h3>📋 Question Marks</h3></div>
                <div style="padding:8px 0">
                    @foreach($script->exam->questions as $i => $question)
                    <div style="padding:10px 20px;border-bottom:1px solid var(--border);font-size:.85rem">
                        <div class="flex justify-between items-center">
                            <span style="color:var(--text-secondary)">Q{{ $i+1 }}</span>
                            <span class="badge badge-blue">{{ $question->marks }} pts</span>
                        </div>
                        <div style="color:var(--text-primary);margin-top:4px;line-height:1.4">{{ Str::limit($question->body, 80) }}</div>
                    </div>
                    @endforeach
                    <div style="padding:12px 20px;background:var(--surface-alt);font-weight:700;font-size:.875rem">
                        Total: {{ $script->exam->total_marks }} marks
                    </div>
                </div>
            </div>

            <!-- Grading Form -->
            <div class="card" style="margin:0">
                <div class="card-header">
                    <h3>🏆 Assign Mark</h3>
                    @if($script->status === 'evaluated')
                        <span class="badge badge-green">Evaluated</span>
                    @else
                        <span class="badge badge-yellow">Pending</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-error">
                        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
                    </div>
                    @endif

                    <form action="{{ route('evaluator.scripts.storeMarks', $script) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Marks Obtained</label>
                            <div style="display:flex;align-items:center;gap:12px">
                                <input type="number" name="marks_obtained" step="0.5" min="0"
                                       max="{{ $script->exam->total_marks }}"
                                       class="form-control"
                                       style="font-size:1.5rem;font-weight:700;text-align:center"
                                       value="{{ old('marks_obtained', $script->marks_obtained) }}"
                                       required>
                                <div style="color:var(--text-secondary);font-size:1.3rem;white-space:nowrap">/ {{ $script->exam->total_marks }}</div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-full" style="font-size:1rem;padding:12px">
                            ✅ Save Evaluation
                        </button>
                    </form>
                </div>
            </div>

            <!-- Student Info -->
            <div class="card" style="margin:0">
                <div class="card-body" style="padding:16px">
                    <table style="width:100%;font-size:.82rem;border-collapse:collapse">
                        <tr><td class="text-muted" style="padding:4px 0;width:80px">Student</td><td class="font-bold">{{ $script->student->name }}</td></tr>
                        <tr><td class="text-muted" style="padding:4px 0">Email</td><td>{{ $script->student->email }}</td></tr>
                        <tr><td class="text-muted" style="padding:4px 0">Exam</td><td>{{ $script->exam->title }}</td></tr>
                        <tr><td class="text-muted" style="padding:4px 0">Submitted</td><td>{{ $script->created_at->format('M d, g:i A') }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
