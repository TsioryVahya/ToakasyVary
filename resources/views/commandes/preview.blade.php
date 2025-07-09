<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévoir une commande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .main-content {
            background-color: #1b1b1b;
            flex-grow: 1;
            padding: 1.5rem;
            overflow: auto;
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
        }
        .btn-primary {
            background-color: #cdb587;
            color: #1b1b1b;
        }
        .btn-primary:hover {
            background-color: #d9c9a3;
            transform: translateY(-1px);
        }
        .btn-primary:active {
            background-color: #c3b083;
        }
        .btn-secondary {
            background-color: #3d3d3d;
            color: white;
        }
        .btn-secondary:hover {
            background-color: #4d4d4d;
            transform: translateY(-1px);
        }
        .btn-secondary:active {
            background-color: #2d2d2d;
        }
        .btn-success {
            background-color: #34d399;
            color: white;
        }
        .btn-success:hover {
            background-color: #10b981;
        }
        .btn-danger {
            background-color: #f87171;
            color: white;
        }
        .btn-danger:hover {
            background-color: #ef4444;
        }
        .btn-warning {
            background-color: #fbbf24;
            color: #1b1b1b;
        }
        .btn-warning:hover {
            background-color: #f59e42;
        }
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
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
        .statut-en-attente {
            color: #fbbf24;
        }
        .statut-valide {
            color: #34d399;
        }
        .statut-annule {
            color: #f87171;
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
        .form-control, .form-select {
            background-color: #2d2d2d;
            border: 1px solid #3d3d3d;
            color: white;
        }
        .form-control:focus, .form-select:focus {
            background-color: #2d2d2d;
            color: white;
            border-color: #cdb587;
            box-shadow: 0 0 0 0.25rem rgba(205, 181, 135, 0.25);
        }
        .ligne {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1rem;
        }
        .ligne > * {
            flex: 1;
        }
        .ligne > button {
            flex: 0 0 auto;
        }
        .text-danger {
            color: #f87171;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    @include('real_sidebar')

    <!-- Main Content -->
    <div class="main-content">
        <h1 class="mb-4 gold-text">Prévoir une commande</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('commandes.preview') }}" onsubmit="debugForm(event)">
            @csrf
            <div class="mb-3">
                <label for="date_livraison" class="form-label">Date de livraison</label>
                <input type="date" name="date_livraison" id="date_livraison" required 
                       value="{{ old('date_livraison') }}" class="form-control">
                @error('date_livraison')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="id_client" class="form-label">Client</label>
                <select name="id_client" id="id_client" required class="form-select">
                    <option value="">Sélectionner un client</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}" {{ old('lignes.0.id_client') == $client->id ? 'selected' : '' }}>
                            {{ $client->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="ligne-container">
                <h3 class="mb-3">Lignes de commande</h3>
                <button type="button" onclick="addLigne()" class="btn btn-primary mb-3">Ajouter une ligne</button>
                
                <div id="lignes-container">
                    <div class="ligne mb-3">
                        <select name="lignes[0][id_gamme]" required class="form-select">
                            <option value="">Sélectionner une gamme</option>
                            @foreach($gammes as $gamme)
                                <option value="{{ $gamme->id }}" {{ old('lignes.0.id_gamme') == $gamme->id ? 'selected' : '' }}>
                                    {{ $gamme->nom }}
                                </option>
                            @endforeach
                        </select>
                        
                        <select name="lignes[0][id_bouteille]" required class="form-select">
                            <option value="">Sélectionner type bouteille</option>
                            @foreach($typesBouteilles as $typesBouteille)
                                <option value="{{ $typesBouteille->id }}" {{ old('lignes.0.id_typesBouteille') == $typesBouteille->id ? 'selected' : '' }}>
                                    {{ $typesBouteille->nom }}
                                </option>
                            @endforeach
                        </select>
                        
                        <input type="number" name="lignes[0][quantite_bouteilles]" min="1" required
                               value="{{ old('lignes.0.quantite_bouteilles') }}" 
                               placeholder="Nombre de bouteilles" class="form-control">
                        
                        <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger">Supprimer</button>
                        
                        @error('lignes.0.id_gamme')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @error('lignes.0.quantite_bouteilles')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    @if (old('lignes'))
                        @foreach (old('lignes') as $index => $ligne)
                            @if ($index > 0)
                                <div class="ligne mb-3">
                                    <select name="lignes[{{ $index }}][id_gamme]" required class="form-select">
                                        <option value="">Sélectionner une gamme</option>
                                        @foreach($gammes as $gamme)
                                            <option value="{{ $gamme->id }}" {{ old("lignes.$index.id_gamme") == $gamme->id ? 'selected' : '' }}>
                                                {{ $gamme->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    
                                    <input type="number" name="lignes[{{ $index }}][quantite_bouteilles]" min="1" required
                                           value="{{ old("lignes.$index.quantite_bouteilles") }}" 
                                           placeholder="Nombre de bouteilles" class="form-control">
                                    
                                    <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger">Supprimer</button>
                                    
                                    @error("lignes.$index.id_gamme")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    @error("lignes.$index.quantite_bouteilles")
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Prévoir</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function addLigne() {
            let container = document.getElementById('lignes-container');
            let index = container.querySelectorAll('.ligne').length;
            let div = document.createElement('div');
            div.className = 'ligne mb-3';
            div.innerHTML = `
                <select name="lignes[${index}][id_gamme]" required class="form-select">
                    <option value="">Sélectionner une gamme</option>
                    @foreach($gammes as $gamme)
                        <option value="{{ $gamme->id }}">{{ $gamme->nom }}</option>
                    @endforeach
                </select>
                <select name="lignes[${index}][id_bouteille]" required class="form-select">
                    <option value="">Sélectionner type bouteille</option>
                    @foreach($typesBouteilles as $typesBouteille)
                        <option value="{{ $typesBouteille->id }}">{{ $typesBouteille->nom }}</option>
                    @endforeach
                </select>
                <input type="number" name="lignes[${index}][quantite_bouteilles]" min="1" required placeholder="Nombre de bouteilles" class="form-control">
                <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger">Supprimer</button>
            `;
            container.appendChild(div);
        }

        function debugForm(event) {
            event.preventDefault();
            const form = document.querySelector('form');
            const formData = new FormData(form);
            console.log('Données du formulaire :');
            for (let [key, value] of formData.entries()) {
                console.log(`${key}: ${value}`);
            }
            const lignes = form.querySelectorAll('.ligne');
            let valid = true;
            lignes.forEach((ligne, index) => {
                const gamme = ligne.querySelector(`select[name="lignes[${index}][id_gamme]"]`).value;
                const quantite = ligne.querySelector(`input[name="lignes[${index}][quantite_bouteilles]"]`).value;
                if (!gamme || !quantite) {
                    valid = false;
                    alert(`Ligne ${index + 1} incomplète : gamme ou quantité manquante.`);
                }
            });
            if (valid && lignes.length > 0) {
                form.submit();
            } else if (lignes.length === 0) {
                alert('Veuillez ajouter au moins une ligne de commande.');
            }
        }
    </script>
</body>
</html>