<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     protected $guarded = ['id', 'created_at'];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

}
