<?php

namespace App\Http\Controllers;

use App\Models\HistoriqueVente;

class HistoriqueVenteController extends Controller
{
    public function historique()
    {
        $ventes = HistoriqueVente::all();
        return view('historique_vente', ['ventes' => $ventes]);
    }
}
