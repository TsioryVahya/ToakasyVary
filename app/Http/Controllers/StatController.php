<?php

namespace App\Http\Controllers;

use App\Models\Stat;
use App\Models\StatsComplete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatController extends Controller
{
    public function statForm()
    {
        return view('stat_vente');
    }
    public function stat_vente(Request $request)
    {
        $values = $request->validate([
            'date_debut' => ['required', 'date'],
            'date_fin' => ['required', 'date'],
        ]);
        $stats =  StatsComplete::select(
            'nom',
            'cout_matiere_unitaire',
            DB::raw('SUM(montant) AS total_montant'),
            DB::raw('SUM(bouteille_vendue) AS total_bouteille'),
            DB::raw('SUM(depense_total) AS total_depense'),
            DB::raw('SUM(marge) AS total_marge')
        )->where('date_vente', '>=', $values['date_debut'])->where('date_vente', '<=', $values['date_fin'])->groupBy(['nom', 'cout_matiere_unitaire'])->get();
        return view('stat_view', ['stats' => $stats]);
    }
}
