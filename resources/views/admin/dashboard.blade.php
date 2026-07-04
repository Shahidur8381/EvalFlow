<x-app-layout>
    <x-slot name="header">Admin Dashboard</x-slot>
    <x-slot name="subheader">Manage courses, exams, and evaluator assignments.</x-slot>

    @php
        $activeExams   = $exams->filter(fn($e) => now() >= $e->start_time && now() <= $e->end_time)->count();
        $pastExams     = $exams->filter(fn($e) => now() > $e->end_time)->count();
        $upcomingExams = $exams->filter(fn($e) => now() < $e->start_time)->count();
    @endphp

    <!-- Stats -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📋</div>
            <div class="stat-value">{{ $exams->count() }}</div>
            <div class="stat-label">Total Exams</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🟢</div>
            <div class="stat-value">{{ $activeExams }}</div>
            <div class="stat-label">Active Now</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🔜</div>
            <div class="stat-value">{{ $upcomingExams }}</div>
            <div class="stat-label">Upcoming</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">📚</div>
            <div class="stat-value">{{ $courses->count() }}</div>
            <div class="stat-label">Courses</div>
        </div>
    </div>

    <div class="grid-2">
        <!-- Create Course -->
        <div class="card">
            <div class="card-header"><h3>➕ Create Course</h3></div>
            <div class="card-body">
                <form action="{{ route('admin.courses.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Course Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Mathematics" value="{{ old('name') }}" required>
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Course Code</label>
                        <input type="text" name="code" class="form-control" placeholder="e.g. MATH101" value="{{ old('code') }}" required>
                        @error('code')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Create Course</button>
                </form>
            </div>
        </div>

        <!-- Create Exam -->
        <div class="card">
            <div class="card-header"><h3>📝 Create New Exam</h3></div>
            <div class="card-body">
                <form action="{{ route('admin.exams.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Course</label>
                        <select name="course_id" class="form-control" required>
                            <option value="">— Select Course —</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }} ({{ $course->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Exam Title</label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Mid-term Exam 2026" value="{{ old('title') }}" required>
                        @error('title')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Start Time</label>
                        <input type="datetime-local" name="start_time" class="form-control" value="{{ old('start_time') }}" required>
                        @error('start_time')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">End Time</label>
                        <input type="datetime-local" name="end_time" class="form-control" value="{{ old('end_time') }}" required>
                        @error('end_time')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Create Exam</button>
                </form>
            </div>
        </div>
    </div>

    <!-- All Exams Table -->
    <div class="card">
        <div class="card-header">
            <h3>📋 All Exams</h3>
            <span class="text-muted text-sm">{{ $exams->count() }} total</span>
        </div>
        @if($exams->isEmpty())
            <div class="card-body text-muted">No exams created yet. Use the form above to get started.</div>
        @else
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Exam Title</th>
                        <th>Course</th>
                        <th>Questions</th>
                        <th>Total Marks</th>
                        <th>Window</th>
                        <th>Status</th>
                        <th>Evaluator</th>
                        <th>Submissions</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exams as $exam)
                    <tr>
                        <td class="font-bold">{{ $exam->title }}</td>
                        <td><span class="badge badge-blue">{{ $exam->course->code ?? '—' }}</span></td>
                        <td>{{ $exam->questions->count() }}</td>
                        <td>{{ $exam->total_marks }}</td>
                        <td class="text-sm text-muted">
                            {{ $exam->start_time->format('M d, g:i A') }}<br>
                            <span style="color:#94a3b8">→</span> {{ $exam->end_time->format('M d, g:i A') }}
                        </td>
                        <td>
                            @if(now() < $exam->start_time)
                                <span class="badge badge-yellow">Upcoming</span>
                            @elseif(now() > $exam->end_time)
                                <span class="badge badge-gray">Ended</span>
                            @else
                                <span class="badge badge-green">Active</span>
                            @endif
                        </td>
                        <td class="text-sm">
                            @if($exam->assignedEvaluator)
                                <span class="badge badge-purple">{{ $exam->assignedEvaluator->name }}</span>
                            @else
                                <span class="text-muted">Unassigned</span>
                            @endif
                        </td>
                        <td>{{ $exam->scripts->count() }}</td>
                        <td>
                            <div class="flex gap-2">
                                <a href="{{ route('admin.exams.show', $exam) }}" class="btn btn-outline btn-xs">Manage</a>
                                <form action="{{ route('admin.exams.destroy', $exam) }}" method="POST" onsubmit="return confirm('Delete this exam and all submissions?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-app-layout>
