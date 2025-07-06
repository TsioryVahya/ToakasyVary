<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lots Production - ToakaVary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/production.js'])
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: none;
            border-radius: 8px;
            width: 80%;
            max-width: 700px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        .close:hover {
            color: #000;
        }
        .calculated-date {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 8px 12px;
            color: #6b7280;
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
        }
        .filter-tab.active {
            background-color: #3b82f6;
            color: white;
        }
        .filter-tab:not(.active) {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        .filter-tab:hover {
            background-color: #e5e7eb;
        }
        .filter-tab.active:hover {
            background-color: #2563eb;
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
    </style>
</head>
<body class="bg-gray-100">
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

    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-primary-green">ToakaVary</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary-green">Accueil</a>
                    <a href="{{ route('production.calendar') }}" class="text-gray-700 hover:text-primary-green">Calendrier</a>
                    <a href="{{ route('lot_productions.index') }}" class="text-primary-green font-semibold">Production</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Lots de Production</h1>
                <button onclick="openModal()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Nouveau Lot
                </button>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filtres par statut -->
            <div class="mb-6">
                <div class="flex flex-wrap items-center gap-2 bg-gray-50 p-3 rounded-lg">
                    <span class="text-sm font-medium text-gray-700 mr-2">Filtrer par statut:</span>
                    
                    <a href="{{ route('lot_productions.index', ['filter' => 'all']) }}" 
                       class="filter-tab {{ ($filter ?? 'all') === 'all' ? 'active' : '' }}">
                        Tous
                        <span class="badge">{{ $counts['all'] ?? 0 }}</span>
                    </a>
                    
                    <a href="{{ route('lot_productions.index', ['filter' => 'fermentation']) }}" 
                       class="filter-tab {{ ($filter ?? '') === 'fermentation' ? 'active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        En Fermentation
                        <span class="badge">{{ $counts['fermentation'] ?? 0 }}</span>
                    </a>
                    
                    <a href="{{ route('lot_productions.index', ['filter' => 'vieillissement']) }}" 
                       class="filter-tab {{ ($filter ?? '') === 'vieillissement' ? 'active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-orange-500"></span>
                        En Vieillissement
                        <span class="badge">{{ $counts['vieillissement'] ?? 0 }}</span>
                    </a>
                    
                    <a href="{{ route('lot_productions.index', ['filter' => 'commercialise']) }}" 
                       class="filter-tab {{ ($filter ?? '') === 'commercialise' ? 'active' : '' }}">
                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                        Commercialisé
                        <span class="badge">{{ $counts['commercialise'] ?? 0 }}</span>
                    </a>
                </div>
            </div>

            <!-- Titre du filtre actif -->
            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-700">
                    @switch($filter ?? 'all')
                        @case('fermentation')
                            Lots en Fermentation
                            @break
                        @case('vieillissement')
                            Lots en Vieillissement
                            @break
                        @case('commercialise')
                            Lots Commercialisés
                            @break
                        @default
                            Tous les Lots
                    @endswitch
                    <span class="text-sm font-normal text-gray-500">({{ $lots->total() }} résultat{{ $lots->total() > 1 ? 's' : '' }})</span>
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gamme</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type Bouteille</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date Début</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mise en Bouteille</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Commercialisation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bouteilles</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                          </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($lots as $lot)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $lot->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lot->gamme->nom ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lot->typeBouteille->nom ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lot->date_debut }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lot->date_mise_en_bouteille }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lot->date_commercialisation }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $lot->nombre_bouteilles ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($lot->date_commercialisation && \Carbon\Carbon::parse($lot->date_commercialisation)->isPast())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Commercialisé
                                    </span>
                                @elseif($lot->date_mise_en_bouteille && \Carbon\Carbon::parse($lot->date_mise_en_bouteille)->isPast())
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                        En vieillissement
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        En fermentation
                                    </span>
                                @endif
                            </td>
                            
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
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
                <div class="mt-4">
                    {{ $lots->appends(['filter' => $filter ?? 'all'])->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Modal de création/édition -->
    <div id="lotModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-800 mb-6">Nouveau Lot de Production</h2>
            
            <form id="lotForm" action="{{ route('lot_productions.store') }}" method="POST" class="space-y-6">
                @csrf
                <div id="methodField"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="id_gamme" class="block text-sm font-medium text-gray-700 mb-2">
                            Gamme * <span class="text-xs text-gray-500">(affecte les durées de production)</span>
                        </label>
                        <select name="id_gamme" id="id_gamme" required onchange="calculateDates()"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionnez une gamme</option>
                            @foreach($gammes as $gamme)
                                <option value="{{ $gamme->id }}" 
                                        data-fermentation="{{ $gamme->fermentation_jours }}"
                                        data-vieillissement="{{ $gamme->vieillissement_jours }}">
                                    {{ $gamme->nom }} ({{ $gamme->fermentation_jours }}j fermentation + {{ $gamme->vieillissement_jours }}j vieillissement)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="id_bouteille" class="block text-sm font-medium text-gray-700 mb-2">
                            Type de Bouteille *
                        </label>
                        <select name="id_bouteille" id="id_bouteille" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Sélectionnez un type</option>
                            @foreach($typeBouteilles as $type)
                                <option value="{{ $type->id }}">{{ $type->nom }} ({{ $type->capacite }}L)</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="date_debut" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de Début *
                        </label>
                        <input type="date" name="date_debut" id="date_debut" required onchange="calculateDates()"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="nombre_bouteilles" class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de Bouteilles
                        </label>
                        <input type="number" name="nombre_bouteilles" id="nombre_bouteilles" min="1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Annuler
                    </button>
                    <button type="submit" id="submitBtn"
                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        Créer le Lot
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>