<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier - ToakaVary</title>
    <style>
    .calendar-day {
        position: relative;
    }

    .lot-indicator {
        position: absolute;
        width: 4px;
        height: 100%;
        top: 0;
        border-radius: 2px;
    }

    .lot-indicator-1 { left: 2px; }
    .lot-indicator-2 { left: 8px; }
    .lot-indicator-3 { left: 14px; }
    .lot-indicator-4 { left: 20px; }

    .fermentation-bg {
        background: linear-gradient(135deg, #3B82F6 0%, #1E40AF 100%);
    }

    .vieillissement-bg {
        background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    }

    .mixed-bg {
        background: linear-gradient(135deg, #3B82F6 0%, #F59E0B 50%, #8B5CF6 100%);
    }

    .tooltip {
        position: absolute;
        background: rgba(0, 0, 0, 0.8);
        color: white;
        padding: 8px;
        border-radius: 4px;
        font-size: 12px;
        z-index: 1000;
        max-width: 200px;
        white-space: pre-line;
    }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/calendar.js'])
</head>
<body class="bg-light-green font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="bg-primary-green text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold">ToakaVary</h1>
                <nav>
                    <a href="{{ route('home') }}" class="text-white hover:underline">Accueil</a>
                </nav>
            </div>
        </header>

        <main class="flex-grow">
            <div class="container mx-auto px-4 py-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold text-gray-800">Calendrier de Production</h1>
                        <div class="flex items-center space-x-4">
                            <button onclick="previousMonth()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                ← Mois précédent
                            </button>
                            <h2 id="monthYear" class="text-xl font-semibold text-gray-700"></h2>
                            <button onclick="nextMonth()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                Mois suivant →
                            </button>
                        </div>
                    </div>

                    <!-- Légende -->
                    <div class="mb-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-blue-500 rounded"></div>
                            <span class="text-sm">Début fermentation</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-green-500 rounded"></div>
                            <span class="text-sm">Mise en bouteille</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-orange-500 rounded"></div>
                            <span class="text-sm">Début vieillissement</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-4 h-4 bg-purple-500 rounded"></div>
                            <span class="text-sm">Fin vieillissement</span>
                        </div>
                    </div>

                    <!-- Calendrier -->
                    <div class="grid grid-cols-7 gap-2 mb-4">
                        <!-- En-têtes des jours -->
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Dim</div>
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Lun</div>
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Mar</div>
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Mer</div>
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Jeu</div>
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Ven</div>
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Sam</div>
                    </div>

                    <!-- Grille du calendrier -->
                    <div id="calendar-grid" class="grid grid-cols-7 gap-2">
                        <!-- Les jours seront générés par JavaScript -->
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>