<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    protected $guarded = ['id', 'created_at'];
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
    public function correctAnswer()
    {
        return $this->options()->where('is_correct', true)->first()?->text;
    }
}
