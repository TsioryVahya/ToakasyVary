<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DebutProductionLot extends Model
{
    protected $table = 'vue_debut_production_lot';
    
    // Pas de timestamps pour une vue
    public $timestamps = false;
    
    // Colonnes accessibles
    protected $fillable = [
        'id_lot',
        'id_gamme',
        'nom_gamme',
        'date_debut',
        'nombre_bouteilles',
        'id_matiere',
        'nom_matiere',
        'quantite_unitaire',
        'quantite_totale'
    ];
    
    protected $casts = [
        'id_lot' => 'integer',
        'id_gamme' => 'integer',
        'date_debut' => 'date',
        'nombre_bouteilles' => 'integer',
        'id_matiere' => 'integer',
        'quantite_unitaire' => 'decimal:2',
        'quantite_totale' => 'decimal:2'
    ];

    /**
     * Récupère toutes les données de début de production
     */
    public static function getAll()
    {
        return self::orderBy('date_debut', 'desc')
                   ->orderBy('id_lot', 'asc')
                   ->get();
    }

    /**
     * Récupère les données par ID de lot
     */
    public static function getByLotId($idLot)
    {
        return self::where('id_lot', $idLot)
                   ->orderBy('nom_matiere', 'asc')
                   ->get();
    }

    /**
     * Récupère les données par ID de gamme
     */
    public static function getByGammeId($idGamme)
    {
        return self::where('id_gamme', $idGamme)
                   ->orderBy('date_debut', 'desc')
                   ->orderBy('id_lot', 'asc')
                   ->get();
    }

    /**
     * Récupère les données pour une matière première spécifique
     */
    public static function getByMatiereId($idMatiere)
    {
        return self::where('id_matiere', $idMatiere)
                   ->orderBy('date_debut', 'desc')
                   ->orderBy('id_lot', 'asc')
                   ->get();
    }

    /**
     * Récupère les données pour une période donnée
     */
    public static function getByPeriod($dateDebut, $dateFin)
    {
        return self::whereBetween('date_debut', [$dateDebut, $dateFin])
                   ->orderBy('date_debut', 'desc')
                   ->orderBy('id_lot', 'asc')
                   ->get();
    }

    /**
     * Récupère la quantité totale de matières premières par lot
     */
    public static function getTotalQuantiteByLot($idLot)
    {
        return self::where('id_lot', $idLot)
                   ->sum('quantite_totale');
    }

    /**
     * Récupère les lots nécessitant une matière première spécifique
     */
    public static function getLotsRequiringMatiere($idMatiere, $quantiteDisponible)
    {
        return self::where('id_matiere', $idMatiere)
                   ->where('quantite_totale', '<=', $quantiteDisponible)
                   ->orderBy('date_debut', 'asc')
                   ->get();
    }

    /**
     * Groupe les matières premières par lot
     */
    public static function getGroupedByLot()
    {
        return self::orderBy('date_debut', 'desc')
                   ->orderBy('id_lot', 'asc')
                   ->orderBy('nom_matiere', 'asc')
                   ->get()
                   ->groupBy('id_lot');
    }
}
