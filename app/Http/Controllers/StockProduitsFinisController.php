<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class StockProduitsFinisController extends Controller
{
    // Static initial number of bottles for the 10% threshold
    private const INITIAL_BOTTLE_COUNT = 1000;

    /**
     * Get low stock messages for notification
     * @return array
     */
    public function getLowStockMessages()
    {
        $threshold = self::INITIAL_BOTTLE_COUNT * 0.1; // 10% threshold (100 bottles)
        $lowStockMessages = [];

        // Remaining bottles per lot
        $stocksPerLot = DB::table('vue_reste_bouteilles_par_lot')
            ->select(
                'id_lot',
                'nom_bouteille',
                'capacite_bouteille',
                'gammes.nom as nom_gamme',
                'reste_bouteilles'
            )
            ->join('gammes', 'vue_reste_bouteilles_par_lot.id_gamme', '=', 'gammes.id')
            ->get();

        // All possible gamme-bottle combinations
        $stocksPerGammeBouteille = DB::table('gammes')
            ->crossJoin('type_bouteilles')
            ->leftJoin('vue_reste_bouteilles_par_lot', function ($join) {
                $join->on('gammes.id', '=', 'vue_reste_bouteilles_par_lot.id_gamme')
                     ->on('type_bouteilles.id', '=', 'vue_reste_bouteilles_par_lot.id_bouteille');
            })
            ->select(
                'gammes.nom as nom_gamme',
                'type_bouteilles.nom as nom_bouteille',
                'type_bouteilles.capacite as capacite_bouteille',
                DB::raw('COALESCE(SUM(vue_reste_bouteilles_par_lot.reste_bouteilles), 0) as total_reste_bouteilles')
            )
            ->groupBy('gammes.nom', 'type_bouteilles.nom', 'type_bouteilles.capacite')
            ->get();

        // Remaining bottles by gamme
        $stocksByGamme = DB::table('gammes')
            ->leftJoin('vue_reste_bouteilles_par_lot', 'gammes.id', '=', 'vue_reste_bouteilles_par_lot.id_gamme')
            ->select(
                'gammes.nom as nom_gamme',
                DB::raw('COALESCE(SUM(vue_reste_bouteilles_par_lot.reste_bouteilles), 0) as total_reste_bouteilles')
            )
            ->groupBy('gammes.nom')
            ->get();

        // Remaining bottles by bottle type
        $stocksByBouteille = DB::table('type_bouteilles')
            ->leftJoin('vue_reste_bouteilles_par_lot', 'type_bouteilles.id', '=', 'vue_reste_bouteilles_par_lot.id_bouteille')
            ->select(
                'type_bouteilles.nom as nom_bouteille',
                'type_bouteilles.capacite as capacite_bouteille',
                DB::raw('COALESCE(SUM(vue_reste_bouteilles_par_lot.reste_bouteilles), 0) as total_reste_bouteilles')
            )
            ->groupBy('type_bouteilles.nom', 'type_bouteilles.capacite')
            ->get();

        // Check per lot
        foreach ($stocksPerLot as $stock) {
            if ($stock->reste_bouteilles < $threshold && $stock->reste_bouteilles > 0) {
                $lowStockMessages[] = "Lot ID {$stock->id_lot} ({$stock->nom_gamme}, {$stock->nom_bouteille}, {$stock->capacite_bouteille}L): {$stock->reste_bouteilles} bottles remaining";
            }
        }

        // Check per gamme-bottle combination
        foreach ($stocksPerGammeBouteille as $stock) {
            if ($stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles > 0) {
                $lowStockMessages[] = "Gamme-Bottle ({$stock->nom_gamme}, {$stock->nom_bouteille}, {$stock->capacite_bouteille}L): {$stock->total_reste_bouteilles} bottles remaining";
            }
        }

        // Check by gamme
        foreach ($stocksByGamme as $stock) {
            if ($stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles > 0) {
                $lowStockMessages[] = "Gamme {$stock->nom_gamme}: {$stock->total_reste_bouteilles} bottles remaining";
            }
        }

        // Check by bottle type
        foreach ($stocksByBouteille as $stock) {
            if ($stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles > 0) {
                $lowStockMessages[] = " )/bouteille, {$stock->capacite_bouteille}L): {$stock->total_reste_bouteilles} bottles remaining";
            }
        }

        return $lowStockMessages;
    }

    public function showAllStocks()
    {
        // Remaining bottles per lot
        $stocksPerLot = DB::table('vue_reste_bouteilles_par_lot')
            ->select(
                'vue_reste_bouteilles_par_lot.id',  // au lieu de 'id_lot'
                'nom_bouteille',
                'capacite_bouteille',
                'gammes.nom as nom_gamme',
                'date_debut',
                'date_mise_en_bouteille',
                'date_commercialisation',
                'nombre_bouteilles',
                'reste_bouteilles'
            )
            ->join('gammes', 'vue_reste_bouteilles_par_lot.id_gamme', '=', 'gammes.id')
            ->orderBy('vue_reste_bouteilles_par_lot.id') // ici c'est correct, car id existe
            ->get();

        // All possible gamme-bottle combinations with remaining bottles (0 if none)
        $stocksPerGammeBouteille = DB::table('gammes')
            ->crossJoin('type_bouteilles')
            ->leftJoin('vue_reste_bouteilles_par_lot', function ($join) {
                $join->on('gammes.id', '=', 'vue_reste_bouteilles_par_lot.id_gamme')
                    ->on('type_bouteilles.id', '=', 'vue_reste_bouteilles_par_lot.id_bouteille');
            })
            ->select(
                'gammes.nom as nom_gamme',
                'type_bouteilles.nom as nom_bouteille',
                'type_bouteilles.capacite as capacite_bouteille',
                DB::raw('COALESCE(SUM(vue_reste_bouteilles_par_lot.reste_bouteilles), 0) as total_reste_bouteilles')
            )
            ->groupBy('gammes.nom', 'type_bouteilles.nom', 'type_bouteilles.capacite')
            ->get();


        // Remaining bottles by gamme (including gammes with 0 bottles)
        $stocksByGamme = DB::table('gammes')
            ->leftJoin('vue_reste_bouteilles_par_lot', 'gammes.id', '=', 'vue_reste_bouteilles_par_lot.id_gamme')
            ->select(
                'gammes.nom as nom_gamme',
                DB::raw('COALESCE(SUM(vue_reste_bouteilles_par_lot.reste_bouteilles), 0) as total_reste_bouteilles')
            )
            ->groupBy('gammes.nom')
            ->orderBy('gammes.nom')
            ->get();

        // Remaining bottles by bottle type (including bottle types with 0 bottles)
        $stocksByBouteille = DB::table('type_bouteilles')
            ->leftJoin('vue_reste_bouteilles_par_lot', 'type_bouteilles.id', '=', 'vue_reste_bouteilles_par_lot.id_bouteille')
            ->select(
                'type_bouteilles.nom as nom_bouteille',
                'type_bouteilles.capacite as capacite_bouteille',
                DB::raw('COALESCE(SUM(vue_reste_bouteilles_par_lot.reste_bouteilles), 0) as total_reste_bouteilles')
            )
            ->groupBy('type_bouteilles.nom', 'type_bouteilles.capacite')
            ->orderBy('type_bouteilles.nom')
            ->get();

        $threshold = self::INITIAL_BOTTLE_COUNT * 0.1; // 10% threshold (100 bottles)

        // Check for low stock and generate notification message
        $lowStockMessages = [];

        // Check per lot
        foreach ($stocksPerLot as $stock) {
            if ($stock->reste_bouteilles < $threshold && $stock->reste_bouteilles >= 0) {
                $lowStockMessages[] = "Lot {$stock->id} ({$stock->nom_gamme}, {$stock->nom_bouteille}, {$stock->capacite_bouteille}L): {$stock->reste_bouteilles} bouteilles reste";
            }
        }

        // Check per gamme-bottle combination
        foreach ($stocksPerGammeBouteille as $stock) {
            if ($stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles >= 0) {
                $lowStockMessages[] = "Gamme-Bouteille ({$stock->nom_gamme}, {$stock->nom_bouteille}, {$stock->capacite_bouteille}L): {$stock->total_reste_bouteilles} bouteilles reste";
            }
        }

        // Check by gamme
        foreach ($stocksByGamme as $stock) {
            if ($stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles >= 0) {
                $lowStockMessages[] = "Gamme {$stock->nom_gamme}: {$stock->total_reste_bouteilles} bouteilles reste";
            }
        }

        // Check by bottle type
        foreach ($stocksByBouteille as $stock) {
            if ($stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles >= 0) {
                $lowStockMessages[] = "$stock->nom_bouteille, ({$stock->capacite_bouteille}L): {$stock->total_reste_bouteilles} bouteilles reste";
            }
        }

        // Create notification message
        $notification = !empty($lowStockMessages)
            ? 'Rupture 10% de stocks:<br>' . implode('<br>', $lowStockMessages)
            : null;


        return view('stocks.produits_finis.all', [
            'stocksPerLot' => $stocksPerLot,
            'stocksPerGammeBouteille' => $stocksPerGammeBouteille,
            'stocksByGamme' => $stocksByGamme,
            'stocksByBouteille' => $stocksByBouteille,
            'threshold' => $threshold,
            'notification' => !empty($lowStockMessages) ? 'Rupture 10% de stocks:<br>' . implode('<br>', $lowStockMessages) : null,
        ]);
    }

    public function getStockAvantDate($date)
    {
        return DB::table('lot_productions as lp')
            ->leftJoin('mouvement_produits as mp', 'lp.id', '=', 'mp.id_lot')
            ->where('mp.date_mouvement', '<=', $date)
            ->select(
                'lp.id as id',
                'lp.id_gamme',
                'lp.id_bouteille',
                'lp.date_mise_en_bouteille',
                DB::raw('SUM(mp.quantite_bouteilles) as reste_bouteilles')
            )
            ->groupBy('lp.id', 'lp.id_gamme', 'lp.id_bouteille', 'lp.date_mise_en_bouteille')
            ->get();
    }

    public function stockSelonCommande($idCommande)
    {
        // 1. Récupérer la date de commande
        $commande = DB::table('commandes')->where('id', $idCommande)->first();
        if (!$commande) {
            return back()->with('error', 'Commande non trouvée');
        }

        $dateCommande = Carbon::parse($commande->date_commande);

        // 2. Calculer les restes de bouteilles par lot avant la date de commande
        $stocks = DB::table('lot_productions as lp')
            ->join('gammes as g', 'lp.id_gamme', '=', 'g.id')
            ->join('type_bouteilles as tb', 'lp.id_bouteille', '=', 'tb.id')
            ->leftJoin('mouvement_produits as mp', function ($join) use ($dateCommande) {
                $join->on('lp.id', '=', 'mp.id_lot')
                    ->where('mp.date_mouvement', '<=', $dateCommande);
            })
            ->select(
                'lp.id',
                'g.nom as nom_gamme',
                'tb.nom as nom_bouteille',
                'tb.capacite as capacite_bouteille',
                'lp.date_debut',
                'lp.date_mise_en_bouteille',
                'lp.date_commercialisation',
                'lp.nombre_bouteilles',
                DB::raw('COALESCE(SUM(mp.quantite_bouteilles), 0) as reste_bouteilles')
            )
            ->groupBy(
                'lp.id',
                'g.nom',
                'tb.nom',
                'tb.capacite',
                'lp.date_debut',
                'lp.date_mise_en_bouteille',
                'lp.date_commercialisation',
                'lp.nombre_bouteilles'
            )
            ->orderBy('lp.id')
            ->get();

        return view('stock.par_commande', compact('stocks', 'commande'));
    }

}
