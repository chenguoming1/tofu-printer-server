<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at', 'id'];

    protected static function boot() {
        parent::boot();
    
        static::creating(function ($printer) {
            $printer->name = Str::slug(microtime());
        });
    }
}
