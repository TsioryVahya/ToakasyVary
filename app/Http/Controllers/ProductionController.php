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
        $request->validate([
            'id_gamme' => 'nullable|integer|in:1,2,3',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut'
        ]);

        $query = DB::table('lot_productions as lp')
            ->join('type_bouteilles as tb', 'lp.id_bouteille', '=', 'tb.id')
            ->join('gammes as g', 'lp.id_gamme', '=', 'g.id') // Jointure avec la table gammes
            ->select(
                DB::raw('DATE(DATE_ADD(lp.date_debut, INTERVAL g.fermentation_jours DAY)) as date'),
                DB::raw('SUM(CASE WHEN lp.id_gamme = 1 THEN tb.capacite * lp.nombre_bouteilles ELSE 0 END) as litre_gamme1'),
                DB::raw('SUM(CASE WHEN lp.id_gamme = 2 THEN tb.capacite * lp.nombre_bouteilles ELSE 0 END) as litre_gamme2'),
                DB::raw('SUM(CASE WHEN lp.id_gamme = 3 THEN tb.capacite * lp.nombre_bouteilles ELSE 0 END) as litre_gamme3'),
                DB::raw('SUM(lp.nombre_bouteilles) as nombre_bouteilles')
            )
            ->whereBetween(
                DB::raw('DATE(DATE_ADD(lp.date_debut, INTERVAL g.fermentation_jours DAY))'),
                [$request->date_debut, $request->date_fin]
            );

        if ($request->has('id_gamme') && $request->id_gamme) {
            $query->where('lp.id_gamme', $request->id_gamme);
        }

        $results = $query->groupBy(DB::raw('DATE(DATE_ADD(lp.date_debut, INTERVAL g.fermentation_jours DAY))'))
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
