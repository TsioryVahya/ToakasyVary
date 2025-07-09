<?php

namespace App\Http\Controllers;

use App\Models\HistoriqueVente;
use Illuminate\Http\Request;

class HistoriqueVenteController extends Controller
{
    public function historique()
    {
        $ventes = HistoriqueVente::all();
        return view('historique_vente', ['ventes' => $ventes]);
    }
    public function exporter(Request $request)
    {
        $venteJson = $request->input('vente');
        $vente = json_decode($venteJson, true);
        return view('exporter', ['vente' => $vente]);
    }
}
