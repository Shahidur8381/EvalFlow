<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Exam;

class AdminExamController extends Controller
{
    public function dashboard()
    {
        $courses = Course::all();
        $exams = Exam::with('course')->get();
        return view('admin.dashboard', compact('courses', 'exams'));
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:courses,code',
        ]);

        Course::create($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Course created successfully!');
    }

    public function storeExam(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'total_marks' => 'required|integer|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        Exam::create($request->all());
        return redirect()->route('admin.dashboard')->with('success', 'Exam created successfully!');
    }
}
