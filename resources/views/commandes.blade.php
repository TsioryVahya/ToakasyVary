<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">Gestion des Commandes</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Client</th>
                        <th>Date Commande</th>
                        <th>Date Livraison</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commandes as $commande)
                        <tr>
                            <td>{{ $commande->nom }}</td>
                            <td>{{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}</td>
                            <td>{{ $commande->date_livraison ? \Carbon\Carbon::parse($commande->date_livraison)->format('d/m/Y') : 'Non livré' }}</td>
                            <td>
                              <form action="{{ route('commandes.annulation', $commande->idCommande) }}" method="get">
                                <input type="hidden" name="idCommande" value="{{ $commande->idCommande }}">
                                <input type="submit" value="Annuler">
                              </form>
                            </td>
                            <td>
                              <form action="{{ route('commandes.valider', $commande->idCommande) }}" method="get">
                                <input type="hidden" name="idCommande" value="{{ $commande->idCommande }}">
                                <input type="submit" value="valider">  
                            </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>