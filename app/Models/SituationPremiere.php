<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SituationPremiere extends Model
{
    protected $table = 'vue_situation_matiere_premiere';

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
     * Récupère toutes les situations des matières premières
     */
    public static function getAll()
    {
        return self::orderBy('nom', 'asc')->get();
    }

    /**
     * Récupère une situation par ID de matière première
     */
    public static function getById($id)
    {
        return self::where('id', $id)->first();
    }
    /**
     * Récupère les situations avec stock positif uniquement
     */
    public static function getWithStock()
    {
        return self::where('qtte', '>', 0)
                   ->orderBy('nom', 'asc')
                   ->get();
    }

    /**
     * Récupère les situations avec stock négatif ou nul
     */
    public static function getWithoutStock()
    {
        return self::where('qtte', '<=', 0)
                   ->orderBy('nom', 'asc')
                   ->get();
    }
}