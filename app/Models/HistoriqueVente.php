<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriqueVente extends Model
{
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = null;
    protected static function boot()
    {
        parent::boot();
        static::saving(fn() => false);
        static::creating(fn() => false);
        static::updating(fn() => false);
        static::deleting(fn() => false);
    }
}
