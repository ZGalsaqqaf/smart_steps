<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $guarded = ['id', 'created_at'];
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }
    
    public function totalPoints()
    {
        return $this->attempts()->sum('earned_points');
    }
}
