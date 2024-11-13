<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'reports_to'
    ];

    public function reportsTo()
    {
        return $this->belongsTo(Position::class, 'reports_to');
    }

}
