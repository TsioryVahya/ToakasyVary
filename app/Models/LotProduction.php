<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotProduction extends Model
{
    protected $table = 'lot_productions';
    
    protected $fillable = [
        'id_gamme',
        'id_bouteille', 
        'date_debut',
        'date_mise_en_bouteille',
        'date_commercialisation',
        'nombre_bouteilles'
    ];
    
    protected $casts = [
        'date_debut' => 'date',
        'date_mise_en_bouteille' => 'date',
        'date_commercialisation' => 'date',
    ];

    public function typeBouteille()
    {
        return $this->belongsTo(TypeBouteille::class, 'id_bouteille');
    }
    
    public function gamme()
    {
        return $this->belongsTo(Gamme::class, 'id_gamme');
    }
    
    public function vieillissement()
    {
        return $this->hasOne(Vieillissement::class, 'lot_id');
    }
}