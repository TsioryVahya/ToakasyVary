<?php
namespace App\Http\Controllers;

use App\Models\LotProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function showHistogram()
    {
        $gammes = LotProduction::distinct('id_gamme')->pluck('id_gamme');
        return view('production.histogram', compact('gammes'));
    }

    public function filterHistogram(Request $request)
    {
        \Log::info('Requête AJAX reçue', [
            'donnees_recues' => $request->all(),
            'ip' => $request->ip(),
            'url' => $request->fullUrl()
        ]);
    
        $request->validate([
            'id_gamme' => 'nullable|integer|in:1,2,3', // Rend le filtre optionnel
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut'
        ]);
        
        \Log::debug('Données validées', $request->only(['id_gamme', 'date_debut', 'date_fin']));
    
        // Construction de la requête de base
        $query = DB::table('lot_productions as lp')
            ->join('type_bouteilles as tb', 'lp.id_bouteille', '=', 'tb.id')
            ->select(
                DB::raw('DATE(lp.date_mise_en_bouteille) as date'),
                DB::raw('SUM(CASE WHEN lp.id_gamme = 1 THEN tb.capacite * lp.nombre_bouteilles ELSE 0 END) as litre_gamme1'),
                DB::raw('SUM(CASE WHEN lp.id_gamme = 2 THEN tb.capacite * lp.nombre_bouteilles ELSE 0 END) as litre_gamme2'),
                DB::raw('SUM(CASE WHEN lp.id_gamme = 3 THEN tb.capacite * lp.nombre_bouteilles ELSE 0 END) as litre_gamme3'),
                DB::raw('SUM(lp.nombre_bouteilles) as nombre_bouteilles')
            )
            ->whereBetween('lp.date_mise_en_bouteille', [$request->date_debut, $request->date_fin]);
    
        // Ajout du filtre par gamme si spécifié
        if ($request->has('id_gamme') && $request->id_gamme) {
            $query->where('lp.id_gamme', $request->id_gamme);
        }
    
        // Exécution de la requête finale
        $results = $query->groupBy(DB::raw('DATE(lp.date_mise_en_bouteille)'))
            ->orderBy('date')
            ->get();
    
        return response()->json([
            'dates' => $results->pluck('date'),
            'litreData' => [
                'gamme1' => $results->pluck('litre_gamme1'),
                'gamme2' => $results->pluck('litre_gamme2'),
                'gamme3' => $results->pluck('litre_gamme3')
            ],
            'bouteilleData' => $results->pluck('nombre_bouteilles'),
            'selected_gamme' => $request->id_gamme
        ]);
    }}