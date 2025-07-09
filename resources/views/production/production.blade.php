<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lots Production - ToakaVary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/production.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e6c6f2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0c0c0c;
            color: white;
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar-custom {
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
            text-decoration: none;
        }
        .btn-primary {
            background-color: #cdb587;
            color: #1b1b1b;
        }
        .btn-primary:hover {
            background-color: #d9c9a3;
            transform: translateY(-1px);
        }
        .btn-secondary {
            background-color: #3d3d3d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #4d4d4d;
            transform: translateY(-1px);
        }
        .btn-success {
            background-color: #34d399;
            color: white;
        }
        .btn-success:hover {
            background-color: #10b981;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.7);
        }
        .modal-content {
            background-color: #1b1b1b;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #3d3d3d;
            border-radius: 8px;
            width: 80%;
            max-width: 700px;
            max-height: 80vh;
            overflow-y: auto;
            color: white;
        }
        .close {
            color: #cdb587;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: #d9c9a3;
        }
        .calculated-date {
            background-color: #2d2d2d;
            border: 1px solid #3d3d3d;
            border-radius: 6px;
            padding: 8px 12px;
            color: #cdb587;
            font-style: italic;
        }
        .filter-tab {
            padding: 0.5rem 1rem;
            margin: 0 0.25rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
        }
        .filter-tab.active {
            background-color: #cdb587;
            color: #1b1b1b;
        }
        .filter-tab:not(.active) {
            background-color: #2d2d2d;
            color: #cdb587;
        }
        .filter-tab:hover {
            background-color: #3d3d3d;
        }
        .filter-tab.active:hover {
            background-color: #d9c9a3;
        }
        .badge {
            background-color: #ef4444;
            color: white;
            border-radius: 50%;
            padding: 0.125rem 0.375rem;
            font-size: 0.75rem;
            font-weight: 600;
            min-width: 1.25rem;
            text-align: center;
        }
        .filter-tab.active .badge {
            background-color: rgba(255, 255, 255, 0.3);
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
        .alert-error {
            background-color: #ef4444;
            color: white;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            text-align: center;
        }
        .status-fermentation {
            background-color: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }
        .status-vieillissement {
            background-color: rgba(249, 115, 22, 0.2);
            color: #f97316;
        }
        .status-commercialise {
            background-color: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }
        .status-indicator {
            width: 0.5rem;
            height: 0.5rem;
            border-radius: 50%;
            display: inline-block;
            margin-right: 0.5rem;
        }
        .indicator-blue {
            background-color: #3b82f6;
        }
        .indicator-orange {
            background-color: #f97316;
        }
        .indicator-green {
            background-color: #22c55e;
        }
    </style>
</head>
<body class="min-h-screen flex">
    <!-- Données JSON pour JavaScript -->
    <script id="gamme-data" type="application/json">@json($gammes)</script>
    <script id="type-bouteille-data" type="application/json">@json($typeBouteilles)</script>

    <!-- Configuration des routes pour JavaScript -->
    <script>
        window.routes = {
            store: "{{ route('lot_productions.store') }}",
            update: "{{ route('lot_productions.update', ':id') }}",
            data: "{{ route('lot_productions.data', ':id') }}"
        };
    </script>

    <!-- Sidebar -->
    @include('slidebar')

    <!-- Main Content -->
    <main class="flex-grow p-6 overflow-auto">
        <div class="main-content rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold gold-text">Lots de Production</h1>
                <button onclick="openModal()" class="btn btn-success">
                    <i class="fas fa-plus mr-2"></i>Nouveau Lot
                </button>
            </div>

            @if (session('success'))
                <div class="alert-success">
                    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert-error">
                    <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                </div>
            @endif

            <!-- Filtres par statut -->
            <div class="filter-section">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-sm font-medium gold-text mr-2">Filtrer par statut:</span>

                    <a href="{{ route('lot_productions.index', ['filter' => 'all']) }}"
                       class="filter-tab {{ ($filter ?? 'all') === 'all' ? 'active' : '' }}">
                        <i class="fas fa-list mr-1"></i>Tous
                        <span class="badge">{{ $counts['all'] ?? 0 }}</span>
                    </a>

                    <a href="{{ route('lot_productions.index', ['filter' => 'fermentation']) }}"
                       class="filter-tab {{ ($filter ?? '') === 'fermentation' ? 'active' : '' }}">
                        <span class="status-indicator indicator-blue"></span>
                        En Fermentation
                        <span class="badge">{{ $counts['fermentation'] ?? 0 }}</span>
                    </a>

                    <a href="{{ route('lot_productions.index', ['filter' => 'vieillissement']) }}"
                       class="filter-tab {{ ($filter ?? '') === 'vieillissement' ? 'active' : '' }}">
                        <span class="status-indicator indicator-orange"></span>
                        En Vieillissement
                        <span class="badge">{{ $counts['vieillissement'] ?? 0 }}</span>
                    </a>

                    <a href="{{ route('lot_productions.index', ['filter' => 'commercialise']) }}"
                       class="filter-tab {{ ($filter ?? '') === 'commercialise' ? 'active' : '' }}">
                        <span class="status-indicator indicator-green"></span>
                        Commercialisé
                        <span class="badge">{{ $counts['commercialise'] ?? 0 }}</span>
                    </a>
                </div>
            </div>

            <!-- Titre du filtre actif -->
            <div class="mb-4">
                <h2 class="text-xl font-semibold gold-text">
                    @switch($filter ?? 'all')
                        @case('fermentation')
                            <i class="fas fa-flask mr-2"></i>Lots en Fermentation
                            @break
                        @case('vieillissement')
                            <i class="fas fa-wine-bottle mr-2"></i>Lots en Vieillissement
                            @break
                        @case('commercialise')
                            <i class="fas fa-store mr-2"></i>Lots Commercialisés
                            @break
                        @default
                            <i class="fas fa-boxes mr-2"></i>Tous les Lots
                    @endswitch
                    <span class="text-sm font-normal text-gray-400">({{ $lots->total() }} résultat{{ $lots->total() > 1 ? 's' : '' }})</span>
                </h2>
            </div>

            <div class="table-container">
                <table class="w-full border-collapse table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                            <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Gamme</th>
                            <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Type Bouteille</th>
                            <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Date Début</th>
                            <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Mise en Bouteille</th>
                            <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Commercialisation</th>
                            <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Bouteilles</th>
                            <th class="py-3 px-4 text-left text-xs font-medium uppercase tracking-wider">Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lots as $lot)
                        <tr>
                            <td class="py-4 px-4 font-medium text-white">
                                <i class="fas fa-hashtag mr-1 text-gray-400"></i>{{ $lot->id }}
                            </td>
                            <td class="py-4 px-4 text-gray-300">
                                <i class="fas fa-wine-glass mr-1 text-gray-400"></i>{{ $lot->gamme->nom ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-4 text-gray-300">
                                <i class="fas fa-wine-bottle mr-1 text-gray-400"></i>{{ $lot->typeBouteille->nom ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-4 text-gray-300">
                                <i class="fas fa-calendar-alt mr-1 text-gray-400"></i>{{ $lot->date_debut }}
                            </td>
                            <td class="py-4 px-4 text-gray-300">
                                <i class="fas fa-calendar-check mr-1 text-gray-400"></i>{{ $lot->date_mise_en_bouteille }}
                            </td>
                            <td class="py-4 px-4 text-gray-300">
                                <i class="fas fa-calendar-day mr-1 text-gray-400"></i>{{ $lot->date_commercialisation }}
                            </td>
                            <td class="py-4 px-4 text-gray-300">
                                <i class="fas fa-boxes mr-1 text-gray-400"></i>{{ $lot->nombre_bouteilles ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-4">
                                @if($lot->date_commercialisation && \Carbon\Carbon::parse($lot->date_commercialisation)->isPast())
                                    <span class="status-badge status-commercialise">
                                        <i class="fas fa-check-circle mr-1"></i>Commercialisé
                                    </span>
                                @elseif($lot->date_mise_en_bouteille && \Carbon\Carbon::parse($lot->date_mise_en_bouteille)->isPast())
                                    <span class="status-badge status-vieillissement">
                                        <i class="fas fa-hourglass-half mr-1"></i>En vieillissement
                                    </span>
                                @else
                                    <span class="status-badge status-fermentation">
                                        <i class="fas fa-flask mr-1"></i>En fermentation
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-8 px-4 text-center text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-4 block"></i>
                                @switch($filter ?? 'all')
                                    @case('fermentation')
                                        Aucun lot en fermentation trouvé.
                                        @break
                                    @case('vieillissement')
                                        Aucun lot en vieillissement trouvé.
                                        @break
                                    @case('commercialise')
                                        Aucun lot commercialisé trouvé.
                                        @break
                                    @default
                                        Aucun lot de production trouvé.
                                @endswitch
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($lots->hasPages())
                <div class="mt-6 flex justify-center">
                    {{ $lots->appends(['filter' => $filter ?? 'all'])->links() }}
                </div>
            @endif
        </div>
    </main>

    <!-- Modal de création/édition -->
    <div id="lotModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle" class="text-2xl font-bold gold-text mb-6">
                <i class="fas fa-plus-circle mr-2"></i>Nouveau Lot de Production
            </h2>

            <form id="lotForm" action="{{ route('lot_productions.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="gamme_id" class="block text-sm font-medium gold-text mb-1">
                            <i class="fas fa-wine-glass mr-1"></i>Gamme
                        </label>
                        <select id="gamme_id" name="gamme_id" class="input-field" required>
                            <option value="">Sélectionner une gamme</option>
                            @foreach($gammes as $gamme)
                                <option value="{{ $gamme->id }}">{{ $gamme->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="type_bouteille_id" class="block text-sm font-medium gold-text mb-1">
                            <i class="fas fa-wine-bottle mr-1"></i>Type de Bouteille
                        </label>
                        <select id="type_bouteille_id" name="type_bouteille_id" class="input-field" required>
                            <option value="">Sélectionner un type</option>
                            @foreach($typeBouteilles as $type)
                                <option value="{{ $type->id }}">{{ $type->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="date_debut" class="block text-sm font-medium gold-text mb-1">
                            <i class="fas fa-calendar-alt mr-1"></i>Date de Début
                        </label>
                        <input type="date" id="date_debut" name="date_debut" class="input-field" required>
                    </div>

                    <div>
                        <label for="nombre_bouteilles" class="block text-sm font-medium gold-text mb-1">
                            <i class="fas fa-boxes mr-1"></i>Nombre de Bouteilles
                        </label>
                        <input type="number" id="nombre_bouteilles" name="nombre_bouteilles" class="input-field" min="1" required>
                    </div>

                    <div>
                        <label for="date_mise_en_bouteille" class="block text-sm font-medium gold-text mb-1">
                            <i class="fas fa-calendar-check mr-1"></i>Date Mise en Bouteille
                        </label>
                        <input type="date" id="date_mise_en_bouteille" name="date_mise_en_bouteille" class="calculated-date" readonly>
                    </div>

                    <div>
                        <label for="date_commercialisation" class="block text-sm font-medium gold-text mb-1">
                            <i class="fas fa-calendar-day mr-1"></i>Date Commercialisation
                        </label>
                        <input type="date" id="date_commercialisation" name="date_commercialisation" class="calculated-date" readonly>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="closeModal()" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>Annuler
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Fonctions pour le modal
        function openModal() {
            document.getElementById('lotModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('lotModal').style.display = 'none';
        }

        // Fermer le modal si on clique à l'extérieur
        window.onclick = function(event) {
            const modal = document.getElementById('lotModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Calcul automatique des dates
        document.addEventListener('DOMContentLoaded', function() {
            const dateDebutInput = document.getElementById('date_debut');
            const gammeSelect = document.getElementById('gamme_id');
            const dateMiseEnBouteilleInput = document.getElementById('date_mise_en_bouteille');
            const dateCommercialisationInput = document.getElementById('date_commercialisation');

            function calculateDates() {
                const dateDebut = dateDebutInput.value;
                const gammeId = gammeSelect.value;
                
                if (dateDebut && gammeId) {
                    const gammes = JSON.parse(document.getElementById('gamme-data').textContent);
                    const selectedGamme = gammes.find(g => g.id == gammeId);
                    
                    if (selectedGamme) {
                        const startDate = new Date(dateDebut);
                        
                        // Calculer la date de mise en bouteille
                        const miseEnBouteilleDate = new Date(startDate);
                        miseEnBouteilleDate.setDate(miseEnBouteilleDate.getDate() + selectedGamme.duree_fermentation);
                        dateMiseEnBouteilleInput.value = miseEnBouteilleDate.toISOString().split('T')[0];
                        
                        // Calculer la date de commercialisation
                        const commercialisationDate = new Date(miseEnBouteilleDate);
                        commercialisationDate.setDate(commercialisationDate.getDate() + selectedGamme.duree_vieillissement);
                        dateCommercialisationInput.value = commercialisationDate.toISOString().split('T')[0];
                    }
                }
            }

            dateDebutInput.addEventListener('change', calculateDates);
            gammeSelect.addEventListener('change', calculateDates);
        });
    </script>
</body>
</html>