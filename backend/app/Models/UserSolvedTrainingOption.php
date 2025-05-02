<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSolvedTrainingOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'training_option_id',
        'started_at',
        'finished_at',
        'is_solved',
        'mark',
        'verifier_id',
        'verifier_comment',
    ];
    protected $casts = [
        'user_id' => 'integer',
        'training_option_id' => 'integer',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'is_solved' => 'boolean',
        'verifier_id' => 'integer',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function trainingOption()
    {
        return $this->belongsTo(TrainingOption::class);
    }
    public function verifier()
    {
        return $this->belongsTo(User::class, 'verifier_id');
    }
}
