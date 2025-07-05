<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gamme extends Model {
    protected $table = 'gammes';
    protected $fillable = [
        'nom',
        'description',
        'fermentation_jours',
        'vieillissement_jours'
    ];

    public function lotProductions()
    {
        return $this->hasMany(LotProduction::class, 'id_gamme');
    }

    public function vieillissements()
    {
        return $this->hasMany(Vieillissement::class, 'id_gamme');
    }

    public function matieres() {
        return $this->hasMany(GammeMatiere::class, 'id_gamme');
    }
}
