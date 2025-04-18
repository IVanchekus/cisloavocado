<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'solution',
        'img_url',
        'video_url',
        'user_id',
        'category_exercise_id',
        'level_exercise_id',
        'subject_id',
        'is_ai',
        'is_public',
        'is_approved'
    ];

    protected $casts = [
        'is_ai' => 'boolean',
        'is_public' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(CategoryExercise::class, 'category_exercise_id');
    }

    public function level()
    {
        return $this->belongsTo(LevelExercise::class, 'level_exercise_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
