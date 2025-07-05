<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MatierePremiere extends Model
{
    protected $fillable = [
        'nom',
        'description',
    ];

    public function mouvements(): HasMany
    {
        return $this->hasMany(MouvementStockMatierePremiere::class, 'id_matiere');
    }
}
