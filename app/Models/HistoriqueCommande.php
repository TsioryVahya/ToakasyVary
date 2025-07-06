<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoriqueCommande extends Model
{
    protected $table = 'historique_commandes';
    protected $fillable = [
        'id_commande',
        'id_status_commande',
        'date_hist'
    ];
}
