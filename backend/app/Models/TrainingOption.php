<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'hash',
        'name',
        'description',
        'with_solution',
        'deadline',
        'user_id',
        'subject_id',
        'is_public',
        'is_approved',
    ];
    protected $casts = [
        'with_solution' => 'boolean',
        'deadline' => 'datetime',
        'is_public' => 'boolean',
        'is_approved' => 'boolean',
    ];

    protected $appends = [
        'is_solved',
    ];

    public function getNameAttribute($value)
    {
        return json_decode($value);
    }

    public function getDescriptionAttribute($value)
    {
        return json_decode($value);
    }

    public function exercises($isWithSolution = false)
    {
        if ($isWithSolution) {
            return $this->belongsToMany(Exercise::class, 'exercise_in_training_options');    
        }
        return $this->belongsToMany(Exercise::class, 'exercise_in_training_options')
            ->select(["exercises.id", "exercises.question", "img_url"]);
    }

    public function getIsSolvedAttribute()
    {
        return UserSolvedTrainingOption::where([
            'user_id' => auth()->user()->id,
            'training_option_id' => $this->id,
        ])->exists();
    }
}
