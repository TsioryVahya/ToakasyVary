<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GammeMatiere extends Model {
    protected $fillable = ['id_gamme', 'id_matiere', 'quantite'];

    public function matiere() {
        return $this->belongsTo(MatierePremiere::class, 'id_matiere');
    }
}