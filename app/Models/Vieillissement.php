<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vieillissement extends Model
{
    protected $table = 'vieillissement';
    
    public $timestamps = false;
    
    public $incrementing = false;
    
    protected $primaryKey = null;
    
    protected $fillable = [];
    
    protected static function boot()
    {
        parent::boot();
        
        static::saving(fn() => false);
        static::creating(fn() => false);
        static::updating(fn() => false);
        static::deleting(fn() => false);
    }
    
    public function lotProduction()
    {
        return $this->belongsTo(LotProduction::class, 'lot_id');
    }
    
    public function gamme()
    {
        return $this->belongsTo(Gamme::class, 'id_gamme');
    }
    
    public function typeBouteille()
    {
        return $this->belongsTo(TypeBouteille::class, 'id_bouteille');
    }
}