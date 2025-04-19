<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
    ];

    public function getNameAttribute($value)
    {
        return json_decode($value, true);
    }

    public function getDescriptionAttribute($value)
    {
        return json_decode($value, true);
    }
}
