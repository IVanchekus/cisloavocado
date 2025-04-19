<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSolvedExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exercise_id',
        'answer',
        'is_correct',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
}
