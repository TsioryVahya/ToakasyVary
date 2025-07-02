<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gamme extends Model {
    protected $fillable = ['nom', 'description', 'fermentation_jours', 'vieillissement_jours'];

    public function lots() {
        return $this->hasMany(LotProduction::class, 'id_gamme');
    }

    public function matieres() {
        return $this->hasMany(GammeMatiere::class, 'id_gamme');
    }
}
