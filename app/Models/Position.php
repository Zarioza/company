<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    public const POSITION_REGULAR = 'regular';
    public const POSITION_MANAGEMENT = 'management';

    protected $fillable = [
        'name', 'start_date', 'end_date',
    ];
}
