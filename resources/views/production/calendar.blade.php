<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier - ToakaVary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e6c6f2.js" crossorigin="anonymous"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/calendar.js'])
    <style>
        .event-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 3px;
        }
        #calendar-grid > div {
            min-height: 80px;
            border: 1px solid #374151;
            padding: 0.5rem;
        }
        #calendar-container {
            max-width: 1200px;
            margin: 0 auto;
        }
    </style>
</head>
<body class="bg-[#0c0c0c] text-white font-sans antialiased min-h-screen flex">

<!-- Sidebar -->
<div class="w-64 bg-[#1b1b1b] text-white p-5 flex flex-col">
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
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 bg-[#cdb587] text-black hover:bg-opacity-80 transition">
            <i class="fas fa-calendar mr-3 w-5 text-center"></i> Calendrier
        </a>
        <a href="/statForm" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>Statistiques des ventes
        </a>
        <a href="{{ route('historique_vente') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-chart-bar mr-3 w-5 text-center"></i> Historique Vente
        </a>
    </nav>
    <a href="/login" class="flex items-center py-2 px-3 rounded hover:bg-opacity-20 hover:bg-white transition mt-auto">
        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i> Déconnexion
    </a>
</div>

<!-- Main -->
<main class="flex-grow p-6">
    <div class="bg-[#1b1b1b] rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-[#cdb587]">Calendrier de Production</h1>
            <div class="flex items-center space-x-4">
                <button onclick="previousMonth()" class="bg-[#2d2d2d] hover:bg-[#3d3d3d] text-white px-4 py-2 rounded transition">
                    <i class="fas fa-chevron-left mr-2"></i>Mois précédent
                </button>
                <h2 id="monthYear" class="text-xl font-semibold text-white px-4"></h2>
                <button onclick="nextMonth()" class="bg-[#2d2d2d] hover:bg-[#3d3d3d] text-white px-4 py-2 rounded transition">
                    Mois suivant<i class="fas fa-chevron-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- Légende -->
        <div class="mb-6 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-gray-300 bg-[#2d2d2d] p-4 rounded-lg">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-gray-500 rounded"></div>
                <span>Début production ▶</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-blue-300 rounded"></div>
                <span>Début fermentation ▶</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-blue-500 rounded"></div>
                <span>Fin fermentation/Mise en bouteille ⏹</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-orange-300 rounded"></div>
                <span>Début vieillissement ▶</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded"></div>
                <span>Fin vieillissement/Commercialisation ⏹</span>
            </div>
        </div>

        <!-- En-têtes des jours -->
        <div class="grid grid-cols-7 gap-2 mb-4">
            <div class="p-3 text-center font-semibold text-gray-400 bg-[#2d2d2d] rounded">Dim</div>
            <div class="p-3 text-center font-semibold text-gray-400 bg-[#2d2d2d] rounded">Lun</div>
            <div class="p-3 text-center font-semibold text-gray-400 bg-[#2d2d2d] rounded">Mar</div>
            <div class="p-3 text-center font-semibold text-gray-400 bg-[#2d2d2d] rounded">Mer</div>
            <div class="p-3 text-center font-semibold text-gray-400 bg-[#2d2d2d] rounded">Jeu</div>
            <div class="p-3 text-center font-semibold text-gray-400 bg-[#2d2d2d] rounded">Ven</div>
            <div class="p-3 text-center font-semibold text-gray-400 bg-[#2d2d2d] rounded">Sam</div>
        </div>

        <!-- Grille du calendrier -->
        <div id="calendar-grid" class="grid grid-cols-7 gap-2">
            <!-- Le JavaScript remplira cette grille -->
        </div>
    </div>
</main>

</body>
</html>
