<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotProduction extends Model {
    protected $fillable = ['id_gamme', 'id_bouteille', 'date_debut', 'date_mise_en_bouteille', 'date_commercialisation', 'nombre_bouteilles'];

    public function gamme() {
        return $this->belongsTo(Gamme::class, 'id_gamme');
    }
}