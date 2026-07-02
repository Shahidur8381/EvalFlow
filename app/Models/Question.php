<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['exam_id', 'body', 'marks', 'order'];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
