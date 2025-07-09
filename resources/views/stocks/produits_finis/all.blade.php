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
@include('slidebar')
<!-- Main Content -->
<main class="flex-grow p-6 overflow-auto">
    <div class="main-content rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold gold-text mb-6">Aperçu du Stock</h1>
        <div class="date-filter">
            <form method="GET" action="{{ url()->current() }}">
                <label for="date">Filtrer par date:</label>
                <input class ="bg-black"type="date" id="date" name="date" value="{{ $selectedDate }}">
                <button type="submit">Filtrer</button>
                <span class="current-date">Date actuelle: {{ $selectedDate }}</span>
            </form>
        </div>


        <!-- @if ($notification)
            <div class="notification flex items-start">
                <i class="fas fa-exclamation-triangle mr-3 mt-1"></i>
                <div>{!! $notification !!}</div>
            </div>
        @endif -->

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
                    <tr><td colspan="9">Aucun lot trouvé pour la date {{ $selectedDate }}!</td></tr>
                @endforelse
                </tbody>
            </table>
    <!-- Date Filter Section -->
    
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
                <tr><td colspan="4">Pas de combinaison gamme-bouteille disponible pour la date {{ $selectedDate }}</td></tr>
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
                <tr><td colspan="2">Pas de gamme disponible pour la date {{ $selectedDate }}</td></tr>
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
                    <tr><td colspan="3">Pas de type de bouteille disponible pour la date {{ $selectedDate }}</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</main>
</body>
</html>
