<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseInTrainingOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercise_id',
        'training_option_id',
    ];
    protected $casts = [
        'exercise_id' => 'integer',
        'training_option_id' => 'integer',
    ];
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
    public function trainingOption()
    {
        return $this->belongsTo(TrainingOption::class);
    }
    public function userSolvedTrainingOption()
    {
        return $this->hasMany(UserSolvedTrainingOption::class);
    }
}
