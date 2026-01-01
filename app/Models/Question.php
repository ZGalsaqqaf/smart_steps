<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $guarded=['id', 'created_at'];
    
    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function options()
    {
        return $this->hasMany(Option::class);
    }

    public function attempts()
    {
        return $this->hasMany(Attempt::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
