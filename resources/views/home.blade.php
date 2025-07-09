<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil - ToakaVary</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #0c0c0c;
        }
        .sidebar-custom {
            background-color: #1b1b1b;
        }
        .sidebar-custom a:hover {
            background-color: #2d2d2d;
        }
        .chart-placeholder {
            background: linear-gradient(to right, #2a2a2a 8%, #3a3a3a 18%, #2a2a2a 33%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite linear;
        }
        @keyframes shimmer {
            0% { background-position: 100% 0; }
            100% { background-position: -100% 0; }
        }
        .custom-curve {
            background-color: #cdb587;
        }
    </style>
</head>
<body class="flex h-screen m-0 font-sans text-white">

<!-- Sidebar -->
<div class="w-64 sidebar-custom text-white p-5 flex flex-col">
    <img src="img/logo_rhum_rice.png" alt="logo" class="w-48 mx-auto mb-8">
    <nav class="flex-1">
        <a href="/home" class="flex items-center py-2 px-3 rounded mb-2 bg-gray-800 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>
            Dashboard
        </a>
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-industry mr-3 w-5 text-center"></i>
            Production
        </a>
        <a href="{{ route('commandes.preview') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-industry mr-3 w-5 text-center"></i>
            Prevision Production
        </a>
        <a href="{{ route('stockProduitsFinis.all') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-boxes mr-3 w-5 text-center"></i>
            Stocks
        </a>

        <a href="{{ route('production.calendar') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
            Calendrier
        </a>
        <a href="{{ route('commandes.preview') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
            Prevision de commandes
            
        </a>
        <a href="{{ route('production.commandes') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
            Commandes des clients
        </a>
        <a href="/statForm" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
            Statistiques des ventes
        </a>
        <a href="{{ route('production.histogram') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
            Statistiques des productions
        </a>
        <a href="{{ route('historique_vente') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-history mr-3 w-5 text-center"></i>
            Historique Vente
        </a>


    </nav>
    <a href="/login" class="flex items-center py-2 px-3 rounded hover:bg-opacity-20 hover:bg-white transition mt-auto">
        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
        Déconnexion
    </a>
</div>

<!-- Main Content -->
<div class="flex-1 overflow-auto">
    <div class="p-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold">Dashboard</h1>
                <p class="text-gray-400">Page / Dashboard</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-[#2a2a2a] rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-400">PORTE FEUILLE</p>
                        <h3 class="text-2xl font-bold mt-1">20.000.000 AR</h3>
                        <p class="text-sm text-green-400 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i> +35% depuis hier
                        </p>
                    </div>
                    <div class="bg-blue-900 p-3 rounded-full">
                        <i class="fas fa-wallet text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-[#2a2a2a] rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-400">STOCK</p>
                        <h3 class="text-2xl font-bold mt-1">2,300</h3>
                        <p class="text-sm text-green-400 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i> +3% semaine passée
                        </p>
                    </div>
                    <div class="bg-green-900 p-3 rounded-full">
                        <i class="fas fa-users text-green-400 text-xl"></i>
                    </div>
                </div>
            </div>
            <div class="bg-[#2a2a2a] rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-400">CLIENTS</p>
                        <h3 class="text-2xl font-bold mt-1">{{ $nombreClients }}</h3>
                        <p class="text-sm text-red-400 mt-2">
                            <i class="fas fa-arrow-down mr-1"></i> +2% semaine passée
                        </p>
                    </div>
                    <div class="bg-orange-900 p-3 rounded-full">
                        <i class="fas fa-user-plus text-orange-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-[#2a2a2a] rounded-lg shadow p-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-medium text-gray-400">CHIFFRE D'AFFAIRE</p>
                        <?php
                            $chiffre=DB::select("
                                SELECT SUM(montant) AS montant FROM ventes WHERE year(date_vente)=year(Now())
                            ");
                            if ($chiffre) 
                            {
                                $montant = $chiffre[0]->montant;
                            } 
                            else 
                            {
                                $montant = 0;
                            }
                            $plus=DB::select("
                                SELECT SUM(montant) AS montant FROM ventes WHERE year(date_vente)=year(Now()) AND month(date_vente)=month(Now())
                            ");
                            if ($plus) 
                            {
                                $montant_plus= $plus[0]->montant;
                            }
                            else
                            {
                                $montant_plus = 0;
                            }
                            $montant_before=$montant - $montant_plus;
                            if ($montant_before == 0) {
                                $pourcentage = 0; // Eviter la division par zéro
                            } else {
                                $pourcentage = ($montant_plus * 100) / $montant_before;
                            }


                        ?>
                        <h3 class="text-2xl font-bold mt-1"><?=$montant ?> AR</h3>
                        <p class="text-sm text-green-400 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i> <?=$pourcentage ?>% ce mois
                        </p>
                    </div>
                    <div class="bg-purple-900 p-3 rounded-full">
                        <i class="fas fa-shopping-cart text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-[#2a2a2a] rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg font-bold">Vente cette année</h3>
                        <p class="text-sm text-green-400">
                            <i class="fas fa-arrow-up mr-1"></i>
                        </p>
                    </div>
                </div>
                <div class="chart-placeholder h-64 rounded-lg flex items-end px-4">
                    <div class="flex-1 flex items-end space-x-1 w-full">
                    <?php
                        $valeur=[];
                        for ($i = 0; $i < 12 ; $i++) 
                        {
                            $stat_vente=DB::select("
                                SELECT SUM(montant) AS montant
                                FROM ventes
                                WHERE year(date_vente) = year(NOW())
                                AND month(date_vente) = $i + 1
                            ");
                            if (empty($stat_vente)) {
                                $valeur[$i] = 0; // Si pas de vente, valeur est 0
                            } else {
                                // On divise par 1 million pour l'affichage
                                $valeur[$i] = $stat_vente[0]->montant / 1000000;
                            }
                        }
                        for ($i = 0; $i < 12; $i++) 
                        {
                            
                    ?>
                            <div class="custom-curve w-10 h-<?=$valeur[$i] ?>"></div>

                    <?php
                        }
                    ?>


                    </div>
                </div>
                <div class="flex justify-between mt-4 text-sm text-gray-400">
                    <?php
                        $mois=[];
                        $mois[1] = 'Jan';
                        $mois[2] = 'Fév';
                        $mois[3] = 'Mar';
                        $mois[4] = 'Avr';
                        $mois[5] = 'Mai';
                        $mois[6] = 'Jun';
                        $mois[7] = 'Jul';
                        $mois[8] = 'Aoû';
                        $mois[9] = 'Sep';
                        $mois[10] = 'Oct';
                        $mois[11] = 'Nov';
                        $mois[12] = 'Déc';

                        foreach ($mois as $m) {
                            echo "<span class='text-gray-400'>$m</span>";
                        }
                    ?>
                </div>
            </div>

            <div class="bg-[#2d2d2d] rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4">Commande des clients en cours</h3>
                <?php
                   $commandes = DB::select("
                            SELECT v1.*
                            FROM vue_details_commandes v1
                            INNER JOIN (
                                SELECT idCommande, MAX(idhistorique) as max_hist
                                FROM vue_details_commandes
                                GROUP BY idCommande
                            ) v2 ON v1.idCommande = v2.idCommande AND v1.idhistorique = v2.max_hist
                            WHERE v1.idstatus = 1
                            GROUP BY v1.id_client, v1.nom, v1.date_commande, v1.date_livraison, v1.idstatus
                        ");
                ?>
                
                <div class="space-y-4">
                    <?php
                        $i=1;
                        foreach ($commandes as $commande)
                        {
                    ?>
                    <div class="flex items-center justify-between p-3 bg-[#2d2d2d] rounded-lg">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white mr-3"><?=$i ?></div>
                            <div>
                                <p class="font-medium text-white"><?php echo $commande->nom; ?></p>
                                <p class="text-sm text-gray-400"><?php echo $commande->quantite_bouteilles; ?></p>

                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-medium text-white"><?php echo $commande->montant_paye; ?></p>
                        </div>
                    </div>
                    <?php
                        $i++;
                        }
                    ?>
                    
                </div>
            </div>
        </div>

        <!-- Bottom -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-[#2a2a2a] rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4">Rappel</h3>
                <p class="text-gray-400 italic">
                    ( fermentation /vieillissement)
                </p>
            </div>

            <div class="bg-[#2a2a2a] rounded-lg shadow p-6">
                <h3 class="text-lg font-bold mb-4">Employes</h3>
                <?php
                    $employes = DB::table('employes')
                        ->select('id', 'nom', 'poste', 'email')
                        ->get();
                    
                    if ($employes->isEmpty()) {
                        echo '<p class="text-gray-400">Aucun employé trouvé.</p>';
                    } else {
                        echo '<ul class="space-y-2">';
                        foreach ($employes as $employe) {
                            echo '<li class="flex items-center justify-between p-3 bg-gray-800 rounded-lg">';
                            echo '<div class="flex items-center">';
                            echo '<span class="font-medium">' . $employe->nom . ' '. $employe->email . '</span>';
                            echo '<span class="text-sm text-gray-400 ml-2">' . $employe->poste . '</span>';
                            echo '</div>';
                            echo '<button class="text-blue-400 hover:text-blue-200">';
                            echo '<i class="fas fa-chevron-right"></i>';
                            echo '</button>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    }
            

                ?>
                <!--                <div class="flex items-center justify-between p-3 bg-gray-800 rounded-lg mb-3">-->
                <!--                    <div class="flex items-center">-->
                <!--                        <i class="fas fa-laptop mr-3 text-blue-400"></i>-->
                <!--                        <div>-->
                <!--                            <p class="font-medium">Devices</p>-->
                <!--                            <p class="text-sm text-gray-400">250 in stock, 346+ sold</p>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                    <button class="text-blue-400 hover:text-blue-200">-->
                <!--                        <i class="fas fa-chevron-right"></i>-->
                <!--                    </button>-->
                <!--                </div>-->
                <!--                <div class="flex items-center justify-between p-3 bg-gray-800 rounded-lg">-->
                <!--                    <div class="flex items-center">-->
                <!--                        <i class="fas fa-ticket-alt mr-3 text-green-400"></i>-->
                <!--                        <div>-->
                <!--                            <p class="font-medium">Tickets</p>-->
                <!--                            <p class="text-sm text-gray-400">View all tickets</p>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                    <button class="text-blue-400 hover:text-blue-200">-->
                <!--                        <i class="fas fa-chevron-right"></i>-->
                <!--                    </button>-->
                <!--                </div>-->
            </div>
        </div>

    </div>
</div>
</body>
</html>
