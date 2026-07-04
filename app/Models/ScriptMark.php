<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScriptMark extends Model
{
    protected $fillable = ['script_id', 'question_id', 'marks_obtained'];

    public function script()
    {
        return $this->belongsTo(Script::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
