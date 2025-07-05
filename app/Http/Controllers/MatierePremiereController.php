<?php

namespace App\Http\Controllers;

use App\Models\MatierePremiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MatierePremiereController extends Controller
{
    /**
     * Display a listing of raw materials.
     */
    public function index()
    {
        $matieres = MatierePremiere::all();
        return view('matieres.index', compact('matieres'));
    }

    /**
     * Show the form for creating a new raw material.
     */
    public function create()
    {
        return view('matieres.create');
    }

    /**
     * Store a newly created raw material in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:matiere_premieres,nom',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('matieres.create')
                             ->withErrors($validator)
                             ->withInput();
        }

        MatierePremiere::create($request->only(['nom', 'description']));
        return redirect()->route('matieres.index')->with('success', 'Matière première créée avec succès.');
    }

    /**
     * Show the form for editing the specified raw material.
     */
    public function edit(MatierePremiere $matiere)
    {
        return view('matieres.edit', compact('matiere'));
    }

    /**
     * Update the specified raw material in storage.
     */
    public function update(Request $request, MatierePremiere $matiere)
    {
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255|unique:matiere_premieres,nom,' . $matiere->id,
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('matieres.edit', $matiere)
                             ->withErrors($validator)
                             ->withInput();
        }

        $matiere->update($request->only(['nom', 'description']));
        return redirect()->route('matieres.index')->with('success', 'Matière première mise à jour avec succès.');
    }

    /**
     * Remove the specified raw material from storage.
     */
    public function destroy(MatierePremiere $matiere)
    {
        // Check if there are related stock movements
        if ($matiere->mouvements()->exists()) {
            return redirect()->route('matieres.index')->with('error', 'Impossible de supprimer : des mouvements de stock sont associés.');
        }

        $matiere->delete();
        return redirect()->route('matieres.index')->with('success', 'Matière première supprimée avec succès.');
    }
}