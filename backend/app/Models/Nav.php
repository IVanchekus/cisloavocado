<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nav extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'component'
    ];

    public $timestamps = false;

    public function getLabelAttribute($value)
    {
        return json_decode($value, true);
    }
}
