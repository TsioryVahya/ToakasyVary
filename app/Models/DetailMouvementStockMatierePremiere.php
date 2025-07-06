<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailMouvementStockMatierePremiere extends Model
{
    protected $fillable = ['id_fournisseur', 'id_employe', 'id_lot'];

    /**
     * Get the supplier for the movement detail.
     */
    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class, 'id_fournisseur');
    }

    /**
     * Get the employee for the movement detail.
     */
    public function employe(): BelongsTo
    {
        return $this->belongsTo(Employe::class, 'id_employe');
    }

    /**
     * Get the production lot for the movement detail.
     */
    public function lot(): BelongsTo
    {
        return $this->belongsTo(LotProduction::class, 'id_lot');
    }
}