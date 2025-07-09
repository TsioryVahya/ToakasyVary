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
        .boxed-import{
            display: flex;
        }
    </style>
    <style>
        body {
            background-color: #0c0c0c;
            color: white;
            font-family: 'Segoe UI', sans-serif;
            min-height: 100vh;
            display: flex;
        }
        .sidebar-custom {
            width: 16rem;
            background-color: #1b1b1b;
            flex-shrink: 0;
        }
        .main-content {
            background-color: #1b1b1b;
            flex-grow: 1;
            padding: 1.5rem;
            overflow: auto;
        }
        .gold-text {
            color: #cdb587;
        }
        .input-field {
            width: 100%;
            padding: 0.75rem 1rem;
            background-color: #2d2d2d;
            border: 1px solid #3d3d3d;
            border-radius: 0.375rem;
            color: white;
            font-size: 0.875rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .input-field:focus {
            outline: none;
            border-color: #cdb587;
            box-shadow: 0 0 0 2px rgba(205, 181, 135, 0.2);
        }
        .input-field::placeholder {
            color: #6b7280;
            opacity: 1;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.625rem 1.25rem;
            border-radius: 0.375rem;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }
        .btn-primary {
            background-color: #cdb587;
            color: #1b1b1b;
        }
        .btn-primary:hover {
            background-color: #d9c9a3;
            transform: translateY(-1px);
        }
        .btn-primary:active {
            background-color: #c3b083;
        }
        .btn-secondary {
            background-color: #3d3d3d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #4d4d4d;
            transform: translateY(-1px);
        }
        .btn-secondary:active {
            background-color: #2d2d2d;
        }
        .btn-success {
            background-color: #34d399;
            color: white;
        }
        .btn-success:hover {
            background-color: #10b981;
        }
        .btn-danger {
            background-color: #f87171;
            color: white;
        }
        .btn-danger:hover {
            background-color: #ef4444;
        }
        .btn-warning {
            background-color: #fbbf24;
            color: #1b1b1b;
        }
        .btn-warning:hover {
            background-color: #f59e42;
        }
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }
        .filter-section {
            background-color: #2d2d2d;
            border: 1px solid #3d3d3d;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .table-container {
            overflow-x: auto;
        }
        .statut-en-attente {
            color: #fbbf24;
        }
        .statut-valide {
            color: #34d399;
        }
        .statut-annule {
            color: #f87171;
        }
        .table-dark {
            background-color: #232323;
            color: #cdb587;
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: #232323;
        }
        .table-striped tbody tr:nth-child(even) {
            background-color: #1b1b1b;
        }
        .alert-success {
            background-color: #34d399;
            color: #1b1b1b;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .form-control, .form-select {
            background-color: #2d2d2d;
            border: 1px solid #3d3d3d;
            color: white;
        }
        .form-control:focus, .form-select:focus {
            background-color: #2d2d2d;
            color: white;
            border-color: #cdb587;
            box-shadow: 0 0 0 0.25rem rgba(205, 181, 135, 0.25);
        }
        .ligne {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1rem;
        }
        .ligne > * {
            flex: 1;
        }
        .ligne > button {
            flex: 0 0 auto;
        }
        .text-danger {
            color: #f87171;
        }
    </style>
</head>
<body class="min-h-screen flex">

<!-- Sidebar -->
@include('slidebar')

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
                            <span>{{ number_format($vente->montant, 2) }} Ar</span>
                        </div>
                        <div class="detail-item">
                            <strong class="block gold-text mb-1">Quantité</strong>
                            <span>{{ $vente->quantite }} bouteilles</span>
                        </div>
                        <div class="detail-item">
                            <strong class="block gold-text mb-1">Livraison</strong>
                            <span>{{ \Carbon\Carbon::parse($vente->livraison)->format('d/m/Y') }}</span>
                        </div>
                        <div class="detail-item">
                            <strong class="block gold-text mb-1">Action</strong>
                            <form action="{{ route('exporter') }}" method="post" target="_blank">
                                @csrf
                                <div class="boxed-import">
                                    <input type="hidden" value="{{ json_encode($vente) }}" name="vente" class="vente-id">
                                    <button class="btn-primary py-2 px-4 rounded font-bold flex items-center">
                                        Importer <i class="fas fa-file-import ml-2"></i>
                                    </button>
                                    <button class="btn-primary py-2 px-4 rounded font-bold flex items-center">
                                        Exporter <i class="fas fa-file-export ml-2"></i>
                                    </button>
                                </div>
                            </form>
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
