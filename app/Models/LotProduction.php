<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotProduction extends Model
{
    protected $table = 'lot_productions'; // Note: Your table name appears to have a typo ('productions' vs 'productions')
    
    protected $fillable = [
        'id_bouteille', 
        'id_gamme', 
        'date_mise_en_bouteille', 
        'nombre_bouteilles'
    ];

    public function typeBouteille()
    {
        return $this->belongsTo(TypeBouteille::class, 'id_bouteille');
    }
}