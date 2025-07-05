<?php

namespace App\Http\Controllers;

use App\Models\LotProduction;
use App\Models\Gamme;
use App\Models\TypeBouteille;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LotProductionController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');
        $query = LotProduction::with(['gamme', 'typeBouteille']);
        
        // Appliquer les filtres selon le statut
        switch ($filter) {
            case 'fermentation':
                $query->whereNull('date_mise_en_bouteille')
                      ->orWhere('date_mise_en_bouteille', '>', Carbon::now());
                break;
            case 'vieillissement':
                $query->where('date_mise_en_bouteille', '<=', Carbon::now())
                      ->where(function($q) {
                          $q->whereNull('date_commercialisation')
                            ->orWhere('date_commercialisation', '>', Carbon::now());
                      });
                break;
            case 'commercialise':
                $query->where('date_commercialisation', '<=', Carbon::now());
                break;
            case 'all':
            default:
                // Pas de filtre, afficher tous
                break;
        }
        
        $lots = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Compter les lots par statut pour les badges
        $counts = $this->getStatusCounts();
        
        $gammes = Gamme::all();
        $typeBouteilles = TypeBouteille::all();
        
        return view('production.production', compact('lots', 'gammes', 'typeBouteilles', 'filter', 'counts'));
    }

    private function getStatusCounts()
    {
        $now = Carbon::now();
        
        return [
            'all' => LotProduction::count(),
            'fermentation' => LotProduction::where(function($q) use ($now) {
                $q->whereNull('date_mise_en_bouteille')
                  ->orWhere('date_mise_en_bouteille', '>', $now);
            })->count(),
            'vieillissement' => LotProduction::where('date_mise_en_bouteille', '<=', $now)
                ->where(function($q) use ($now) {
                    $q->whereNull('date_commercialisation')
                      ->orWhere('date_commercialisation', '>', $now);
                })->count(),
            'commercialise' => LotProduction::where('date_commercialisation', '<=', $now)->count(),
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_gamme' => 'required|exists:gammes,id',
            'id_bouteille' => 'required|exists:type_bouteilles,id',
            'date_debut' => 'required|date',
            'nombre_bouteilles' => 'nullable|integer|min:1'
        ]);

        // Récupérer la gamme pour calculer les dates
        $gamme = Gamme::findOrFail($request->id_gamme);
        
        // Calculer les dates automatiquement
        $dateDebut = Carbon::parse($request->date_debut);
        $dateMiseEnBouteille = $dateDebut->copy()->addDays($gamme->fermentation_jours);
        $dateCommercialisation = $dateMiseEnBouteille->copy()->addDays($gamme->vieillissement_jours);

        LotProduction::create([
            'id_gamme' => $request->id_gamme,
            'id_bouteille' => $request->id_bouteille,
            'date_debut' => $dateDebut->format('Y-m-d'),
            'date_mise_en_bouteille' => $dateMiseEnBouteille->format('Y-m-d'),
            'date_commercialisation' => $dateCommercialisation->format('Y-m-d'),
            'nombre_bouteilles' => $request->nombre_bouteilles
        ]);

        return redirect()->route('lot_productions.index')
            ->with('success', 'Lot de production créé avec succès.');
    }

    public function update(Request $request, LotProduction $lotProduction)
    {
        $request->validate([
            'id_gamme' => 'required|exists:gammes,id',
            'id_bouteille' => 'required|exists:type_bouteilles,id',
            'date_debut' => 'required|date',
            'nombre_bouteilles' => 'nullable|integer|min:1'
        ]);

        // Récupérer la gamme pour calculer les dates
        $gamme = Gamme::findOrFail($request->id_gamme);
        
        // Calculer les dates automatiquement
        $dateDebut = Carbon::parse($request->date_debut);
        $dateMiseEnBouteille = $dateDebut->copy()->addDays($gamme->fermentation_jours);
        $dateCommercialisation = $dateMiseEnBouteille->copy()->addDays($gamme->vieillissement_jours);

        $lotProduction->update([
            'id_gamme' => $request->id_gamme,
            'id_bouteille' => $request->id_bouteille,
            'date_debut' => $dateDebut->format('Y-m-d'),
            'date_mise_en_bouteille' => $dateMiseEnBouteille->format('Y-m-d'),
            'date_commercialisation' => $dateCommercialisation->format('Y-m-d'),
            'nombre_bouteilles' => $request->nombre_bouteilles
        ]);

        return redirect()->route('lot_productions.index')
            ->with('success', 'Lot de production mis à jour avec succès.');
    }

    public function show(LotProduction $lotProduction)
    {
        $lotProduction->load(['gamme', 'typeBouteille']);
        return view('production.show', compact('lotProduction'));
    }

    public function edit(LotProduction $lotProduction)
    {
        $gammes = Gamme::all();
        $typeBouteilles = TypeBouteille::all();
        return view('production.edit', compact('lotProduction', 'gammes', 'typeBouteilles'));
    }

    public function destroy(LotProduction $lotProduction)
    {
        $lotProduction->delete();
        return redirect()->route('lot_productions.index')
            ->with('success', 'Lot de production supprimé avec succès.');
    }

    public function getLotData(LotProduction $lotProduction)
    {
        return response()->json([
            'id' => $lotProduction->id,
            'id_gamme' => $lotProduction->id_gamme,
            'id_bouteille' => $lotProduction->id_bouteille,
            'date_debut' => $lotProduction->date_debut,
            'nombre_bouteilles' => $lotProduction->nombre_bouteilles,
            'date_mise_en_bouteille' => $lotProduction->date_mise_en_bouteille,
            'date_commercialisation' => $lotProduction->date_commercialisation,
        ]);
    }
}