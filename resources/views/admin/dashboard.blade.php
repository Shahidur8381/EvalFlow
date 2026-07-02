<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Create Course</h3>
                <form action="{{ route('admin.courses.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('Course Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="code" :value="__('Course Code')" />
                        <x-text-input id="code" name="code" type="text" class="mt-1 block w-full" required />
                    </div>
                    <x-primary-button>{{ __('Create Course') }}</x-primary-button>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Create Exam</h3>
                <form action="{{ route('admin.exams.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <x-input-label for="course_id" :value="__('Course')" />
                        <select id="course_id" name="course_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full" required>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}">{{ $course->name }} ({{ $course->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="title" :value="__('Exam Title')" />
                        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" required />
                    </div>
                    <div>
                        <x-input-label for="total_marks" :value="__('Total Marks')" />
                        <x-text-input id="total_marks" name="total_marks" type="number" value="100" class="mt-1 block w-full" required />
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="start_time" :value="__('Start Time')" />
                            <x-text-input id="start_time" name="start_time" type="datetime-local" class="mt-1 block w-full" required />
                        </div>
                        <div>
                            <x-input-label for="end_time" :value="__('End Time')" />
                            <x-text-input id="end_time" name="end_time" type="datetime-local" class="mt-1 block w-full" required />
                        </div>
                    </div>
                    <x-primary-button>{{ __('Create Exam') }}</x-primary-button>
                </form>
            </div>

            <!-- List of All Exams -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mt-6">
                <h3 class="text-lg font-bold mb-4">All Exams (Current & Past)</h3>
                @if($exams->isEmpty())
                    <p class="text-gray-500">No exams have been created yet.</p>
                @else
                    <div class="space-y-4">
                        @foreach($exams as $exam)
                            <div class="border p-4 rounded-lg shadow-sm">
                                <h4 class="font-bold text-md">{{ $exam->title }} <span class="text-sm font-normal text-gray-500">({{ $exam->course->code ?? 'No Course' }})</span></h4>
                                <p class="text-sm text-gray-600">Marks: {{ $exam->total_marks }} | Start: {{ $exam->start_time->format('M d, Y g:i A') }} | End: {{ $exam->end_time->format('M d, Y g:i A') }}</p>
                                @if(now() < $exam->start_time)
                                    <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 mt-2 rounded text-xs font-semibold">Upcoming</span>
                                @elseif(now() > $exam->end_time)
                                    <span class="inline-block bg-gray-200 text-gray-800 px-2 py-1 mt-2 rounded text-xs font-semibold">Ended</span>
                                @else
                                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 mt-2 rounded text-xs font-semibold">Active Now</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
