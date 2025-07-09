<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aperçu du Stock - ToakaVary</title>
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
            margin-bottom: 2rem;
        }
        table {
            min-width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        th, td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid #3d3d3d;
        }
        th {
            background-color: #2d2d2d;
            color: #cdb587;
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        tr:hover {
            background-color: #3d3d3d;
        }
        .low-stock {
            background-color: rgba(239, 68, 68, 0.1) !important;
        }
        .low-stock td {
            color: #f87171;
            font-weight: 600;
        }
        .notification {
            background-color: rgba(239, 68, 68, 0.2);
            border-left: 4px solid #f87171;
            padding: 1rem;
            margin-bottom: 2rem;
            color: #f87171;
            border-radius: 0.375rem;
        }
        .section-title {
            color: #cdb587;
            font-size: 1.25rem;
            font-weight: 600;
            margin: 1.5rem 0 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #3d3d3d;
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
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 bg-gray-800 hover:bg-opacity-20 hover:bg-white transition">
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
            <i class="fas fa-chart-bar mr-3 w-5 text-center"></i> Historique Vente
        </a>
    </nav>
    <a href="/login" class="flex items-center py-2 px-3 rounded hover:bg-opacity-20 hover:bg-white transition mt-auto">
        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i> Déconnexion
    </a>
</div>

<!-- Main Content -->
<main class="flex-grow p-6 overflow-auto">
    <div class="main-content rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold gold-text mb-6">Aperçu du Stock</h1>

        @if ($notification)
            <div class="notification flex items-start">
                <i class="fas fa-exclamation-triangle mr-3 mt-1"></i>
                <div>{!! $notification !!}</div>
            </div>
        @endif

        <div class="section-title">Reste bouteille par Lot</div>
        <div class="table-container">
            <table>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Gamme</th>
                    <th>Bouteille</th>
                    <th>Capacité</th>
                    <th>Date début</th>
                    <th>Date embouteillage</th>
                    <th>Date commercialisation</th>
                    <th>Nombre initial</th>
                    <th>Reste bouteille</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($stocksPerLot as $stock)
                    <tr class="{{ $stock->reste_bouteilles < $threshold && $stock->reste_bouteilles > 0 ? 'low-stock' : '' }}">
                        <td>{{ $stock->id_lot }}</td>
                        <td>{{ $stock->nom_gamme }}</td>
                        <td>{{ $stock->nom_bouteille }}</td>
                        <td>{{ $stock->capacite_bouteille }} L</td>
                        <td>{{ \Carbon\Carbon::parse($stock->date_debut)->format('d/m/Y') }}</td>
                        <td>{{ $stock->date_mise_en_bouteille ? \Carbon\Carbon::parse($stock->date_mise_en_bouteille)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $stock->date_commercialisation ? \Carbon\Carbon::parse($stock->date_commercialisation)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $stock->nombre_bouteilles }}</td>
                        <td>{{ $stock->reste_bouteilles }}</td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center py-4">Aucun lot trouvé</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="section-title">Reste bouteille par Gamme-Type Bouteille</div>
        <div class="table-container">
            <table>
                <thead>
                <tr>
                    <th>Gamme</th>
                    <th>Bouteille</th>
                    <th>Capacité</th>
                    <th>Reste bouteille</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($stocksPerGammeBouteille as $stock)
                    <tr class="{{ $stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles > 0 ? 'low-stock' : '' }}">
                        <td>{{ $stock->nom_gamme }}</td>
                        <td>{{ $stock->nom_bouteille }}</td>
                        <td>{{ $stock->capacite_bouteille }} L</td>
                        <td>{{ $stock->total_reste_bouteilles }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center py-4">Pas de combinaison gamme-bouteille disponible</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="section-title">Reste bouteille par Gamme</div>
        <div class="table-container">
            <table>
                <thead>
                <tr>
                    <th>Gamme</th>
                    <th>Reste bouteille</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($stocksByGamme as $stock)
                    <tr class="{{ $stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles > 0 ? 'low-stock' : '' }}">
                        <td>{{ $stock->nom_gamme }}</td>
                        <td>{{ $stock->total_reste_bouteilles }}</td>
                    </tr>
                @empty
                    <tr><td colspan="2" class="text-center py-4">Pas de gamme disponible</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="section-title">Reste bouteille par Type</div>
        <div class="table-container">
            <table>
                <thead>
                <tr>
                    <th>Bouteille</th>
                    <th>Capacité</th>
                    <th>Reste bouteille</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($stocksByBouteille as $stock)
                    <tr class="{{ $stock->total_reste_bouteilles < $threshold && $stock->total_reste_bouteilles > 0 ? 'low-stock' : '' }}">
                        <td>{{ $stock->nom_bouteille }}</td>
                        <td>{{ $stock->capacite_bouteille }} L</td>
                        <td>{{ $stock->total_reste_bouteilles }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center py-4">Pas de type de bouteille disponible</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
</body>
</html>
