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
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        // Вариант считается пройденным только если пользователь завершил его (is_solved = true).
        // Запись может создаваться при первом открытии (started_at), это НЕ должно считаться прохождением.
        return UserSolvedTrainingOption::query()
            ->where('user_id', $user->id)
            ->where('training_option_id', $this->id)
            ->where('is_solved', true)
            ->exists();
    }
}
