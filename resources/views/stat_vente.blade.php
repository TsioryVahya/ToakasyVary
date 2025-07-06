<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques de Vente - ToakaVary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e6c6f2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/">
    <style>

        body {
            background-color: #0c0c0c;
            color: white;
            font-family: sans-serif;
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
        .input-field {
            background-color: #2d2d2d;
            border: 1px solid #3d3d3d;
            color: white;
        }
        .input-field:focus {
            outline: none;
            border-color: #cdb587;
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
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
<body class="min-h-screen flex">

<!-- Sidebar -->
<div class="w-64 sidebar-custom text-white p-5 flex flex-col">
    <img src="../img/logo_rhum_rice.png" alt="logo" class="w-48 mx-auto mb-8">
    <nav class="flex-1">
        <a href="/home" class="flex items-center py-2 px-3 rounded mb-2 bg-gray-800 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>
            Dashboard
        </a>
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-industry mr-3 w-5 text-center"></i>
            Production
        </a>
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-boxes mr-3 w-5 text-center"></i>
            Stocks
        </a>
        <a href="{{ route('production.calendar') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
            Calendrier
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
<main class="flex-grow p-6 flex items-center justify-center">
    <div class="main-content rounded-lg shadow-lg p-8 form-container">
        <h1 class="text-3xl font-bold gold-text mb-8 text-center">Statistiques de Vente</h1>

        <form action="{{ route('stat_vente') }}" method="POST" class="space-y-6">
            @csrf

            <div class="space-y-3">
                <label for="date_debut" class="block text-gray-300">Date de début :</label>
                <input type="date" name="date_debut" id="date_debut"
                       class="input-field w-full p-3 rounded focus:ring-2 focus:ring-yellow-500" required>
            </div>

            <div class="space-y-3">
                <label for="date_fin" class="block text-gray-300">Date de fin :</label>
                <input type="date" name="date_fin" id="date_fin"
                       class="input-field w-full p-3 rounded focus:ring-2 focus:ring-yellow-500" required>
            </div>

            <div class="pt-4 text-center">
                <button type="submit"
                        class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-3 px-8 rounded transition duration-300 flex items-center justify-center mx-auto">
                    <i class="fas fa-chart-bar mr-2"></i> Générer les statistiques
                </button>
            </div>
        </form>
    </div>
</main>

</body>
</html>
