<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockProduitsFinisController extends Controller
{
    // Static initial number of bottles for the 10% threshold
    private const INITIAL_BOTTLE_COUNT = 1000;

    /**
     * Get stock data for a specific date
     * @param string $date
     * @return array
     */
    private function getStockDataForDate($date)
    {
        // Convert date to Carbon instance for better handling
        $filterDate = Carbon::parse($date)->format('Y-m-d');

        // Remaining bottles per lot for specific date
        $stocksPerLot = DB::table('lot_productions as lp')
            ->leftJoin('mouvement_produits as mp', function($join) use ($filterDate) {
                $join->on('mp.id_lot', '=', 'lp.id')
                     ->where('mp.date_mouvement', '<=', $filterDate);
            })
            ->leftJoin('mouvement_produits_commandes as mpc', 'mpc.id_mouvement_produit', '=', 'mp.id')
            ->leftJoin('commandes as c', function($join) use ($filterDate) {
                $join->on('c.id', '=', 'mpc.id_commande')
                     ->where('c.date_commande', '<=', $filterDate);
            })
            ->leftJoin('historique_commandes as hc', function($join) use ($filterDate) {
                $join->on('hc.id_commande', '=', 'c.id')
                     ->where('hc.date_hist', '<=', $filterDate)
                     ->whereRaw('hc.date_hist = (
                         SELECT MAX(hc2.date_hist) 
                         FROM historique_commandes hc2 
                         WHERE hc2.id_commande = c.id 
                         AND hc2.date_hist <= ?
                     )', [$filterDate]);
            })
            ->leftJoin('type_bouteilles as tb', 'tb.id', '=', 'lp.id_bouteille')
            ->join('gammes', 'lp.id_gamme', '=', 'gammes.id')
            ->select(
                'lp.id as id_lot',
                'lp.id_gamme',
                'lp.id_bouteille',
                'tb.nom as nom_bouteille',
                'tb.capacite as capacite_bouteille',
                'gammes.nom as nom_gamme',
                'lp.date_debut',
                'lp.date_mise_en_bouteille',
                'lp.date_commercialisation',
                'lp.nombre_bouteilles',
                DB::raw('COALESCE(lp.nombre_bouteilles, 0) + COALESCE(SUM(
                    CASE
                        WHEN mpc.id_commande IS NULL THEN mp.quantite_bouteilles
                        WHEN hc.id_status_commande > 1 THEN mp.quantite_bouteilles
                        ELSE 0
                    END
                ), 0) AS reste_bouteilles')
            )
            ->groupBy(
                'lp.id', 'lp.id_gamme', 'lp.id_bouteille', 'tb.nom', 'tb.capacite',
                'gammes.nom', 'lp.date_debut', 'lp.date_mise_en_bouteille', 
                'lp.date_commercialisation', 'lp.nombre_bouteilles'
            )
            ->orderBy('lp.id')
            ->get();

        return $stocksPerLot;
    }

    /**
     * Get aggregated stock data for a specific date
     * @param string $date
     * @return array
     */
    private function getAggregatedStockDataForDate($date)
    {
        $stocksPerLot = $this->getStockDataForDate($date);

        // All possible gamme-bottle combinations with remaining bottles
        $stocksPerGammeBouteille = DB::table('gammes')
            ->crossJoin('type_bouteilles')
            ->leftJoin(DB::raw('(' . $this->getStockSubquery($date) . ') as stock_data'), function($join) {
                $join->on('gammes.id', '=', 'stock_data.id_gamme')
                     ->on('type_bouteilles.id', '=', 'stock_data.id_bouteille');
            })
            ->select(
                'gammes.nom as nom_gamme',
                'type_bouteilles.nom as nom_bouteille',
                'type_bouteilles.capacite as capacite_bouteille',
                DB::raw('COALESCE(SUM(stock_data.reste_bouteilles), 0) as total_reste_bouteilles')
            )
            ->groupBy('gammes.nom', 'type_bouteilles.nom', 'type_bouteilles.capacite')
            ->orderBy('gammes.nom')
            ->orderBy('type_bouteilles.nom')
            ->get();

        // Remaining bottles by gamme
        $stocksByGamme = DB::table('gammes')
            ->leftJoin(DB::raw('(' . $this->getStockSubquery($date) . ') as stock_data'), 'gammes.id', '=', 'stock_data.id_gamme')
            ->select(
                'gammes.nom as nom_gamme',
                DB::raw('COALESCE(SUM(stock_data.reste_bouteilles), 0) as total_reste_bouteilles')
            )
            ->groupBy('gammes.nom')
            ->orderBy('gammes.nom')
            ->get();

        // Remaining bottles by bottle type
        $stocksByBouteille = DB::table('type_bouteilles')
            ->leftJoin(DB::raw('(' . $this->getStockSubquery($date) . ') as stock_data'), 'type_bouteilles.id', '=', 'stock_data.id_bouteille')
            ->select(
                'type_bouteilles.nom as nom_bouteille',
                'type_bouteilles.capacite as capacite_bouteille',
                DB::raw('COALESCE(SUM(stock_data.reste_bouteilles), 0) as total_reste_bouteilles')
            )
            ->groupBy('type_bouteilles.nom', 'type_bouteilles.capacite')
            ->orderBy('type_bouteilles.nom')
            ->get();

        return [
            'stocksPerLot' => $stocksPerLot,
            'stocksPerGammeBouteille' => $stocksPerGammeBouteille,
            'stocksByGamme' => $stocksByGamme,
            'stocksByBouteille' => $stocksByBouteille
        ];
    }

    /**
     * Generate subquery for stock calculation with date filter
     * @param string $date
     * @return string
     */
    private function getStockSubquery($date)
    {
        $filterDate = Carbon::parse($date)->format('Y-m-d');
        
        return "
            SELECT
                lp.id as id_lot,
                lp.id_gamme,
                lp.id_bouteille,
                COALESCE(lp.nombre_bouteilles, 0) + COALESCE(SUM(
                    CASE
                        WHEN mpc.id_commande IS NULL THEN mp.quantite_bouteilles
                        WHEN hc.id_status_commande > 1 THEN mp.quantite_bouteilles
                        ELSE 0
                    END
                ), 0) AS reste_bouteilles
            FROM lot_productions lp
            LEFT JOIN mouvement_produits mp ON mp.id_lot = lp.id 
                AND mp.date_mouvement <= '{$filterDate}'
            LEFT JOIN mouvement_produits_commandes mpc ON mpc.id_mouvement_produit = mp.id
            LEFT JOIN commandes c ON c.id = mpc.id_commande 
                AND c.date_commande <= '{$filterDate}'
            LEFT JOIN historique_commandes hc ON hc.id_commande = c.id 
                AND hc.date_hist <= '{$filterDate}'
                AND hc.date_hist = (
                    SELECT MAX(hc2.date_hist) 
                    FROM historique_commandes hc2 
                    WHERE hc2.id_commande = c.id 
                    AND hc2.date_hist <= '{$filterDate}'
                )
            GROUP BY lp.id, lp.id_gamme, lp.id_bouteille, lp.nombre_bouteilles
        ";
    }

    /**
     * Get low stock messages for notification for a specific date
     * @param string $date
     * @return array
     */
    public function getLowStockMessages($date = null)
    {
        $date = $date ?? Carbon::today()->format('Y-m-d');
        $threshold = self::INITIAL_BOTTLE_COUNT * 0.1; // 10% threshold (100 bottles)
        $lowStockMessages = [];

        $stockData = $this->getAggregatedStockDataForDate($date);

        // Check per lot
        foreach ($stockData['stocksPerLot'] as $stock) {
            if ($stock->reste_bouteilles < $threshold && $stock->reste_bouteilles > 0) {
                $lowStockMessages[] = "Lot ID {$stock->id_lot} ({$stock->nom_gamme}, {$stock->nom_bouteille}, {$stock->capacite_bouteille}L): {$stock->reste_bouteilles} bottles remaining";
            }
        }

        // Check per gamme-bottle combination
        foreach ($stockData['stocksPerGammeBouteille'] as $stock) {
            if ($stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles > 0) {
                $lowStockMessages[] = "Gamme-Bottle ({$stock->nom_gamme}, {$stock->nom_bouteille}, {$stock->capacite_bouteille}L): {$stock->total_reste_bouteilles} bottles remaining";
            }
        }

        // Check by gamme
        foreach ($stockData['stocksByGamme'] as $stock) {
            if ($stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles > 0) {
                $lowStockMessages[] = "Gamme {$stock->nom_gamme}: {$stock->total_reste_bouteilles} bottles remaining";
            }
        }

        // Check by bottle type
        foreach ($stockData['stocksByBouteille'] as $stock) {
            if ($stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles > 0) {
                $lowStockMessages[] = "Bottle type {$stock->nom_bouteille} ({$stock->capacite_bouteille}L): {$stock->total_reste_bouteilles} bottles remaining";
            }
        }

        return $lowStockMessages;
    }

    /**
     * Show all stocks for a specific date
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showAllStocks(Request $request)
    {
        // Get date from request or use today's date
        $selectedDate = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        // Validate date format
        try {
            $validatedDate = Carbon::parse($selectedDate)->format('Y-m-d');
        } catch (\Exception $e) {
            $validatedDate = Carbon::today()->format('Y-m-d');
        }

        // Get all stock data for the specified date
        $stockData = $this->getAggregatedStockDataForDate($validatedDate);

        $threshold = self::INITIAL_BOTTLE_COUNT * 0.1; // 10% threshold (100 bottles)

        // Check for low stock and generate notification message
        $lowStockMessages = [];

        // Check per lot
        foreach ($stockData['stocksPerLot'] as $stock) {
            if ($stock->reste_bouteilles < $threshold && $stock->reste_bouteilles >= 0) {
                $lowStockMessages[] = "Lot {$stock->id} ({$stock->nom_gamme}, {$stock->nom_bouteille}, {$stock->capacite_bouteille}L): {$stock->reste_bouteilles} bouteilles reste";
            }
        }

        // Check per gamme-bottle combination
        foreach ($stockData['stocksPerGammeBouteille'] as $stock) {
            if ($stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles >= 0) {
                $lowStockMessages[] = "Gamme-Bouteille ({$stock->nom_gamme}, {$stock->nom_bouteille}, {$stock->capacite_bouteille}L): {$stock->total_reste_bouteilles} bouteilles reste";
            }
        }

        // Check by gamme
        foreach ($stockData['stocksByGamme'] as $stock) {
            if ($stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles >= 0) {
                $lowStockMessages[] = "Gamme {$stock->nom_gamme}: {$stock->total_reste_bouteilles} bouteilles reste";
            }
        }

        // Check by bottle type
        foreach ($stockData['stocksByBouteille'] as $stock) {
            if ($stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles >= 0) {
                $lowStockMessages[] = "{$stock->nom_bouteille} ({$stock->capacite_bouteille}L): {$stock->total_reste_bouteilles} bouteilles reste";
            }
        }

        // Create notification message
        $notification = !empty($lowStockMessages)
            ? 'Rupture 10% de stocks:<br>' . implode('<br>', $lowStockMessages)
            : null;

        return view('stocks.produits_finis.all', [
            'stocksPerLot' => $stockData['stocksPerLot'],
            'stocksPerGammeBouteille' => $stockData['stocksPerGammeBouteille'],
            'stocksByGamme' => $stockData['stocksByGamme'],
            'stocksByBouteille' => $stockData['stocksByBouteille'],
            'threshold' => $threshold,
            'notification' => $notification,
            'selectedDate' => $validatedDate
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
