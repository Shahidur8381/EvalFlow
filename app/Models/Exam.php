<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['course_id', 'title', 'start_time', 'end_time', 'assigned_evaluator_id'];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime',
            'end_time' => 'datetime',
        ];
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->orderBy('order');
    }

    public function scripts()
    {
        return $this->hasMany(Script::class);
    }

    public function assignedEvaluator()
    {
        return $this->belongsTo(User::class, 'assigned_evaluator_id');
    }

    /**
     * Auto-calculate total marks from questions.
     */
    public function getTotalMarksAttribute(): int
    {
        return $this->questions()->sum('marks');
    }
}
