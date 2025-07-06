<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LigneCommande extends Model {
    protected $fillable = ['id_commande', 'id_lot', 'quantite_bouteilles', 'id_prix'];

    public function lot() {
        return $this->belongsTo(LotProduction::class, 'id_lot');
    }
}