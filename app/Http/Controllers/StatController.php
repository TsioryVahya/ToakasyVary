<?php

namespace App\Http\Controllers;

use App\Models\Stat;
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
        $stats =  Stat::select(
            'nom',
            DB::raw('SUM(montant) as total_montant'),
            DB::raw('SUM(quantite) as total_quantite')
        )->where('vente', '>=', $values['date_debut'])->where('vente', '<=', $values['date_fin'])->groupBy('nom')->get();
        return view('stat_view', ['stats' => $stats]);
    }
}
