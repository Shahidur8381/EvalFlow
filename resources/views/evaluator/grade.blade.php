<x-app-layout>
    <x-slot name="header">Grade Script</x-slot>
    <x-slot name="subheader">{{ $script->student->name }} · {{ $script->exam->title }}</x-slot>
    <x-slot name="headerActions">
        <a href="{{ route('evaluator.dashboard') }}" class="btn btn-outline btn-sm">← Back to Dashboard</a>
    </x-slot>

    <style>
        /* ── Enhanced Grading Layout ─── */
        .grading-layout {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 20px;
            height: calc(100vh - 170px);
            min-height: 600px;
        }

        /* ── PDF Viewer Panel ─────────── */
        .pdf-panel {
            display: flex;
            flex-direction: column;
            background: #1e293b;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.08);
            overflow: hidden;
        }
        .pdf-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            background: #0f172a;
            border-bottom: 1px solid rgba(255,255,255,.08);
            gap: 10px;
            flex-shrink: 0;
        }
        .pdf-toolbar-left { display: flex; align-items: center; gap: 10px; }
        .pdf-filename {
            color: #cbd5e1;
            font-size: .82rem;
            font-weight: 600;
            font-family: monospace;
            background: rgba(255,255,255,.06);
            padding: 4px 10px;
            border-radius: 6px;
            max-width: 260px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .pdf-toolbar-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 7px;
            font-size: .78rem;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all .15s;
        }
        .pdf-btn-primary { background: #4f46e5; color: #fff; }
        .pdf-btn-primary:hover { background: #4338ca; }
        .pdf-btn-ghost { background: rgba(255,255,255,.06); color: #94a3b8; border: 1px solid rgba(255,255,255,.1); }
        .pdf-btn-ghost:hover { background: rgba(255,255,255,.12); color: #e2e8f0; }
        .pdf-frame {
            flex: 1;
            width: 100%;
            border: none;
            background: #525659;
        }
        .pdf-status-bar {
            padding: 6px 16px;
            background: #0f172a;
            border-top: 1px solid rgba(255,255,255,.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: .72rem;
            color: #475569;
            flex-shrink: 0;
        }

        /* ── Right Panel ─────────────── */
        .right-panel {
            display: flex;
            flex-direction: column;
            gap: 14px;
            overflow-y: auto;
            padding-right: 2px;
        }

        /* ── Per-question marks ──────── */
        .question-mark-row {
            display: flex;
            flex-direction: column;
            gap: 6px;
            padding: 14px 16px;
            border-bottom: 1px solid var(--border);
            position: relative;
        }
        .question-mark-row:last-of-type { border-bottom: none; }
        .q-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 8px;
        }
        .q-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px; height: 24px;
            background: #4f46e5;
            color: #fff;
            border-radius: 6px;
            font-size: .72rem;
            font-weight: 700;
            flex-shrink: 0;
            margin-top: 1px;
        }
        .q-body {
            font-size: .82rem;
            color: #e2e8f0;
            line-height: 1.5;
            flex: 1;
        }
        .q-max-badge {
            background: rgba(79,70,229,.15);
            color: #818cf8;
            border: 1px solid rgba(79,70,229,.25);
            padding: 2px 8px;
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 700;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .q-input-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 4px;
        }
        .q-marks-input {
            width: 80px;
            padding: 7px 10px;
            background: rgba(255,255,255,.06);
            border: 1.5px solid rgba(255,255,255,.12);
            border-radius: 7px;
            color: #fff;
            font-size: .95rem;
            font-weight: 700;
            text-align: center;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .q-marks-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79,70,229,.2);
        }
        .q-marks-input.has-value { border-color: #10b981; }
        .q-out-of { color: #64748b; font-size: .85rem; }
        .q-error { color: #f87171; font-size: .72rem; }

        /* ── Total bar ───────────────── */
        .total-bar {
            background: #1e293b;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .total-label { color: #94a3b8; font-size: .82rem; font-weight: 600; }
        .total-value { font-size: 1.4rem; font-weight: 900; color: #fff; }
        .total-value span { color: #475569; font-size: .9rem; }

        /* ── Student info mini ───────── */
        .student-mini {
            background: #1e293b;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px 16px;
        }
        .student-mini-name { font-weight: 700; font-size: .9rem; color: #e2e8f0; }
        .student-mini-meta { color: #64748b; font-size: .78rem; margin-top: 2px; }

        @media (max-width: 1100px) {
            .grading-layout { grid-template-columns: 1fr; height: auto; }
            .pdf-panel { height: 500px; }
        }
    </style>

    <div class="grading-layout">

        <!-- ── LEFT: Enhanced PDF Viewer ──────────────────── -->
        <div class="pdf-panel">
            <div class="pdf-toolbar">
                <div class="pdf-toolbar-left">
                    <span style="font-size:1rem">📄</span>
                    <div class="pdf-filename">{{ basename($script->file_path) }}</div>
                    @if($script->status === 'evaluated')
                        <span style="background:rgba(16,185,129,.15);color:#6ee7b7;border:1px solid rgba(16,185,129,.25);padding:3px 10px;border-radius:999px;font-size:.7rem;font-weight:700">✓ Evaluated</span>
                    @else
                        <span style="background:rgba(245,158,11,.12);color:#fcd34d;border:1px solid rgba(245,158,11,.25);padding:3px 10px;border-radius:999px;font-size:.7rem;font-weight:700">⏳ Pending</span>
                    @endif
                </div>
                <div style="display:flex;gap:8px">
                    <a href="{{ asset('storage/' . $script->file_path) }}"
                       target="_blank"
                       class="pdf-toolbar-btn pdf-btn-ghost">↗ Open Full</a>
                    <a href="{{ asset('storage/' . $script->file_path) }}"
                       download
                       class="pdf-toolbar-btn pdf-btn-primary">⬇ Download</a>
                </div>
            </div>

            <iframe
                src="{{ asset('storage/' . $script->file_path) }}#toolbar=1&navpanes=1&scrollbar=1&zoom=page-fit"
                class="pdf-frame"
                title="Answer Script — {{ $script->student->name }}"
                allowfullscreen>
            </iframe>

            <div class="pdf-status-bar">
                <span>Student: <strong style="color:#94a3b8">{{ $script->student->name }}</strong></span>
                <span>Submitted: {{ $script->created_at->format('M d, Y · g:i A') }}</span>
                <span>Exam: {{ $script->exam->title }}</span>
            </div>
        </div>

        <!-- ── RIGHT: Per-question Grading Panel ────────── -->
        <div class="right-panel">

            <!-- Student Info -->
            <div class="student-mini">
                <div style="display:flex;align-items:center;gap:10px">
                    <div style="width:36px;height:36px;background:#4f46e5;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:.9rem;flex-shrink:0">
                        {{ strtoupper(substr($script->student->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="student-mini-name">{{ $script->student->name }}</div>
                        <div class="student-mini-meta">{{ $script->student->email }}</div>
                    </div>
                </div>
            </div>

            <!-- Grading Form -->
            <div style="background:#1e293b;border:1px solid var(--border);border-radius:12px;overflow:hidden">
                <div style="padding:14px 18px;border-bottom:1px solid rgba(255,255,255,.07);display:flex;align-items:center;justify-content:space-between">
                    <div style="font-weight:700;color:#e2e8f0;font-size:.95rem">🏆 Mark per Question</div>
                    <div style="font-size:.78rem;color:#64748b">Max: {{ $script->exam->total_marks }} marks</div>
                </div>

                @if ($errors->any())
                <div style="margin:12px 16px;background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#f87171;border-radius:8px;padding:10px 14px;font-size:.82rem">
                    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                </div>
                @endif

                <form action="{{ route('evaluator.scripts.storeMarks', $script) }}" method="POST" id="grading-form">
                    @csrf

                    @foreach($script->exam->questions as $i => $question)
                    @php $existing = $script->markForQuestion($question->id); @endphp
                    <div class="question-mark-row">
                        <div class="q-header">
                            <span class="q-number">{{ $i + 1 }}</span>
                            <div class="q-body">{{ $question->body }}</div>
                            <span class="q-max-badge">/ {{ $question->marks }}</span>
                        </div>
                        <div class="q-input-row">
                            <input
                                type="number"
                                name="marks[{{ $question->id }}]"
                                class="q-marks-input {{ $existing !== null ? 'has-value' : '' }}"
                                min="0"
                                max="{{ $question->marks }}"
                                step="0.5"
                                value="{{ old("marks.{$question->id}", $existing) }}"
                                placeholder="0"
                                oninput="updateTotal()"
                                data-max="{{ $question->marks }}"
                                required>
                            <span class="q-out-of">out of {{ $question->marks }} marks</span>
                        </div>
                        @error("marks.{$question->id}")
                            <div class="q-error">{{ $message }}</div>
                        @enderror
                    </div>
                    @endforeach

                    <!-- Running Total -->
                    <div style="padding:14px 16px;border-top:1px solid rgba(255,255,255,.07)">
                        <div class="total-bar" style="margin-bottom:12px">
                            <span class="total-label">Running Total</span>
                            <div class="total-value" id="running-total">
                                0 <span>/ {{ $script->exam->total_marks }}</span>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success w-full" style="padding:13px;font-size:.95rem;justify-content:center">
                            ✅ Save All Marks
                        </button>
                    </div>
                </form>
            </div>

            @if($script->status === 'evaluated')
            <!-- Saved marks summary -->
            <div style="background:rgba(16,185,129,.06);border:1px solid rgba(16,185,129,.2);border-radius:10px;padding:16px">
                <div style="font-weight:700;color:#6ee7b7;margin-bottom:10px">✅ Previously Saved Marks</div>
                @foreach($script->exam->questions as $i => $question)
                @php $saved = $script->markForQuestion($question->id); @endphp
                <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid rgba(255,255,255,.05);font-size:.82rem">
                    <span style="color:#94a3b8">Q{{ $i+1 }}</span>
                    <span style="color:#e2e8f0;font-weight:600">{{ $saved ?? '—' }} / {{ $question->marks }}</span>
                </div>
                @endforeach
                <div style="display:flex;justify-content:space-between;padding:8px 0 0;font-weight:800">
                    <span style="color:#6ee7b7">Total</span>
                    <span style="color:#6ee7b7;font-size:1.1rem">{{ $script->marks_obtained }} / {{ $script->exam->total_marks }}</span>
                </div>
            </div>
            @endif

        </div>
    </div>

    <script>
        function updateTotal() {
            const inputs = document.querySelectorAll('.q-marks-input');
            let total = 0;
            inputs.forEach(inp => {
                const val = parseFloat(inp.value) || 0;
                total += val;
                inp.classList.toggle('has-value', inp.value !== '');
            });
            document.getElementById('running-total').innerHTML =
                `<strong>${total}</strong> <span>/ {{ $script->exam->total_marks }}</span>`;
        }
        // Init on load
        document.addEventListener('DOMContentLoaded', updateTotal);
    </script>
</x-app-layout>
