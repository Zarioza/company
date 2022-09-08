<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'superior_id', 'position_id', 'name', 'start_date', 'end_date',
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }
}
