<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prévoir une commande</title>
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
        .form-control, .form-select {
            background-color: #2d2d2d;
            border: 1px solid #3d3d3d;
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }
        .form-control:focus, .form-select:focus {
            background-color: #2d2d2d;
            color: white;
            border-color: #cdb587;
            box-shadow: 0 0 0 0.25rem rgba(205, 181, 135, 0.25);
        }
        .ligne {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 1rem;
            align-items: center;
            margin-bottom: 1rem;
        }
        .text-danger {
            color: #ef4444;
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
    </style>
</head>
<body class="min-h-screen flex">
    <!-- Sidebar -->
    @include('slidebar')

    <!-- Content Container -->
    <div class="content-container">
        <main class="flex-grow p-6 overflow-auto">
            <div class="main-content">
                <h1 class="text-3xl font-bold gold-text mb-6"><i class="fas fa-cart-plus mr-2"></i>Prévoir une commande</h1>

                @if ($errors->any())
                    <div class="alert-error">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="filter-section">
                    <form method="POST" action="{{ route('commandes.preview') }}" onsubmit="debugForm(event)">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="date_livraison" class="block text-sm font-medium gold-text mb-1">
                                    <i class="fas fa-calendar-alt mr-1"></i>Date de livraison
                                </label>
                                <input type="date" name="date_livraison" id="date_livraison" required 
                                       value="{{ old('date_livraison') }}" class="form-control">
                                @error('date_livraison')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="id_client" class="block text-sm font-medium gold-text mb-1">
                                    <i class="fas fa-user mr-1"></i>Client
                                </label>
                                <select name="id_client" id="id_client" required class="form-select">
                                    <option value="">Sélectionner un client</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ old('lignes.0.id_client') == $client->id ? 'selected' : '' }}>
                                            {{ $client->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="ligne-container">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-semibold gold-text"><i class="fas fa-list mr-2"></i>Lignes de commande</h3>
                                <button type="button" onclick="addLigne()" class="btn btn-success">
                                    <i class="fas fa-plus mr-2"></i>Ajouter une ligne
                                </button>
                            </div>
                            
                            <div id="lignes-container">
                                <div class="ligne">
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
                                    
                                    <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    
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
                                            <div class="ligne">
                                                <select name="lignes[{{ $index }}][id_gamme]" required class="form-select">
                                                    <option value="">Sélectionner une gamme</option>
                                                    @foreach($gammes as $gamme)
                                                        <option value="{{ $gamme->id }}" {{ old("lignes.$index.id_gamme") == $gamme->id ? 'selected' : '' }}>
                                                            {{ $gamme->nom }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                
                                                <select name="lignes[{{ $index }}][id_bouteille]" required class="form-select">
                                                    <option value="">Sélectionner type bouteille</option>
                                                    @foreach($typesBouteilles as $typesBouteille)
                                                        <option value="{{ $typesBouteille->id }}">{{ $typesBouteille->nom }}</option>
                                                    @endforeach
                                                </select>
                                                
                                                <input type="number" name="lignes[{{ $index }}][quantite_bouteilles]" min="1" required
                                                       value="{{ old("lignes.$index.quantite_bouteilles") }}" 
                                                       placeholder="Nombre de bouteilles" class="form-control">
                                                
                                                <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                                
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

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Prévoir
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script>
        function addLigne() {
            let container = document.getElementById('lignes-container');
            let index = container.querySelectorAll('.ligne').length;
            let div = document.createElement('div');
            div.className = 'ligne';
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
                <button type="button" onclick="this.parentElement.remove()" class="btn btn-danger"><i class="fas fa-trash"></i></button>
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