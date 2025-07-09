<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat de la prévision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e6c6f2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
        .content-container {
            flex-grow: 1;
            overflow: auto;
        }
        .main-content {
            background-color: #1b1b1b;
            padding: 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 1.5rem;
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
        .btn-danger {
            background-color: #ef4444;
            color: white;
        }
        .btn-danger:hover {
            background-color: #dc2626;
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
        .status-en-attente {
            background-color: rgba(249, 115, 22, 0.2);
            color: #f97316;
        }
        .status-valide {
            background-color: rgba(34, 197, 94, 0.2);
            color: #22c55e;
        }
        .status-annule {
            background-color: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #3d3d3d;
        }
        th {
            background-color: #2d2d2d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
        }
        a {
            color: #cdb587;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body class="min-h-screen flex">
    <!-- Sidebar -->
    @include('slidebar')

    <!-- Content Container -->
    <div class="content-container">
        <main class="flex-grow p-6 overflow-auto">
            <div class="main-content">
                <h1 class="text-3xl font-bold gold-text mb-6"><i class="fas fa-chart-bar mr-2"></i>Résultat de la prévision</h1>

                @if ($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <ul class="list-disc list-inside mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="filter-section">
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold gold-text mb-2"><i class="fas fa-boxes mr-2"></i>Stocks disponibles</h2>
                        <div class="table-container">
                            <table class="w-full border-collapse table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Gamme</th>
                                        <th>Type de bouteille</th>
                                        <th>Quantité disponible</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($result['stocks_disponibles'] as $gammeId => $stock)
                                        <tr>
                                            <td class="py-4 px-4 text-gray-300"><i class="fas fa-wine-glass mr-1 text-gray-400"></i>{{ $stock['gamme'] }}</td>
                                            <td class="py-4 px-4 text-gray-300"><i class="fas fa-wine-bottle mr-1 text-gray-400"></i>{{ $stock['type_bouteille'] }}</td>
                                            <td class="py-4 px-4 text-gray-300"><i class="fas fa-boxes mr-1 text-gray-400"></i>{{ $stock['quantite'] }} bouteilles</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xl font-semibold gold-text mb-2"><i class="fas fa-check-circle mr-2"></i>Faisabilité</h2>
                        <div class="table-container">
                            <table class="w-full border-collapse table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Gamme</th>
                                        <th>Type de bouteille</th>
                                        <th>Quantité demandée</th>
                                        <th>Date de disponibilité</th>
                                        <th>Date de livraison</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($result['faisabilite'] as $gammeId => $status)
                                        <tr>
                                            <td class="py-4 px-4 text-gray-300"><i class="fas fa-wine-glass mr-1 text-gray-400"></i>{{ $result['stocks_disponibles'][$gammeId]['gamme'] }}</td>
                                            <td class="py-4 px-4 text-gray-300"><i class="fas fa-wine-bottle mr-1 text-gray-400"></i>{{ $result['stocks_disponibles'][$gammeId]['type_bouteille'] }}</td>
                                            <td class="py-4 px-4 text-gray-300"><i class="fas fa-boxes mr-1 text-gray-400"></i>{{ $result['quantite_demandee'][$gammeId] }}</td>
                                            <td class="py-4 px-4 text-gray-300"><i class="fas fa-calendar-alt mr-1 text-gray-400"></i>{{ $result['date_disponibilite'][$gammeId] }}</td>
                                            <td class="py-4 px-4 text-gray-300"><i class="fas fa-calendar-check mr-1 text-gray-400"></i>{{ $result['date_livraison'][$gammeId] }}</td>
                                            <td class="py-4 px-4">
                                                <span class="status-badge @if($status == 'En attente') status-en-attente @elseif($status == 'Valide') status-valide @else status-annule @endif">
                                                    <i class="fas @if($status == 'En attente') fa-hourglass-half @elseif($status == 'Valide') fa-check-circle @else fa-times-circle @endif mr-1"></i>{{ $status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xl font-semibold gold-text mb-2"><i class="fas fa-exclamation-triangle mr-2"></i>Stocks manquants</h2>
                        @if (empty($result['stocks_manquants']))
                            <p class="text-gray-300"><i class="fas fa-check-circle mr-2"></i>Aucun stock manquant.</p>
                        @else
                            <div class="mb-4">
                                @foreach ($result['manquant'] as $gammeId => $status)
                                    <p class="text-gray-300"><i class="fas fa-exclamation-circle mr-2"></i>Gamme "{{ $result['stocks_disponibles'][$gammeId]['gamme'] }}": {{ $status }} bouteilles manquantes à produire</p>
                                @endforeach
                            </div>
                            <div class="table-container">
                                <table class="w-full border-collapse table-striped">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Matière</th>
                                            <th>Quantité manquante</th>
                                            <th>Gamme</th>
                                            <th>Type de bouteille</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($result['stocks_manquants'] as $manque)
                                            <tr>
                                                <td class="py-4 px-4 text-gray-300"><i class="fas fa-cubes mr-1 text-gray-400"></i>{{ $manque['matiere'] }}</td>
                                                <td class="py-4 px-4 text-gray-300"><i class="fas fa-boxes mr-1 text-gray-400"></i>{{ $manque['quantite_manquante'] }} unités</td>
                                                <td class="py-4 px-4 text-gray-300"><i class="fas fa-wine-glass mr-1 text-gray-400"></i>{{ $manque['gamme'] }}</td>
                                                <td class="py-4 px-4 text-gray-300"><i class="fas fa-wine-bottle mr-1 text-gray-400"></i>{{ $manque['type_bouteille'] }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xl font-semibold gold-text mb-2"><i class="fas fa-save mr-2"></i>Enregistrer la commande</h2>
                        <form action="{{ route('commandes.store') }}" method="POST" onsubmit="return validateLotsAllocation(event)">
                            @csrf
                            
                            <input type="hidden" name="commande" value="{{ json_encode($result['commande']) }}">

                            <h3 class="text-lg font-medium mb-2 gold-text"><i class="fas fa-list mr-2"></i>Allocation des lots</h3>
                            @foreach ($result['lots_disponibles'] as $gammeId => $lots)
                                <div class="mb-4">
                                    <h4 class="text-md font-semibold gold-text">
                                        <i class="fas fa-wine-glass mr-1"></i>{{ $result['stocks_disponibles'][$gammeId]['gamme'] }} - {{ $result['stocks_disponibles'][$gammeId]['type_bouteille'] }}
                                    </h4>
                                    <p class="text-gray-300"><i class="fas fa-boxes mr-1 text-gray-400"></i>Quantité demandée: {{ $result['quantite_demandee'][$gammeId] }} bouteilles</p>
                                    @if (!empty($lots))
                                        <div class="table-container">
                                            <table class="w-full border-collapse table-striped">
                                                <thead class="table-dark">
                                                    <tr>
                                                        <th>Lot ID</th>
                                                        <th>Quantité disponible</th>
                                                        <th>Quantité à prendre</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($lots as $lot)
                                                        <tr>
                                                            <td class="py-4 px-4 text-gray-300"><i class="fas fa-hashtag mr-1 text-gray-400"></i>{{ $lot['id_lot'] }}</td>
                                                            <td class="py-4 px-4 text-gray-300"><i class="fas fa-boxes mr-1 text-gray-400"></i>{{ $lot['quantite_disponible'] }} bouteilles</td>
                                                            <td class="py-4 px-4">
                                                                <input type="number" name="lots_allocation[{{ $gammeId }}][{{ $lot['id_lot'] }}]"
                                                                       min="0" max="{{ $lot['quantite_disponible'] }}"
                                                                       value="0" class="input-field lot-quantity"
                                                                       data-gamme-id="{{ $gammeId }}"
                                                                       data-max-quantity="{{ $result['quantite_demandee'][$gammeId] }}">
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-gray-300"><i class="fas fa-exclamation-circle mr-2"></i>Aucun lot disponible pour cette gamme et type de bouteille.</p>
                                    @endif
                                </div>
                            @endforeach

                            <div class="flex justify-end mt-6">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save mr-2"></i>Enregistrer la commande
                                </button>
                            </div>
                        </form>
                    </div>

                    <a href="{{ route('commandes.preview') }}" class="text-gray-300 hover:text-gold-text">
                        <i class="fas fa-arrow-left mr-2"></i>Retour au formulaire
                    </a>
                </div>
            </div>
        </main>
    </div>

    <script>
        function validateLotsAllocation(event) {
            event.preventDefault();
            let valid = true;
            @foreach ($result['quantite_demandee'] as $gammeId => $quantiteDemandee)
                const inputs = document.querySelectorAll(`input[name^="lots_allocation[{{ $gammeId }}]"]`);
                let totalAllocated = 0;
                inputs.forEach(input => {
                    const value = parseInt(input.value) || 0;
                    totalAllocated += value;
                    if (value > parseInt(input.max)) {
                        valid = false;
                        alert(`La quantité pour le lot ${input.name} dépasse la quantité disponible.`);
                    }
                });
                if (totalAllocated > {{ $quantiteDemandee }}) {
                    valid = false;
                    alert(`La quantité totale allouée pour {{ $result['stocks_disponibles'][$gammeId]['gamme'] }} - {{ $result['stocks_disponibles'][$gammeId]['type_bouteille'] }} dépasse la quantité demandée ({{ $quantiteDemandee }}).`);
                }
            @endforeach
            if (valid) {
                document.querySelector('form').submit();
            }
            return valid;
        }
    </script>
</body>
</html>