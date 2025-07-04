<?php

namespace App\Http\Controllers;

use App\Models\Vieillissement;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function calendar()
    {
        // Récupérer les données de vieillissement
        $vieillissements = Vieillissement::all();
        
        return view('production.calendar', compact('vieillissements'));
    }
    
    public function getVieillissementData()
    {
        $vieillissements = Vieillissement::all();
        $events = [];
        
        foreach ($vieillissements as $v) {
            $dateDebut = new \DateTime($v->date_debut);
            $fermentationFin = clone $dateDebut;
            $fermentationFin->modify('+' . $v->fermentation_jours . ' days');
            
            $vieillissementDebut = clone $fermentationFin;
            $vieillissementDebut->modify('+1 day');
            
            $vieillissementFin = clone $vieillissementDebut;
            $vieillissementFin->modify('+' . $v->vieillissement_jours . ' days');
            
            $currentDate = clone $dateDebut;
            while ($currentDate <= $fermentationFin) {
                $events[] = [
                    'type' => 'fermentation',
                    'date' => $currentDate->format('Y-m-d'),
                    'nom' => "Fermentation - {$v->nom_gamme}",
                    'lot_id' => $v->lot_id,
                    'couleur' => 'bg-blue-500',
                    'day_type' => $currentDate == $dateDebut ? 'debut' : ($currentDate == $fermentationFin ? 'fin' : 'milieu')
                ];
                $currentDate->modify('+1 day');
            }
            
            $currentDate = clone $vieillissementDebut;
            while ($currentDate <= $vieillissementFin) {
                $events[] = [
                    'type' => 'vieillissement',
                    'date' => $currentDate->format('Y-m-d'),
                    'nom' => "Vieillissement - {$v->nom_gamme}",
                    'lot_id' => $v->lot_id,
                    'couleur' => 'bg-orange-500',
                    'day_type' => $currentDate == $vieillissementDebut ? 'debut' : ($currentDate == $vieillissementFin ? 'fin' : 'milieu')
                ];
                $currentDate->modify('+1 day');
            }
        }
        
        return response()->json($events);
    }
}