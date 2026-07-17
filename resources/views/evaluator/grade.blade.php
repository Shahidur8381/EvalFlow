<x-app-layout>
    <x-slot name="header">Grade Script</x-slot>
    <x-slot name="subheader">{{ $script->student->name }} · {{ $script->exam->title }}</x-slot>
    <x-slot name="headerActions">
        <a href="{{ route('evaluator.dashboard') }}" class="btn btn-outline btn-sm">← Back</a>
    </x-slot>

    <style>
        /* Override main padding for full-bleed layout */
        .main-content > .page-header { display: none; }

        .grading-wrap {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 0;
            height: calc(100vh - 64px);
            overflow: hidden;
            margin: -24px;
        }

        /* ── LEFT: PDF Viewer ── */
        .pdf-panel {
            display: flex;
            flex-direction: column;
            background: #1a1f2e;
            border-right: 1px solid rgba(255,255,255,.08);
            overflow: hidden;
        }
        .pdf-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 16px;
            background: #0f172a;
            border-bottom: 1px solid rgba(255,255,255,.08);
            flex-shrink: 0;
            gap: 12px;
        }
        .pdf-topbar-left { display: flex; align-items: center; gap: 10px; min-width: 0; }
        .pdf-filename {
            color: #cbd5e1; font-size: .8rem; font-weight: 600; font-family: monospace;
            background: rgba(255,255,255,.07); padding: 4px 10px; border-radius: 6px;
            max-width: 280px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }
        .pdf-status-badge {
            padding: 3px 10px; border-radius: 999px; font-size: .68rem; font-weight: 700;
            white-space: nowrap; flex-shrink: 0;
        }
        .badge-evaluated { background: rgba(16,185,129,.15); color: #6ee7b7; border: 1px solid rgba(16,185,129,.25); }
        .badge-pending    { background: rgba(245,158,11,.12); color: #fcd34d; border: 1px solid rgba(245,158,11,.25); }
        .pdf-topbar-actions { display: flex; gap: 8px; flex-shrink: 0; }
        .pdftb-btn {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 6px 12px; border-radius: 6px; font-size: .76rem; font-weight: 600;
            text-decoration: none; border: none; cursor: pointer; transition: all .15s;
        }
        .pdftb-ghost   { background: rgba(255,255,255,.07); color: #94a3b8; border: 1px solid rgba(255,255,255,.1); }
        .pdftb-ghost:hover { background: rgba(255,255,255,.12); color: #e2e8f0; }
        .pdftb-primary { background: #4f46e5; color: #fff; }
        .pdftb-primary:hover { background: #4338ca; }

        .pdf-iframe { flex: 1; width: 100%; border: none; background: #525659; display: block; }

        .pdf-footbar {
            padding: 5px 16px; background: #0f172a; border-top: 1px solid rgba(255,255,255,.07);
            display: flex; align-items: center; justify-content: space-between;
            font-size: .7rem; color: #475569; flex-shrink: 0;
        }

        /* ── RIGHT: Grading Panel ── */
        .grade-panel {
            display: flex;
            flex-direction: column;
            background: #f8fafc;
            overflow: hidden;
        }

        .grade-panel-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 20px; background: #fff;
            border-bottom: 1.5px solid #e2e8f0;
            flex-shrink: 0;
        }
        .grade-panel-header h2 { font-size: .95rem; font-weight: 800; color: #0f172a; margin: 0; }
        .grade-panel-header .max-marks { font-size: .78rem; color: #64748b; font-weight: 600; }

        /* Student info strip */
        .student-strip {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 20px; background: #fff;
            border-bottom: 1px solid #e2e8f0; flex-shrink: 0;
        }
        .student-avatar {
            width: 34px; height: 34px; background: #4f46e5; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; color: #fff; font-size: .85rem; flex-shrink: 0;
        }
        .student-name  { font-weight: 700; font-size: .88rem; color: #0f172a; }
        .student-email { font-size: .75rem; color: #64748b; }

        /* Scrollable questions area */
        .questions-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 0;
        }

        .question-row {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: #fff;
            margin-bottom: 1px;
        }
        .question-row:last-child { border-bottom: none; }

        .q-top {
            display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px;
        }
        .q-num {
            width: 24px; height: 24px; background: #4f46e5; color: #fff;
            border-radius: 6px; font-size: .72rem; font-weight: 800;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .q-text { flex: 1; font-size: .85rem; color: #1e293b; line-height: 1.5; }
        .q-max  {
            background: rgba(79,70,229,.08); color: #4f46e5;
            border: 1px solid rgba(79,70,229,.2); padding: 2px 9px;
            border-radius: 999px; font-size: .7rem; font-weight: 700; white-space: nowrap;
        }
        .q-input-row {
            display: flex; align-items: center; gap: 10px;
        }
        .q-input {
            width: 80px; padding: 8px 10px; text-align: center;
            font-size: 1rem; font-weight: 800; color: #0f172a;
            background: #f8fafc; border: 2px solid #e2e8f0;
            border-radius: 8px; outline: none;
            transition: border-color .15s, box-shadow .15s;
            font-family: 'Inter', sans-serif;
        }
        .q-input:focus { border-color: #4f46e5; box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
        .q-input.filled { border-color: #10b981; background: #f0fdf4; }
        .q-hint { font-size: .8rem; color: #94a3b8; }
        .q-err  { font-size: .72rem; color: #ef4444; margin-top: 4px; }

        /* Bottom: total + submit */
        .grade-footer {
            background: #fff;
            border-top: 2px solid #e2e8f0;
            padding: 14px 20px;
            flex-shrink: 0;
        }
        .total-row {
            display: flex; align-items: center; justify-content: space-between;
            background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px;
            padding: 12px 16px; margin-bottom: 12px;
        }
        .total-label { font-size: .82rem; font-weight: 600; color: #64748b; }
        .total-val   { font-size: 1.5rem; font-weight: 900; color: #0f172a; line-height: 1; }
        .total-val span { font-size: .9rem; color: #94a3b8; font-weight: 600; }

        .submit-btn {
            width: 100%; padding: 13px;
            background: #10b981; color: #fff;
            border: none; border-radius: 10px;
            font-size: .95rem; font-weight: 800;
            cursor: pointer; font-family: 'Inter', sans-serif;
            transition: background .15s, transform .1s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .submit-btn:hover { background: #059669; transform: translateY(-1px); }
        .submit-btn:active { transform: translateY(0); }

        /* Previously saved */
        .saved-summary {
            background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px;
            padding: 12px 16px; margin-top: 10px;
        }
        .saved-summary-title { font-size: .78rem; font-weight: 700; color: #16a34a; margin-bottom: 8px; }
        .saved-row {
            display: flex; justify-content: space-between;
            padding: 4px 0; font-size: .78rem; border-bottom: 1px solid rgba(0,0,0,.05);
        }
        .saved-row:last-child { border-bottom: none; padding-top: 8px; font-weight: 800; }

        /* Error banner */
        .err-banner {
            margin: 10px 20px; background: rgba(239,68,68,.07);
            border: 1px solid rgba(239,68,68,.2); color: #dc2626;
            border-radius: 8px; padding: 10px 14px; font-size: .82rem;
        }

        @media (max-width: 1100px) {
            .grading-wrap { grid-template-columns: 1fr; height: auto; }
            .pdf-panel { height: 50vh; }
            .grade-panel { height: auto; }
            .questions-scroll { max-height: 400px; }
        }
    </style>

    <div class="grading-wrap">

        {{-- ── LEFT: PDF Viewer ── --}}
        <div class="pdf-panel">
            <div class="pdf-topbar">
                <div class="pdf-topbar-left">
                    <span style="font-size:1.1rem">📄</span>
                    <div class="pdf-filename">{{ basename($script->file_path) }}</div>
                    @if($script->status === 'evaluated')
                        <span class="pdf-status-badge badge-evaluated">✓ Evaluated</span>
                    @else
                        <span class="pdf-status-badge badge-pending">⏳ Pending</span>
                    @endif
                </div>
                <div class="pdf-topbar-actions">
                    <a href="{{ asset('storage/' . $script->file_path) }}" target="_blank" class="pdftb-btn pdftb-ghost">↗ Full</a>
                    <a href="{{ asset('storage/' . $script->file_path) }}" download class="pdftb-btn pdftb-primary">⬇ Download</a>
                </div>
            </div>

            <iframe
                src="{{ asset('storage/' . $script->file_path) }}#toolbar=1&navpanes=1&scrollbar=1&zoom=page-fit"
                class="pdf-iframe"
                title="Answer Script — {{ $script->student->name }}">
            </iframe>

            <div class="pdf-footbar">
                <span>Student: <strong style="color:#94a3b8">{{ $script->student->name }}</strong></span>
                <span>{{ $script->created_at->format('M d, Y · g:i A') }}</span>
                <span>{{ $script->exam->title }}</span>
            </div>
        </div>

        {{-- ── RIGHT: Grading Panel ── --}}
        <div class="grade-panel">

            <div class="grade-panel-header">
                <h2>🏆 Mark per Question</h2>
                <div class="max-marks">Max: {{ $script->exam->total_marks }} marks</div>
            </div>

            <div class="student-strip">
                <div class="student-avatar">{{ strtoupper(substr($script->student->name, 0, 1)) }}</div>
                <div>
                    <div class="student-name">{{ $script->student->name }}</div>
                    <div class="student-email">{{ $script->student->email }}</div>
                </div>
            </div>

            <form action="{{ route('evaluator.scripts.storeMarks', $script) }}" method="POST" id="grading-form" style="display:flex;flex-direction:column;flex:1;overflow:hidden;">
                @csrf

                @if ($errors->any())
                    <div class="err-banner">
                        @foreach($errors->all() as $error)<div>⚠ {{ $error }}</div>@endforeach
                    </div>
                @endif

                <div class="questions-scroll">
                    @foreach($script->exam->questions as $i => $question)
                        @php $existing = $script->markForQuestion($question->id); @endphp
                        <div class="question-row">
                            <div class="q-top">
                                <span class="q-num">{{ $i + 1 }}</span>
                                <div class="q-text">
                                    {{ $question->body }}
                                    @if($question->media_path)
                                        <div style="margin-top:6px">
                                            <a href="{{ asset('storage/' . $question->media_path) }}" target="_blank"
                                               style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;background:rgba(79,70,229,.08);color:#4f46e5;border:1px solid rgba(79,70,229,.2);border-radius:6px;font-size:.72rem;font-weight:600;text-decoration:none;">
                                                📄 View Question PDF
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <span class="q-max">/ {{ $question->marks }}</span>
                            </div>
                            <div class="q-input-row">
                                <input
                                    type="number"
                                    name="marks[{{ $question->id }}]"
                                    class="q-input {{ $existing !== null ? 'filled' : '' }}"
                                    min="0"
                                    max="{{ $question->marks }}"
                                    step="0.5"
                                    value="{{ old("marks.{$question->id}", $existing) }}"
                                    placeholder="0"
                                    oninput="updateTotal()"
                                    data-max="{{ $question->marks }}"
                                    required>
                                <span class="q-hint">out of {{ $question->marks }} marks</span>
                            </div>
                            @error("marks.{$question->id}")
                                <div class="q-err">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach

                    @if($script->status === 'evaluated')
                        <div style="margin: 16px 20px 16px;">
                            <div class="saved-summary">
                                <div class="saved-summary-title">✅ Previously Saved Marks</div>
                                @foreach($script->exam->questions as $i => $question)
                                    @php $saved = $script->markForQuestion($question->id); @endphp
                                    <div class="saved-row">
                                        <span style="color:#64748b">Q{{ $i+1 }}</span>
                                        <span style="color:#0f172a;font-weight:600">{{ $saved ?? '—' }} / {{ $question->marks }}</span>
                                    </div>
                                @endforeach
                                <div class="saved-row">
                                    <span style="color:#16a34a">Total</span>
                                    <span style="color:#16a34a;font-size:1rem">{{ $script->marks_obtained }} / {{ $script->exam->total_marks }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="grade-footer">
                    <div class="total-row">
                        <span class="total-label">Running Total</span>
                        <div class="total-val" id="running-total">
                            0 <span>/ {{ $script->exam->total_marks }}</span>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">
                        ✅ Save All Marks
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        function updateTotal() {
            const inputs = document.querySelectorAll('.q-input');
            let total = 0;
            inputs.forEach(inp => {
                const val = parseFloat(inp.value) || 0;
                total += val;
                inp.classList.toggle('filled', inp.value !== '');
            });
            document.getElementById('running-total').innerHTML =
                `<strong>${total}</strong> <span>/ {{ $script->exam->total_marks }}</span>`;
        }
        document.addEventListener('DOMContentLoaded', updateTotal);
    </script>
</x-app-layout>
