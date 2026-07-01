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
        </div>
    </div>
</x-app-layout>
