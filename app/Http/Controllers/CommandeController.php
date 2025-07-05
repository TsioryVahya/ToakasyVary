<?php

namespace App\Http\Controllers;

use App\Models\Gamme;
use App\Models\LotProduction;
use App\Models\GammeMatiere;
use App\Models\TypeBouteille;
use App\Models\MouvementStockMatierePremiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CommandeController extends Controller
{
    public function previewForm()
    {
        $gammes = Gamme::all();
        $typesBouteilles = TypeBouteille::all();
        return view('commandes.preview', compact('gammes', 'typesBouteilles'));
    }

    public function preview(Request $request)
    {
        $request->validate([
            'date_livraison' => 'required|date|after:today',
            'lignes.*.id_gamme' => 'required|exists:gammes,id',
            'lignes.*.quantite_bouteilles' => 'required|integer|min:1',
            'lignes.*.id_bouteille' => 'required|integer|min:1',
        ]);

        $result = [
            'stocks_disponibles' => [],
            'faisabilite' => [],
            'stocks_manquants' => [],
            'commande' => $request->all(), // Stocker pour enregistrement ultérieur
        ];

        foreach ($request->lignes as $ligne) {
            $gamme = Gamme::with('lots')->find($ligne['id_gamme']);
            $quantiteDemandee = $ligne['quantite_bouteilles'];
            $typeBouteille = TypeBouteille::where('id', $ligne['id_bouteille'])->get()[0];

            // Stocks disponibles (somme des bouteilles dans les lots de la gamme)
            $stockDisponible = DB::table('vue_reste_bouteilles_par_lot')
                    ->where('id_gamme', $gamme->id)
                    ->where('id_bouteille', $ligne['id_bouteille'])
                    ->sum('reste_bouteilles');
            $result['stocks_disponibles'][$gamme->id] = [
                'gamme' => $gamme->nom,
                'quantite' => $stockDisponible,
                'type_bouteille' => $typeBouteille->nom,
            ];
            $result['quantite_demandee'][$gamme->id] = $quantiteDemandee;

            // Vérifier faisabilité temporelle
            $joursNecessaires = $gamme->fermentation_jours + $gamme->vieillissement_jours;
            $dateLivraison = Carbon::parse($request->date_livraison);
            $datePossible = Carbon::today()->addDays($joursNecessaires);
            $faisableDansDelais = $dateLivraison->greaterThanOrEqualTo($datePossible);

            if ($stockDisponible >= $quantiteDemandee) {
                $result['faisabilite'][$gamme->id] = 'Réalisable dans le délai';
                $result['date_disponibilite'][$gamme->id] = Carbon::today();
                $result['date_livraison'][$gamme->id] = $dateLivraison;
            } else {
                if ($faisableDansDelais) $result['faisabilite'][$gamme->id] = 'Réalisable dans le délai';
                else $result['faisabilite'][$gamme->id] = 'Réalisable hors délai';

                $result['date_disponibilite'][$gamme->id] = $datePossible;
                $result['date_livraison'][$gamme->id] = $dateLivraison;
                $result['manquant'][$gamme->id] = max(0, $quantiteDemandee - $stockDisponible);
                

                if ($result['manquant'][$gamme->id] > 0) {
                    // Vérifier matières premières
                    $matieres = GammeMatiere::where('id_gamme', $gamme->id)->get();
                    foreach ($matieres as $matiere) {
                        $quantiteRequise = $matiere->quantite * $result['manquant'][$gamme->id];
                        $stockMatiere = MouvementStockMatierePremiere::where('id_matiere', $matiere->id_matiere)
                            ->sum('quantite');

                        if ($stockMatiere < $quantiteRequise) {
                            $result['stocks_manquants'][] = [
                                'matiere' => $matiere->matiere->nom,
                                'quantite_manquante' => $quantiteRequise - $stockMatiere,
                                'gamme' => $gamme->nom,
                                'type_bouteille' => $typeBouteille->nom,
                            ];
                        }
                    }
                }
            }
        }

        return view('commandes.result', compact('result'));
    }

    public function store(Request $request)
    {
        $commandeData = json_decode($request->input('commande'), true);
        // Créer la commande (simplifiée, sans client)
        $commande = \App\Models\Commande::create([
            'id_client' => 1, // À adapter (client par défaut ou autre logique)
            'date_commande' => now(),
            'date_livraison' => $commandeData['date_livraison'],
            'id_statut_commande' => 1,
            'total' => 0,
        ]);
        
        // Enregistrer les lignes (associer à un lot existant ou fictif)
        foreach ($commandeData['lignes'] as $ligne) {
            $lot = LotProduction::where('id_gamme', $ligne['id_gamme'])->first();
            if ($lot) {
                \App\Models\LigneCommande::create([
                    'id_commande' => $commande->id,
                    'id_lot' => $lot->id,
                    'quantite_bouteilles' => $ligne['quantite_bouteilles'],
                    'prix_unitaire' => 10.00, // À adapter
                ]);
            }
        }

        $commande->total = $commande->lignes->sum(function ($ligne) {
            return $ligne->quantite_bouteilles * $ligne->prix_unitaire;
        });
        $commande->save();

        return redirect()->route('commandes.preview')->with('success', 'Commande enregistrée avec succès.');
    }
}