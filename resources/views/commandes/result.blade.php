:<!DOCTYPE html>
<html>
<head>
    <title>Résultat de la prévision</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #232323;
            color: #cdb587;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #3d3d3d;
        }
        th {
            background-color: #2d2d2d;
            font-weight: 600;
        }
        tr:nth-child(even) {
            background-color: #1b1b1b;
        }
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
        }
        .alert-danger {
            background-color: rgba(248, 113, 113, 0.2);
            border: 1px solid #f87171;
            color: #f87171;
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
        }
        input[type="number"] {
            background-color: #2d2d2d;
            border: 1px solid #3d3d3d;
            color: white;
            padding: 0.5rem;
            border-radius: 0.25rem;
        }
        input[type="number"]:focus {
            outline: none;
            border-color: #cdb587;
            box-shadow: 0 0 0 2px rgba(205, 181, 135, 0.2);
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
<body>
    <!-- Sidebar -->
    @include('real_sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <h1 class="text-2xl font-bold mb-4 gold-text">Résultat de la prévision</h1>

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-2 gold-text">Stocks disponibles</h2>
            <table>
                <thead>
                    <tr>
                        <th>Gamme</th>
                        <th>Type de bouteille</th>
                        <th>Quantité disponible</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($result['stocks_disponibles'] as $gammeId => $stock)
                        <tr>
                            <td>{{ $stock['gamme'] }}</td>
                            <td>{{ $stock['type_bouteille'] }}</td>
                            <td>{{ $stock['quantite'] }} bouteilles</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-2 gold-text">Faisabilité</h2>
            <table>
                <thead>
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
                            <td>{{ $result['stocks_disponibles'][$gammeId]['gamme'] }}</td>
                            <td>{{ $result['stocks_disponibles'][$gammeId]['type_bouteille'] }}</td>
                            <td>{{ $result['quantite_demandee'][$gammeId] }}</td>
                            <td>{{ $result['date_disponibilite'][$gammeId] }}</td>
                            <td>{{ $result['date_livraison'][$gammeId] }}</td>
                            <td>{{ $status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-2 gold-text">Stocks manquants</h2>
            @if (empty($result['stocks_manquants']))
                <p>Aucun stock manquant.</p>
            @else
                <div class="mb-4">
                    @foreach ($result['manquant'] as $gammeId => $status)
                        <p>Gamme "{{ $result['stocks_disponibles'][$gammeId]['gamme'] }}": {{ $status }} bouteilles manquantes à produire</p>
                    @endforeach
                </div>
                <table>
                    <thead>
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
                                <td>{{ $manque['matiere'] }}</td>
                                <td>{{ $manque['quantite_manquante'] }} unités</td>
                                <td>{{ $manque['gamme'] }}</td>
                                <td>{{ $manque['type_bouteille'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-2 gold-text">Enregistrer la commande</h2>
            <form action="{{ route('commandes.store') }}" method="POST" onsubmit="return validateLotsAllocation(event)">
                @csrf
                
                <input type="hidden" name="commande" value="{{ json_encode($result['commande']) }}">

                <h3 class="text-lg font-medium mb-2 gold-text">Allocation des lots</h3>
                @foreach ($result['lots_disponibles'] as $gammeId => $lots)
                    <div class="mb-4">
                        <h4 class="text-md font-semibold gold-text">
                            {{ $result['stocks_disponibles'][$gammeId]['gamme'] }} - {{ $result['stocks_disponibles'][$gammeId]['type_bouteille'] }}
                        </h4>
                        <p>Quantité demandée: {{ $result['quantite_demandee'][$gammeId] }} bouteilles</p>
                        @if (!empty($lots))
                            <table class="mb-2">
                                <thead>
                                    <tr>
                                        <th>Lot ID</th>
                                        <th>Quantité disponible</th>
                                        <th>Quantité à prendre</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lots as $lot)
                                        <tr>
                                            <td>{{ $lot['id_lot'] }}</td>
                                            <td>{{ $lot['quantite_disponible'] }} bouteilles</td>
                                            <td>
                                                <input type="number" name="lots_allocation[{{ $gammeId }}][{{ $lot['id_lot'] }}]"
                                                       min="0" max="{{ $lot['quantite_disponible'] }}"
                                                       value="0" class="lot-quantity"
                                                       data-gamme-id="{{ $gammeId }}"
                                                       data-max-quantity="{{ $result['quantite_demandee'][$gammeId] }}">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Aucun lot disponible pour cette gamme et type de bouteille.</p>
                        @endif
                    </div>
                @endforeach

                <button type="submit" class="btn btn-primary">Enregistrer la commande</button>
            </form>
        </div>

        <a href="{{ route('commandes.preview') }}" class="text-blue-500 hover:underline">Retour au formulaire</a>
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