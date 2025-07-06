<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Statistiques - ToakaVary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e6c6f2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/">
    <style>
        body {
            background-color: #0c0c0c;
            color: white;
        }
        .sidebar {
            width: 16rem;
            background-color: #1b1b1b;
        }
        .main-content {
            background-color: #1b1b1b;
        }
        .gold-text {
            color: #cdb587;
        }
        .table-container {
            overflow-x: auto;
        }
        table {
            min-width: 800px;
        }
    </style>
</head>
<body class="min-h-screen flex">

<!-- Sidebar -->
<div class="sidebar text-white p-5 flex flex-col">
    <img src="../img/logo_rhum_rice.png" alt="logo" class="w-48 mx-auto mb-8">
    <nav class="flex-1">
        <a href="/home" class="flex items-center py-2 px-3 rounded mb-2 bg-gray-800 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i> Dashboard
        </a>
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-industry mr-3 w-5 text-center"></i> Production
        </a>
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-boxes mr-3 w-5 text-center"></i> Stocks
        </a>
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-chart-bar mr-3 w-5 text-center"></i> Statistiques
        </a>
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-calendar mr-3 w-5 text-center"></i> Calendrier
        </a>
        <a href="/statForm" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-chart-bar mr-3 w-5 text-center"></i> Statistiques des ventes
        </a>
        <a href="{{ route('historique_vente') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-history mr-3 w-5 text-center"></i>
            Historique Vente
        </a>
    </nav>
    <a href="/login" class="flex items-center py-2 px-3 rounded hover:bg-opacity-20 hover:bg-white transition mt-auto">
        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i> Déconnexion
    </a>
</div>

<!-- Main Content -->
<main class="flex-grow p-6 overflow-auto">
    <div class="main-content rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold gold-text mb-6">Statistiques de Production</h1>

        <div class="table-container">
            <table class="w-full border-collapse">
                <thead>
                <tr class="bg-[#2d2d2d] text-[#cdb587]">
                    <th class="p-3 border border-[#3d3d3d] text-left">Nom</th>
                    <th class="p-3 border border-[#3d3d3d] text-left">Coût unitaire</th>
                    <th class="p-3 border border-[#3d3d3d] text-left">Total bouteilles</th>
                    <th class="p-3 border border-[#3d3d3d] text-left">Revenue</th>
                    <th class="p-3 border border-[#3d3d3d] text-left">Dépense</th>
                    <th class="p-3 border border-[#3d3d3d] text-left">Marge Bénéficiaire</th>
                </tr>
                </thead>
                <tbody>
                @foreach($stats as $stat)
                    <tr class="hover:bg-[#3d3d3d] transition">
                        <td class="p-3 border border-[#3d3d3d]">{{ $stat->nom }}</td>
                        <td class="p-3 border border-[#3d3d3d]">{{ number_format($stat->cout_matiere_unitaire, 2) }} €</td>
                        <td class="p-3 border border-[#3d3d3d]">{{ number_format($stat->total_bouteille, 0) }}</td>
                        <td class="p-3 border border-[#3d3d3d]">{{ number_format($stat->total_montant, 2) }} €</td>
                        <td class="p-3 border border-[#3d3d3d]">{{ number_format($stat->total_depense, 2) }} €</td>
                        <td class="p-3 border border-[#3d3d3d] font-semibold
                                {{ $stat->total_marge >= 0 ? 'text-green-400' : 'text-red-400' }}">
                            {{ number_format($stat->total_marge, 2) }} €
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <!-- Résumé des statistiques -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-[#2d2d2d] p-4 rounded-lg border border-[#3d3d3d]">
                <h3 class="text-[#cdb587] font-bold mb-2">Revenue Total</h3>
                <p class="text-xl">{{ number_format($stats->sum('total_montant'), 2) }} €</p>
            </div>
            <div class="bg-[#2d2d2d] p-4 rounded-lg border border-[#3d3d3d]">
                <h3 class="text-[#cdb587] font-bold mb-2">Dépenses Totales</h3>
                <p class="text-xl">{{ number_format($stats->sum('total_depense'), 2) }} €</p>
            </div>
            <div class="bg-[#2d2d2d] p-4 rounded-lg border border-[#3d3d3d]">
                <h3 class="text-[#cdb587] font-bold mb-2">Marge Totale</h3>
                <p class="text-xl {{ $stats->sum('total_marge') >= 0 ? 'text-green-400' : 'text-red-400' }}">
                    {{ number_format($stats->sum('total_marge'), 2) }} €
                </p>
            </div>
        </div>
    </div>
</main>

</body>
</html>
