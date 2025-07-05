<?php

namespace App\Http\Controllers;

use App\Models\MouvementStockMatierePremiere;
use App\Models\DetailMouvementStockMatierePremiere;
use App\Models\MatierePremiere;
use App\Models\Employe;
use App\Models\Fournisseur;
use App\Models\TypeMouvement;
use App\Models\LotProduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MouvementStockMatierePremiereController extends Controller
{
    /**
     * Display a listing of stock movements.
     */
    public function index()
    {
        $mouvements = MouvementStockMatierePremiere::with(['matiere', 'typeMouvement', 'detailMouvement'])->get();
        return view('mouvementsStockMatierePremiere.index', compact('mouvements'));
    }

    /**
     * Show the form for creating a new stock movement.
     */
    public function create()
    {
        $matieres = MatierePremiere::all();
        $employes = Employe::all();
        $fournisseurs = Fournisseur::all();
        $types = TypeMouvement::all();
        $lots = LotProduction::all();
        return view('mouvementsStockMatierePremiere.create', compact('matieres', 'employes', 'fournisseurs', 'types', 'lots'));
    }

    /**
     * Store a newly created stock movement in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_matiere' => 'required|exists:matiere_premieres,id',
            'id_type_mouvement' => 'required|exists:type_mouvements,id',
            'id_employe' => 'nullable|exists:employes,id',
            'id_fournisseur' => 'nullable|exists:fournisseurs,id',
            'id_lot' => 'nullable|exists:lot_productions,id',
            'quantite' => 'required|numeric|min:0',
            'date_mouvement' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('mouvementsStockMatierePremiere.create')
                             ->withErrors($validator)
                             ->withInput();
        }

        DB::beginTransaction();
        try {
            $detail = DetailMouvementStockMatierePremiere::create([
                'id_fournisseur' => $request->id_fournisseur,
                'id_employe' => $request->id_employe,
                'id_lot' => $request->id_lot,
            ]);

            MouvementStockMatierePremiere::create([
                'id_matiere' => $request->id_matiere,
                'id_type_mouvement' => $request->id_type_mouvement,
                'id_detail_mouvement' => $detail->id,
                'quantite' => $request->quantite,
                'date_mouvement' => $request->date_mouvement,
            ]);

            DB::commit();
            return redirect()->route('mouvementsStockMatierePremiere.index')->with('success', 'Mouvement de stock créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('mouvementsStockMatierePremiere.create')->with('error', 'Erreur lors de la création du mouvement.');
        }
    }

    /**
     * Show the form for editing the specified stock movement.
     */
    public function edit(MouvementStockMatierePremiere $mouvement)
    {
        $matieres = MatierePremiere::all();
        $employes = Employe::all();
        $fournisseurs = Fournisseur::all();
        $types = TypeMouvement::all();
        $lots = LotProduction::all();
        return view('mouvementsStockMatierePremiere.edit', compact('mouvement', 'matieres', 'employes', 'fournisseurs', 'types', 'lots'));
    }

    /**
     * Update the specified stock movement in storage.
     */
    public function update(Request $request, MouvementStockMatierePremiere $mouvement)
    {
        $validator = Validator::make($request->all(), [
            'id_matiere' => 'required|exists:matiere_premieres,id',
            'id_type_mouvement' => 'required|exists:type_mouvements,id',
            'id_employe' => 'nullable|exists:employes,id',
            'id_fournisseur' => 'nullable|exists:fournisseurs,id',
            'id_lot' => 'nullable|exists:lot_productions,id',
            'quantite' => 'required|numeric|min:0',
            'date_mouvement' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('mouvementsStockMatierePremiere.edit', $mouvement)
                             ->withErrors($validator)
                             ->withInput();
        }

        DB::beginTransaction();
        try {
            $mouvement->detailMouvement->update([
                'id_fournisseur' => $request->id_fournisseur,
                'id_employe' => $request->id_employe,
                'id_lot' => $request->id_lot,
            ]);

            $mouvement->update([
                'id_matiere' => $request->id_matiere,
                'id_type_mouvement' => $request->id_type_mouvement,
                'quantite' => $request->quantite,
                'date_mouvement' => $request->date_mouvement,
            ]);

            DB::commit();
            return redirect()->route('mouvementsStockMatierePremiere.index')->with('success', 'Mouvement de stock mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('mouvementsStockMatierePremiere.edit', $mouvement)->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    /**
     * Remove the specified stock movement from storage.
     */
    public function destroy(MouvementStockMatierePremiere $mouvement)
    {
        // The cascade delete on id_detail_mouvement will handle the detail deletion
        $mouvement->delete();
        return redirect()->route('mouvementsStockMatierePremiere.index')->with('success', 'Mouvement de stock supprimé avec succès.');
    }
}