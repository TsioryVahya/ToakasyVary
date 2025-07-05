<?php

namespace App\Http\Controllers;

use App\Models\Vieillissement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function calendar()
    {
        return view('production.calendar');
    }

    public function getCalendarData()
    {
        try {
            $lots = Vieillissement::all();
            $events = [];

            foreach ($lots as $lot) {
                $dateDebut = Carbon::parse($lot->date_debut);

                // 1. Début de production
                $events[] = $this->createEvent(
                    $dateDebut->format('Y-m-d'),
                    'production_start',
                    'bg-gray-500',
                    'Début production',
                    $lot
                );

                // 2. Période de fermentation
                if ($lot->fermentation_jours > 0) {
                    // Début fermentation (même jour que début production)
                    $events[] = $this->createEvent(
                        $dateDebut->format('Y-m-d'),
                        'fermentation_start',
                        'bg-blue-300',
                        'Début fermentation',
                        $lot
                    );

                    // Fin fermentation = Mise en bouteille
                    $finFermentation = $dateDebut->copy()->addDays($lot->fermentation_jours);
                    $events[] = $this->createEvent(
                        $finFermentation->format('Y-m-d'),
                        'fermentation_end',
                        'bg-blue-500',
                        'Fin fermentation/Mise en bouteille',
                        $lot
                    );
                }

                // 3. Période de vieillissement
                if ($lot->vieillissement_jours > 0) {
                    // Début vieillissement (jour après fin fermentation)
                    $debutVieillissement = $dateDebut->copy()->addDays($lot->fermentation_jours + 1);
                    $events[] = $this->createEvent(
                        $debutVieillissement->format('Y-m-d'),
                        'aging_start',
                        'bg-orange-300',
                        'Début vieillissement',
                        $lot
                    );

                    // Fin vieillissement = Commercialisation
                    $finVieillissement = $debutVieillissement->copy()->addDays($lot->vieillissement_jours);
                    $events[] = $this->createEvent(
                        $finVieillissement->format('Y-m-d'),
                        'aging_end',
                        'bg-green-500',
                        'Fin vieillissement/Commercialisation',
                        $lot
                    );
                }
            }

            return response()
                ->json($events)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'GET');

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la génération du calendrier',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    private function createEvent($date, $type, $color, $label, $lot)
    {
        $event = [
            'date' => $date,
            'type' => $type,
            'couleur' => $color,
            'nom' => "$label - Lot #{$lot->lot_id}",
            'lot_id' => $lot->lot_id,
            'gamme' => $lot->nom_gamme,
            'full_date' => Carbon::parse($date)->isoFormat('dddd D MMMM YYYY'),
            'is_start_event' => in_array($type, ['production_start', 'fermentation_start', 'aging_start']),
            'is_end_event' => in_array($type, ['fermentation_end', 'aging_end'])
        ];

        return $event;
    }
}
