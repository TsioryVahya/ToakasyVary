<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementStockMatierePremiere extends Model
{
    protected $fillable = ['id_matiere', 'id_type_mouvement', 'id_detail_mouvement', 'quantite', 'date_mouvement'];

    /**
     * Get the raw material for the stock movement.
     */
    public function matiere(): BelongsTo
    {
        return $this->belongsTo(MatierePremiere::class, 'id_matiere');
    }

    /**
     * Get the movement type for the stock movement.
     */
    public function typeMouvement(): BelongsTo
    {
        return $this->belongsTo(TypeMouvement::class, 'id_type_mouvement');
    }

    /**
     * Get the movement details for the stock movement.
     */
    public function detailMouvement(): BelongsTo
    {
        return $this->belongsTo(DetailMouvementStockMatierePremiere::class, 'id_detail_mouvement');
    }
}