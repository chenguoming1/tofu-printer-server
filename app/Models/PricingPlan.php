<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at', 'id', 'version', 'is_in_use'];

    protected $casts = [
        'variants' => 'array',
    ];

    protected $hidden = [
        'is_in_use', 'version',
    ];

    protected static function boot() {
        parent::boot();
    
        static::creating(function ($printer) {
            $printer->version = 1;
        });
    }
}
