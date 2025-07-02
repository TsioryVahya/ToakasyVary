<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MouvementStockMatierePremiere extends Model {
    protected $fillable = ['id_matiere', 'id_type_mouvement', 'id_detail_mouvement', 'quantite', 'date_mouvement'];
}
