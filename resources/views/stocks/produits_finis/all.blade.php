<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aperçu du Stock - Toaka Vary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        gold: {
                            50: '#fefce8',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .low-stock {
            @apply bg-red-900 bg-opacity-30 border-red-500;
        }
        .table-row-hover:hover {
            @apply bg-gray-800 bg-opacity-50;
        }
        /* Styles pour le header sticky */
        .sticky-header {
            position: sticky;
            top: 0;
            z-index: 50;
            backdrop-filter: blur(10px);
            background-color: rgba(17, 24, 39, 0.95); /* gray-900 avec transparence */
            border-bottom: 1px solid rgba(55, 65, 81, 0.8); /* gray-800 avec transparence */
        }
        
        /* Ajustement du contenu principal pour compenser le header sticky */
        .main-content {
            padding-top: 0;
        }
    </style>
</head>
<body class="bg-gray-950 text-white">
    <!-- Include Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen">
        <!-- Header Sticky -->
        <header class="sticky-header p-4 shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-yellow-400">Aperçu du Stock</h1>
                    <p class="text-gray-400">Gestion et suivi des stocks de produits finis</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Date Filter -->
                    <div class="bg-gray-800 bg-opacity-80 backdrop-blur-sm rounded-lg p-3 border border-gray-700">
                        <form method="GET" action="{{ url()->current() }}" class="flex items-center space-x-3">
                            <label for="date" class="text-sm text-gray-300 font-medium">Date:</label>
                            <input type="date" id="date" name="date" value="{{ $selectedDate }}" 
                                   class="bg-gray-700 bg-opacity-80 text-white rounded-lg px-3 py-2 text-sm border border-gray-600 focus:border-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50 transition-all">
                            <button type="submit" class="bg-yellow-400 text-gray-900 px-4 py-2 rounded-lg text-sm font-medium hover:bg-yellow-500 transition-colors focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50">
                                Filtrer
                            </button>
                        </form>
                    </div>
                    <div class="text-sm text-gray-400">
                        <span class="bg-gray-800 bg-opacity-60 backdrop-blur-sm px-3 py-2 rounded-lg border border-gray-700">
                            {{ $selectedDate }}
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content p-6">
            <!-- Notification -->
            @if ($notification)
                <div class="mb-6 p-4 bg-yellow-900 bg-opacity-30 border border-yellow-400 rounded-lg backdrop-blur-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-400 mr-3" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                        <div class="text-yellow-400">{!! $notification !!}</div>
                    </div>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 hover:border-yellow-400 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Total Lots</p>
                            <p class="text-2xl font-bold text-yellow-400">{{ $stocksPerLot->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-400 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 6h-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 hover:border-yellow-400 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Stock Faible</p>
                            <p class="text-2xl font-bold text-red-400">{{ $stocksPerLot->where('reste_bouteilles', '<', $threshold)->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-400 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 hover:border-yellow-400 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Gammes Actives</p>
                            <p class="text-2xl font-bold text-green-400">{{ $stocksByGamme->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-400 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 hover:border-yellow-400 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Types Bouteilles</p>
                            <p class="text-2xl font-bold text-blue-400">{{ $stocksByBouteille->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-400 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tables Section -->
            <div class="space-y-8">
                <!-- Stock Détaillé par Lot -->
                <div class="bg-gray-900 rounded-xl border border-gray-800">
                    <div class="p-6 border-b border-gray-800">
                        <h3 class="text-lg font-semibold text-yellow-400">Stock Détaillé par Lot</h3>
                        <p class="text-gray-400 text-sm mt-1">Vue complète des stocks disponibles pour chaque lot de production</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-800 text-gray-300">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Gamme</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Type Bouteille</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Capacité</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Date Début</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Mise en Bouteille</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Commercialisation</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Qté Initiale</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Stock Restant</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @forelse ($stocksPerLot as $stock)
                                    <tr class="table-row-hover {{ $stock->reste_bouteilles < $threshold ? 'low-stock' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $stock->nom_gamme }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $stock->nom_bouteille }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $stock->capacite_bouteille }}L</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $stock->date_debut }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $stock->date_mise_en_bouteille }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $stock->date_commercialisation }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $stock->nombre_bouteilles }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $stock->reste_bouteilles < $threshold ? 'bg-red-400 bg-opacity-20 text-red-400' : 'bg-green-400 bg-opacity-20 text-green-400' }}">
                                                {{ $stock->reste_bouteilles }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-600 mb-4" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M20 6h-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v2H4a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2z"/>
                                                </svg>
                                                <p class="text-lg font-medium">Aucun stock trouvé</p>
                                                <p class="text-sm">Aucun stock disponible pour la date {{ $selectedDate }}</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Stock Groupé par Gamme-Bouteille -->
                <div class="bg-gray-900 rounded-xl border border-gray-800">
                    <div class="p-6 border-b border-gray-800">
                        <h3 class="text-lg font-semibold text-yellow-400">Stock Groupé par Gamme-Bouteille</h3>
                        <p class="text-gray-400 text-sm mt-1">Totaux agrégés par combinaison gamme et type de bouteille</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-800 text-gray-300">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Gamme</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Type Bouteille</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Capacité</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Stock Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @forelse ($stocksPerGammeBouteille as $stock)
                                    <tr class="table-row-hover {{ $stock->total_reste_bouteilles < $threshold ? 'low-stock' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $stock->nom_gamme }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $stock->nom_bouteille }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $stock->capacite_bouteille }}L</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $stock->total_reste_bouteilles < $threshold ? 'bg-red-400 bg-opacity-20 text-red-400' : 'bg-green-400 bg-opacity-20 text-green-400' }}">
                                                {{ $stock->total_reste_bouteilles }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                            <p>Aucune combinaison gamme-bouteille disponible pour la date {{ $selectedDate }}</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Stock par Gamme et par Type -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Stock Total par Gamme -->
                    <div class="bg-gray-900 rounded-xl border border-gray-800">
                        <div class="p-6 border-b border-gray-800">
                            <h3 class="text-lg font-semibold text-yellow-400">Stock Total par Gamme</h3>
                            <p class="text-gray-400 text-sm mt-1">Totaux consolidés par gamme de produit</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-800 text-gray-300">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Gamme</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Stock Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800">
                                    @forelse ($stocksByGamme as $stock)
                                        <tr class="table-row-hover {{ $stock->total_reste_bouteilles < $threshold ? 'low-stock' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $stock->nom_gamme }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $stock->total_reste_bouteilles < $threshold ? 'bg-red-400 bg-opacity-20 text-red-400' : 'bg-green-400 bg-opacity-20 text-green-400' }}">
                                                    {{ $stock->total_reste_bouteilles }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="px-6 py-12 text-center text-gray-400">
                                                <p>Aucune gamme disponible pour la date {{ $selectedDate }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Stock Total par Type de Bouteille -->
                    <div class="bg-gray-900 rounded-xl border border-gray-800">
                        <div class="p-6 border-b border-gray-800">
                            <h3 class="text-lg font-semibold text-yellow-400">Stock Total par Type de Bouteille</h3>
                            <p class="text-gray-400 text-sm mt-1">Totaux consolidés par type de bouteille</p>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-800 text-gray-300">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Type Bouteille</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Capacité</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Stock Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-800">
                                    @forelse ($stocksByBouteille as $stock)
                                        <tr class="table-row-hover {{ $stock->total_reste_bouteilles < $threshold ? 'low-stock' : '' }}">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">{{ $stock->nom_bouteille }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $stock->capacite_bouteille }}L</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $stock->total_reste_bouteilles < $threshold ? 'bg-red-400 bg-opacity-20 text-red-400' : 'bg-green-400 bg-opacity-20 text-green-400' }}">
                                                    {{ $stock->total_reste_bouteilles }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                                <p>Aucun type de bouteille disponible pour la date {{ $selectedDate }}</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>