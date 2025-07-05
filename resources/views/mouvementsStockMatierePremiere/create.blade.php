<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Mouvement de Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Ajouter un Mouvement de Stock</h1>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('mouvementsStockMatierePremiere.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="id_matiere" class="form-label">Matière Première</label>
                <select class="form-control" id="id_matiere" name="id_matiere" required>
                    <option value="">Sélectionner une matière</option>
                    @foreach ($matieres as $matiere)
                        <option value="{{ $matiere->id }}" {{ old('id_matiere') == $matiere->id ? 'selected' : '' }}>{{ $matiere->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="id_type_mouvement" class="form-label">Type de Mouvement</label>
                <select class="form-control" id="id_type_mouvement" name="id_type_mouvement" required>
                    <option value="">Sélectionner un type</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->id }}" {{ old('id_type_mouvement') == $type->id ? 'selected' : '' }}>{{ $type->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="id_employe" class="form-label">Employé</label>
                <select class="form-control" id="id_employe" name="id_employe">
                    <option value="">Aucun</option>
                    @foreach ($employes as $employe)
                        <option value="{{ $employe->id }}" {{ old('id_employe') == $employe->id ? 'selected' : '' }}>{{ $employe->nom }} {{ $employe->prenom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="id_fournisseur" class="form-label">Fournisseur</label>
                <select class="form-control" id="id_fournisseur" name="id_fournisseur">
                    <option value="">Aucun</option>
                    @foreach ($fournisseurs as $fournisseur)
                        <option value="{{ $fournisseur->id }}" {{ old('id_fournisseur') == $fournisseur->id ? 'selected' : '' }}>{{ $fournisseur->nom }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="id_lot" class="form-label">Lot de Production</label>
                <select class="form-control" id="id_lot" name="id_lot">
                    <option value="">Aucun</option>
                    @foreach ($lots as $lot)
                        <option value="{{ $lot->id }}" {{ old('id_lot') == $lot->id ? 'selected' : '' }}>{{ $lot->id }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="quantite" class="form-label">Quantité</label>
                <input type="number" class="form-control" id="quantite" name="quantite" value="{{ old('quantite') }}" step="0.01" required>
            </div>
            <div class="mb-3">
                <label for="date_mouvement" class="form-label">Date du Mouvement</label>
                <input type="date" class="form-control" id="date_mouvement" name="date_mouvement" value="{{ old('date_mouvement') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="{{ route('mouvementsStockMatierePremiere.index') }}" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>