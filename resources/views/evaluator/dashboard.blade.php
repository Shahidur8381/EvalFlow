<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Evaluator Dashboard') }}
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
                <h3 class="text-lg font-bold mb-4">Submitted Scripts</h3>
                @if($scripts->isEmpty())
                    <p class="text-gray-500">No scripts submitted yet.</p>
                @else
                    <div class="space-y-4">
                        @foreach($scripts as $script)
                            <div class="border p-4 rounded-lg shadow-sm flex justify-between items-center bg-gray-50 hover:bg-gray-100 transition-colors">
                                <div>
                                    <h4 class="font-bold text-md">{{ $script->exam->title }} <span class="text-sm font-normal text-gray-500">({{ $script->exam->course->code ?? 'No Course' }})</span></h4>
                                    <p class="text-sm text-gray-600">Student: {{ $script->student->name }} | Submitted: {{ $script->created_at->format('M d, g:i A') }}</p>
                                    <p class="text-sm mt-1">
                                        Status: 
                                        @if($script->status === 'evaluated')
                                            <span class="text-green-600 font-semibold">Evaluated ({{ $script->marks_obtained }} / {{ $script->exam->total_marks }})</span>
                                        @else
                                            <span class="text-yellow-600 font-semibold">Pending Evaluation</span>
                                        @endif
                                    </p>
                                </div>
                                <a href="{{ route('evaluator.scripts.show', $script) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ $script->status === 'evaluated' ? 'View/Edit Grade' : 'Grade Script' }}
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
