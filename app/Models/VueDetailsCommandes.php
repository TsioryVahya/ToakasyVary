<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VueDetailsCommandes extends Model
{
    protected $table = 'vue_details_commandes';
    public $timestamps = false;
    
    // Désactive toutes les opérations d'écriture
    protected static function boot()
    {
        parent::boot();
        static::saving(fn() => false);
        static::creating(fn() => false);
        static::updating(fn() => false);
        static::deleting(fn() => false);
    }
}