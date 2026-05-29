<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    protected $fillable = [
        'user_id','title','genre','year','rating','description',
        'poster','director','cast','duration','language','status','is_favorite',
    ];

    protected $casts = ['is_favorite' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
