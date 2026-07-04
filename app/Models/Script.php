<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Script extends Model
{
    protected $fillable = ['exam_id', 'student_id', 'file_path', 'status', 'marks_obtained'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function scriptMarks()
    {
        return $this->hasMany(ScriptMark::class);
    }

    /**
     * Get the mark for a specific question on this script.
     */
    public function markForQuestion(int $questionId): ?int
    {
        return $this->scriptMarks->where('question_id', $questionId)->first()?->marks_obtained;
    }
}
