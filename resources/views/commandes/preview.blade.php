<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévoir une commande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .ligne-container {
            margin-bottom: 15px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        .ligne {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
            align-items: center;
        }
        @media (max-width: 768px) {
            .ligne {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">Prévoir une commande</h1>

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