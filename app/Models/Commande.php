<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $table = "Commande";
    
    protected $fillable = ['id_client', 'date_commande', 'date_livraison', 'id_statut_commande', 'total'];

    public function client() {
        return $this->belongsTo(Client::class, 'id_client');
    }

    public function lignes() {
        return $this->hasMany(LigneCommande::class, 'id_commande');
    }
}