<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementCommande extends Model
{    
    protected $table = 'paiement_commandes';

    protected $fillable = [
        'id_commande',
        'montant',
        'date_paiement'
    ];

    protected $casts = [
        'date_paiement' => 'date',
    ];

    public function commande()
    {
        return $this->belongsTo(Commande::class, 'id_commande');
    }
}