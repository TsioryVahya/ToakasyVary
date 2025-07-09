<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Premiere extends Model
{
    protected $table = 'vue_historique_matiere_premiere';
    
    // Pas de timestamps pour une vue
    public $timestamps = false;
    
    // Colonnes accessibles
    protected $fillable = [
        'id',
        'nom',
        'qtte',
        'date_mouvement'
    ];
    
    protected $casts = [
        'qtte' => 'integer',
        'date_mouvement' => 'datetime'
    ];

    /**
     * Récupère tout l'historique des matières premières
     */
    public static function getAll()
    {
        return self::orderBy('date_mouvement', 'desc')->get();
    }

    /**
     * Récupère l'historique par ID de matière première
     */
    public static function getById($id)
    {
        return self::where('id', $id)
                   ->orderBy('date_mouvement', 'desc')
                   ->get();
    }

    /**
     * Récupère l'historique d'une matière première pour une période
     */
    public static function getByIdAndPeriod($id, $dateDebut, $dateFin)
    {
        return self::where('id', $id)
                   ->whereBetween('date_mouvement', [$dateDebut, $dateFin])
                   ->orderBy('date_mouvement', 'desc')
                   ->get();
    }

    /**
     * Récupère les mouvements d'entrée (quantité positive)
     */
    public static function getEntrees()
    {
        return self::where('qtte', '>', 0)
                   ->orderBy('date_mouvement', 'desc')
                   ->get();
    }

    /**
     * Récupère les mouvements de sortie (quantité négative)
     */
    public static function getSorties()
    {
        return self::where('qtte', '<', 0)
                   ->orderBy('date_mouvement', 'desc')
                   ->get();
    }

    /**
     * Récupère l'historique pour une période donnée
     */
    public static function getByPeriod($dateDebut, $dateFin)
    {
        return self::whereBetween('date_mouvement', [$dateDebut, $dateFin])
                   ->orderBy('date_mouvement', 'desc')
                   ->get();
    }
}