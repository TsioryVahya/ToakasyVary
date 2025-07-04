<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Toaka Vary</title>
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
</head>
<body class="bg-gray-950 text-white">
    <!-- Include Sidebar -->
    @include('layouts.sidebar')

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen">
        <!-- Header -->
        <header class="bg-gray-900 border-b border-gray-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-yellow-400">Dashboard</h1>
                    <p class="text-gray-400">Bienvenue dans votre tableau de bord</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <button class="relative p-2 bg-gray-800 rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.89 2 2 2zm6-6v-5c0-3.07-1.64-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.63 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-yellow-400 text-gray-900 text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </button>
                    
                    <!-- User Menu -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-300">{{ date('d/m/Y') }}</span>
                        <div class="w-8 h-8 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center">
                            <span class="text-gray-900 font-semibold text-xs">TV</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Dashboard Content -->
        <main class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Production Mensuelle -->
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 hover:border-yellow-400 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Production Mensuelle</p>
                            <p class="text-2xl font-bold text-yellow-400">12,450L</p>
                            <p class="text-green-400 text-sm mt-1">+8.2% vs mois dernier</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-400 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Ventes du Jour -->
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 hover:border-yellow-400 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Ventes du Jour</p>
                            <p class="text-2xl font-bold text-yellow-400">284,500 Ar</p>
                            <p class="text-green-400 text-sm mt-1">+15.3% vs hier</p>
                        </div>
                        <div class="w-12 h-12 bg-green-400 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stock Disponible -->
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 hover:border-yellow-400 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Stock Disponible</p>
                            <p class="text-2xl font-bold text-yellow-400">8,750L</p>
                            <p class="text-orange-400 text-sm mt-1">Réapprovisionnement nécessaire</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-400 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 7h-3V6a4 4 0 0 0-4-4h-2a4 4 0 0 0-4 4v1H4a1 1 0 0 0-1 1v11a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V8a1 1 0 0 0-1-1z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Commandes en Attente -->
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800 hover:border-yellow-400 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">Commandes en Attente</p>
                            <p class="text-2xl font-bold text-yellow-400">23</p>
                            <p class="text-red-400 text-sm mt-1">5 urgentes</p>
                        </div>
                        <div class="w-12 h-12 bg-red-400 bg-opacity-20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Recent Activity -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Production Chart -->
                <div class="lg:col-span-2 bg-gray-900 rounded-xl p-6 border border-gray-800">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-yellow-400">Production des 7 derniers jours</h3>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 bg-yellow-400 text-gray-900 rounded-lg text-sm font-medium">7j</button>
                            <button class="px-3 py-1 bg-gray-800 text-gray-400 rounded-lg text-sm">30j</button>
                            <button class="px-3 py-1 bg-gray-800 text-gray-400 rounded-lg text-sm">1a</button>
                        </div>
                    </div>
                    <div class="h-64 flex items-end justify-between space-x-2">
                        <div class="flex flex-col items-center">
                            <div class="w-12 bg-yellow-400 rounded-t-lg" style="height: 80px;"></div>
                            <span class="text-xs text-gray-400 mt-2">Lun</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 bg-yellow-400 rounded-t-lg" style="height: 120px;"></div>
                            <span class="text-xs text-gray-400 mt-2">Mar</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 bg-yellow-400 rounded-t-lg" style="height: 60px;"></div>
                            <span class="text-xs text-gray-400 mt-2">Mer</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 bg-yellow-400 rounded-t-lg" style="height: 140px;"></div>
                            <span class="text-xs text-gray-400 mt-2">Jeu</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 bg-yellow-400 rounded-t-lg" style="height: 100px;"></div>
                            <span class="text-xs text-gray-400 mt-2">Ven</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 bg-yellow-400 rounded-t-lg" style="height: 90px;"></div>
                            <span class="text-xs text-gray-400 mt-2">Sam</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div class="w-12 bg-yellow-400 rounded-t-lg" style="height: 110px;"></div>
                            <span class="text-xs text-gray-400 mt-2">Dim</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800">
                    <h3 class="text-lg font-semibold text-yellow-400 mb-4">Commandes Récentes</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-gray-800 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-white">Commande #1234</p>
                                <p class="text-xs text-gray-400">Client: Hôtel Carlson</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-yellow-400">250L</p>
                                <span class="text-xs bg-green-400 bg-opacity-20 text-green-400 px-2 py-1 rounded">Livré</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-800 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-white">Commande #1235</p>
                                <p class="text-xs text-gray-400">Client: Restaurant Sakamanga</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-yellow-400">180L</p>
                                <span class="text-xs bg-yellow-400 bg-opacity-20 text-yellow-400 px-2 py-1 rounded">En cours</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-gray-800 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-white">Commande #1236</p>
                                <p class="text-xs text-gray-400">Client: Bar Panorama</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-yellow-400">120L</p>
                                <span class="text-xs bg-red-400 bg-opacity-20 text-red-400 px-2 py-1 rounded">Urgent</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Production Status and Alerts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Production Status -->
                <div class="bg-gray-900 rounded-xl p-6 border border-gray-800">
                    <h3 class="text-lg font-semibold text-yellow-400 mb-4">État de la Production</h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                <span class="text-sm text-gray-300">Cuve A - Fermentation</span>
                            </div>
                        </div>
                    </div>
                </div>

        <main class="flex-grow container mx-auto p-6">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-primary-green mb-4">Bienvenue, {{ auth()->user()->name }} !</h2>
                <a href="/statForm"><p class="text-gray-700">stat vente</p></a>
                <a href="{{ route('historique_vente') }}"><p class="text-gray-700">Historique vente</p></a>
                <a href="{{ route('production.calendar') }}"><p class="text-gray-700">Calendrier</p></a>
                <p class="text-gray-700">Ceci est la page d'accueil de ToakaVary. Vous êtes maintenant connecté.</p>
            </div>
        </main>
    </div>
</body>
</html>