<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $guarded=['id', 'created_at'];
    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

}
