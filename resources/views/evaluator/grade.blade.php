<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Grading Script: ') }} {{ $script->student->name }}
            </h2>
            <a href="{{ route('evaluator.dashboard') }}" class="text-sm text-gray-600 hover:text-gray-900">&larr; Back to Dashboard</a>
        </div>
    </x-slot>

    <div class="py-12 h-screen max-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-[80vh] flex flex-col md:flex-row">
                <!-- PDF Viewer Area (Left Side) -->
                <div class="w-full md:w-3/4 h-full border-r p-4 bg-gray-100">
                    <iframe src="{{ asset('storage/' . $script->file_path) }}" class="w-full h-full border-0 rounded" title="Student Answer Script"></iframe>
                </div>

                <!-- Grading Area (Right Side) -->
                <div class="w-full md:w-1/4 h-full p-6 bg-white overflow-y-auto">
                    <h3 class="text-lg font-bold mb-2">{{ $script->exam->title }}</h3>
                    <p class="text-sm text-gray-600 mb-6">Course: {{ $script->exam->course->name ?? 'N/A' }}</p>

                    <form action="{{ route('evaluator.scripts.storeMarks', $script) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="marks_obtained" :value="__('Assign Marks (out of ' . $script->exam->total_marks . ')')" />
                            <x-text-input id="marks_obtained" name="marks_obtained" type="number" step="0.5" min="0" max="{{ $script->exam->total_marks }}" class="mt-1 block w-full text-lg text-center" required value="{{ old('marks_obtained', $script->marks_obtained) }}" />
                        </div>

                        <div class="pt-4">
                            <x-primary-button class="w-full justify-center text-lg py-3">
                                {{ __('Save & Complete Evaluation') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
