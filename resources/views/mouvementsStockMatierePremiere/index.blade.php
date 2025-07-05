<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Mouvements de Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Liste des Mouvements de Stock</h1>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        <a href="{{ route('mouvementsStockMatierePremiere.create') }}" class="btn btn-primary mb-3">Ajouter un mouvement</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Matière Première</th>
                    <th>Type de Mouvement</th>
                    <th>Quantité</th>
                    <th>Date</th>
                    <th>Employé</th>
                    <th>Fournisseur</th>
                    <th>Lot</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mouvements as $mouvement)
                    <tr>
                        <td>{{ $mouvement->matiere->nom ?? 'N/A' }}</td>
                        <td>{{ $mouvement->typeMouvement->nom ?? 'N/A' }}</td>
                        <td>{{ $mouvement->quantite }}</td>
                        <td>{{ $mouvement->date_mouvement }}</td>
                        <td>{{ $mouvement->detailMouvement->employe->nom ?? 'N/A' }}</td>
                        <td>{{ $mouvement->detailMouvement->fournisseur->nom ?? 'N/A' }}</td>
                        <td>{{ $mouvement->detailMouvement->lot->numero ?? 'N/A' }}</td>
                        
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>