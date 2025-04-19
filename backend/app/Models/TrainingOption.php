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
}
