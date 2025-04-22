<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'with_solution',
        'deadline',
        'user_id',
    ];
    protected $casts = [
        'with_solution' => 'boolean',
        'deadline' => 'datetime',
    ];

    public function getNameAttribute($value)
    {
        return json_decode($value);
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'exercise_in_training_options');
    }
}
