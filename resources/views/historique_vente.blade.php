<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Ventes - ToakaVary</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        .vente-card {
            background-color: #2d2d2d;
            border: 1px solid #3d3d3d;
            transition: transform 0.2s ease;
        }
        .vente-card:hover {
            transform: translateY(-3px);
            border-color: #cdb587;
        }
        .detail-item strong {
            color: #cdb587;
        }
        .btn-primary {
            background-color: #cdb587;
            color: #1b1b1b;
        }
        .btn-primary:hover {
            background-color: #d9c9a3;
        }
        .btn-secondary {
            background-color: #3d3d3d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #4d4d4d;
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
        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i> Déconnexion
    </a>
</div>

<!-- Main Content -->
<main class="flex-grow p-6 overflow-auto">
    <div class="main-content rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold gold-text mb-6">Historique des Ventes</h1>

        <!-- Zone de filtres -->
        <div class="filtre-section bg-[#2d2d2d] p-6 rounded-lg mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div class="filtre-group">
                    <label for="clientFilter" class="block text-gray-300 mb-2">Client</label>
                    <input type="text" id="clientFilter" placeholder="Nom du client" class="input-field w-full p-3 rounded">
                </div>

                <div class="filtre-group">
                    <label for="gammeFilter" class="block text-gray-300 mb-2">Gamme</label>
                    <input type="text" id="gammeFilter" placeholder="Nom de la gamme" class="input-field w-full p-3 rounded">
                </div>

                <div class="filtre-group">
                    <label for="dateDebut" class="block text-gray-300 mb-2">Date début</label>
                    <input type="date" id="dateDebut" class="input-field w-full p-3 rounded">
                </div>

                <div class="filtre-group">
                    <label for="dateFin" class="block text-gray-300 mb-2">Date fin</label>
                    <input type="date" id="dateFin" class="input-field w-full p-3 rounded">
                </div>
            </div>

            <div class="flex space-x-4">
                <button onclick="filtrerVentes()" class="btn-primary py-2 px-6 rounded font-bold flex items-center">
                    <i class="fas fa-filter mr-2"></i> Filtrer
                </button>
                <button onclick="resetFiltres()" class="btn-secondary py-2 px-6 rounded font-bold flex items-center">
                    <i class="fas fa-redo mr-2"></i> Réinitialiser
                </button>
            </div>
        </div>

        <!-- Liste des ventes -->
        <div class="space-y-4" id="ventes-container">
            @foreach($ventes as $vente)
                <div class="vente-card rounded-lg p-6"
                     data-client="{{ strtolower($vente->nom_clients) }}"
                     data-gamme="{{ strtolower($vente->nom_gamme) }}"
                     data-date="{{ \Carbon\Carbon::parse($vente->vente)->format('Y-m-d') }}">

                    <div class="flex flex-wrap justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">{{ $vente->nom_clients }}</h2>
                        <span class="text-gray-400">{{ \Carbon\Carbon::parse($vente->vente)->format('d/m/Y') }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <div class="detail-item">
                            <strong class="block gold-text mb-1">Email</strong>
                            <span>{{ $vente->email_clients }}</span>
                        </div>
                        <div class="detail-item">
                            <strong class="block gold-text mb-1">Gamme</strong>
                            <span>{{ $vente->nom_gamme }}</span>
                        </div>
                        <div class="detail-item">
                            <strong class="block gold-text mb-1">Montant</strong>
                            <span>{{ number_format($vente->montant, 2) }} €</span>
                        </div>
                        <div class="detail-item">
                            <strong class="block gold-text mb-1">Quantité</strong>
                            <span>{{ $vente->quantite }} bouteilles</span>
                        </div>
                        <div class="detail-item">
                            <strong class="block gold-text mb-1">Livraison</strong>
                            <span>{{ \Carbon\Carbon::parse($vente->livraison)->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</main>

<script>
    function filtrerVentes() {
        const client = document.getElementById('clientFilter').value.toLowerCase();
        const gamme = document.getElementById('gammeFilter').value.toLowerCase();
        const dateDebut = document.getElementById('dateDebut').value;
        const dateFin = document.getElementById('dateFin').value;

        document.querySelectorAll('.vente-card').forEach(card => {
            const dataClient = card.dataset.client;
            const dataGamme = card.dataset.gamme;
            const dataDate = card.dataset.date;

            let visible = true;

            if (client && !dataClient.includes(client)) visible = false;
            if (gamme && !dataGamme.includes(gamme)) visible = false;
            if (dateDebut && dataDate < dateDebut) visible = false;
            if (dateFin && dataDate > dateFin) visible = false;

            card.style.display = visible ? 'block' : 'none';
        });
    }

    function resetFiltres() {
        document.getElementById('clientFilter').value = '';
        document.getElementById('gammeFilter').value = '';
        document.getElementById('dateDebut').value = '';
        document.getElementById('dateFin').value = '';
        filtrerVentes();
    }
</script>

</body>
</html>
