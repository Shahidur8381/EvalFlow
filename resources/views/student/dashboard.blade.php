<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Available Exams</h3>
                @if($exams->isEmpty())
                    <p class="text-gray-500">No exams available at the moment.</p>
                @else
                    <div class="space-y-4">
                        @foreach($exams as $exam)
                            <div class="border p-4 rounded-lg shadow-sm">
                                <h4 class="font-bold text-md">{{ $exam->title }} <span class="text-sm font-normal text-gray-500">({{ $exam->course->code ?? 'No Course' }})</span></h4>
                                <p class="text-sm text-gray-600">Marks: {{ $exam->total_marks }} | Window: {{ $exam->start_time->format('M d, g:i A') }} to {{ $exam->end_time->format('M d, g:i A') }}</p>
                                
                                <div class="mt-4">
                                    @if(in_array($exam->id, $submittedScriptExamIds))
                                        <span class="inline-block bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Submitted</span>
                                    @elseif(now() < $exam->start_time)
                                        <span class="inline-block bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">Starts soon</span>
                                    @elseif(now() > $exam->end_time)
                                        <span class="inline-block bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">Ended</span>
                                    @else
                                        <form action="{{ route('student.scripts.upload', $exam) }}" method="POST" enctype="multipart/form-data" class="flex items-center space-x-4">
                                            @csrf
                                            <input type="file" name="answer_script" accept=".pdf" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                                            <x-primary-button>Upload PDF</x-primary-button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
