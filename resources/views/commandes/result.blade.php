

<!DOCTYPE html>
<html>
<head>
    <title>Résultat de la prévision</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Résultat de la prévision</h1>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Stocks disponibles</h2>
        <table class="min-w-full bg-white border">
            <thead class="text-left">
                <tr>
                    <th class="py-2 px-4 border-b">Gamme</th>
                    <th class="py-2 px-4 border-b">Type de bouteille</th>
                    <th class="py-2 px-4 border-b">Quantité disponible</th>
                </tr>
            </thead>
            <tbody class="text-left">
                @foreach ($result['stocks_disponibles'] as $gammeId => $stock)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $stock['gamme'] }}</td>
                        <td class="py-2 px-4 border-b">{{ $stock['type_bouteille'] }}</td>
                        <td class="py-2 px-4 border-b">{{ $stock['quantite'] }} bouteilles</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Faisabilité</h2>
        <table class="min-w-full bg-white border">
            <thead class="text-left">
                <tr>
                    <th class="py-2 px-4 border-b">Gamme</th>
                    <th class="py-2 px-4 border-b">Type de bouteille</th>
                    <th class="py-2 px-4 border-b">Quantité demandée</th>
                    <th class="py-2 px-4 border-b">Date de disponibilité</th>
                    <th class="py-2 px-4 border-b">Date de livraison</th>
                    <th class="py-2 px-4 border-b">Statut</th>
                </tr>
            </thead>
            <tbody class="text-left">
                @foreach ($result['faisabilite'] as $gammeId => $status)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $result['stocks_disponibles'][$gammeId]['gamme'] }}</td>
                        <td class="py-2 px-4 border-b">{{ $result['stocks_disponibles'][$gammeId]['type_bouteille'] }}</td>
                        <td class="py-2 px-4 border-b">{{ $result['quantite_demandee'][$gammeId] }}</td>
                        <td class="py-2 px-4 border-b">{{ $result['date_disponibilite'][$gammeId] }}</td>
                        <td class="py-2 px-4 border-b">{{ $result['date_livraison'][$gammeId] }}</td>
                        <td class="py-2 px-4 border-b">{{ $status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Stocks manquants</h2>
        @if (empty($result['stocks_manquants']))
            <p>Aucun stock manquant.</p>
        @else
            <div class="mb-4">
                @foreach ($result['manquant'] as $gammeId => $status)
                    <p>Gamme "{{ $result['stocks_disponibles'][$gammeId]['gamme'] }}": {{ $status }} bouteilles manquantes à produire</p>
                @endforeach
            </div>
            <table class="min-w-full bg-white border">
                <thead class="text-left">
                    <tr>
                        <th class="py-2 px-4 border-b">Matière</th>
                        <th class="py-2 px-4 border-b">Quantité manquante</th>
                        <th class="py-2 px-4 border-b">Gamme</th>
                        <th class="py-2 px-4 border-b">Type de bouteille</th>
                    </tr>
                </thead>
                <tbody class="text-left">
                    @foreach ($result['stocks_manquants'] as $manque)
                        <tr>
                            <td class="py-2 px-4 border-b">{{ $manque['matiere'] }}</td>
                            <td class="py-2 px-4 border-b">{{ $manque['quantite_manquante'] }} unités</td>
                            <td class="py-2 px-4 border-b">{{ $manque['gamme'] }}</td>
                            <td class="py-2 px-4 border-b">{{ $manque['type_bouteille'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="mb-8">
        <h2 class="text-xl font-semibold mb-2">Enregistrer la commande</h2>
        <form action="{{ route('commandes.store') }}" method="POST">
            @csrf
            <input type="hidden" name="commande" value="{{ json_encode($result['commande']) }}">
            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Enregistrer la commande</button>
        </form>
    </div>

    <a href="{{ route('commandes.preview') }}" class="text-blue-500 hover:underline">Retour au formulaire</a>
</div>
</body>
</html>