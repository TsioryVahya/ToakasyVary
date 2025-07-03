<!DOCTYPE html>
<html>
<head>
    <title>Stock Overview</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px;}
        h1, h2 { color: #333; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 40px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .low-stock { color: red; }
        .notification { background-color: #ffe6e6; padding: 15px; margin-bottom: 20px; border: 1px solid red; color: red; }
    </style>
</head>
<body>
    <h1>Aperçu du Stock</h1>

    @if ($notification)
        <div class="notification">
            {!! $notification !!}
        </div>
    @endif

    <h2>Reste bouteille par Lot</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Gamme</th>
                <th>Bouteille</th>
                <th>Capacité</th>
                <th>Date debut</th>
                <th>Date embouteillage</th>
                <th>Date commercialisation</th>
                <th>Nombre initial sur mouvements</th>
                <th>Reste bouteille</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stocksPerLot as $stock)
                <tr class="{{ $stock->reste_bouteilles < $threshold ? 'low-stock' : '' }}">
                    <td>{{ $stock->id_lot }}</td>
                    <td>{{ $stock->nom_gamme }}</td>
                    <td>{{ $stock->nom_bouteille }}</td>
                    <td>{{ $stock->capacite_bouteille }}</td>
                    <td>{{ $stock->date_debut }}</td>
                    <td>{{ $stock->date_mise_en_bouteille }}</td>
                    <td>{{ $stock->date_commercialisation }}</td>
                    <td>{{ $stock->nombre_bouteilles }}</td>
                    <td>{{ $stock->reste_bouteilles }}</td>
                </tr>
            @empty
                <tr><td colspan="9">Aucun lot trouvé!</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Reste bouteille par gamme-typeBouteille</h2>
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
                <tr class="{{ $stock->total_reste_bouteilles < $threshold ? 'low-stock' : '' }}">
                    <td>{{ $stock->nom_gamme }}</td>
                    <td>{{ $stock->nom_bouteille }}</td>
                    <td>{{ $stock->capacite_bouteille }}</td>
                    <td>{{ $stock->total_reste_bouteilles }}</td>
                </tr>
            @empty
                <tr><td colspan="4">pas de combinaison gamme-bouteille disponible</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Reste bouteille par gamme</h2>
    <table>
        <thead>
            <tr>
                <th>Gamme</th>
                <th>Reste bouteille</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stocksByGamme as $stock)
                <tr class="{{ $stock->total_reste_bouteilles < $threshold ? 'low-stock' : '' }}">
                    <td>{{ $stock->nom_gamme }}</td>
                    <td>{{ $stock->total_reste_bouteilles }}</td>
                </tr>
            @empty
                <tr><td colspan="2">Pas de gamme disponible</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Reste bouteille par type</h2>
    <table>
        <thead>
            <tr>
                <th>Bouteille</th>
                <th>Capacite</th>
                <th>Reste bouteille</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stocksByBouteille as $stock)
                <tr class="{{ $stock->total_reste_bouteilles < $threshold ? 'low-stock' : '' }}">
                    <td>{{ $stock->nom_bouteille }}</td>
                    <td>{{ $stock->capacite_bouteille }}</td>
                    <td>{{ $stock->total_reste_bouteilles }}</td>
                </tr>
            @empty
                <tr><td colspan="3">Pas de type de bouteille disponible</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>