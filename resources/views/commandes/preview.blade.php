<!DOCTYPE html>
<html>
<head>
    <title>Prévoir une commande</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script>
        function addLigne() {
            let container = document.getElementById('lignes-container');
            let index = container.querySelectorAll('.ligne').length;
            let div = document.createElement('div');
            div.className = 'ligne flex space-x-2 mb-2';
            div.innerHTML = `
                <select name="lignes[${index}][id_gamme]" required class="p-2 border rounded">
                    <option value="">Sélectionner une gamme</option>
                    @foreach($gammes as $gamme)
                        <option value="{{ $gamme->id }}">{{ $gamme->nom }}</option>
                    @endforeach
                </select>
                <select name="lignes[${index}][id_bouteille]" required class="p-2 border rounded">
                    <option value="">Sélectionner type bouteille</option>
                    @foreach($typesBouteilles as $typesBouteille)
                        <option value="{{ $typesBouteille->id }}">{{ $typesBouteille->nom }}</option>
                    @endforeach
                </select>
                <input type="number" name="lignes[${index}][quantite_bouteilles]" min="1" required placeholder="Nombre de bouteilles" class="p-2 border rounded">
                <button type="button" onclick="this.parentElement.remove()" class="bg-red-500 text-white px-2 py-1 rounded">Supprimer</button>
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
</head>
<body class="bg-gray-100 font-sans">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Prévoir une commande</h1>

        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('commandes.preview') }}" onsubmit="debugForm(event)">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700">Date de livraison</label>
                <input type="date" name="date_livraison" required value="{{ old('date_livraison') }}" class="p-2 border rounded w-full">
                @error('date_livraison')
                    <span class="text-red-600 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div id="lignes-container" class="mb-4">
                <h3 class="text-xl font-semibold mb-2">Lignes de commande</h3>
                <button type="button" onclick="addLigne()" class="bg-blue-500 text-white px-4 py-2 rounded mb-2">Ajouter une ligne</button>
                <div class="ligne flex space-x-2 mb-2">
                    <select name="lignes[0][id_gamme]" required class="p-2 border rounded">
                        <option value="">Sélectionner une gamme</option>
                        @foreach($gammes as $gamme)
                            <option value="{{ $gamme->id }}" {{ old('lignes.0.id_gamme') == $gamme->id ? 'selected' : '' }}>
                                {{ $gamme->nom }}
                            </option>
                        @endforeach
                    </select>
                    <select name="lignes[0][id_bouteille]" required class="p-2 border rounded">
                        <option value="">Sélectionner type bouteille</option>
                        @foreach($typesBouteilles as $typesBouteille)
                            <option value="{{ $typesBouteille->id }}" {{ old('lignes.0.id_typesBouteille') == $typesBouteille->id ? 'selected' : '' }}>
                                {{ $typesBouteille->nom }}
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="lignes[0][quantite_bouteilles]" min="1" required
                           value="{{ old('lignes.0.quantite_bouteilles') }}" placeholder="Nombre de bouteilles" class="p-2 border rounded">
                    <button type="button" onclick="this.parentElement.remove()" class="bg-red-500 text-white px-2 py-1 rounded">Supprimer</button>
                    @error('lignes.0.id_gamme')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                    @error('lignes.0.quantite_bouteilles')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                @if (old('lignes'))
                    @foreach (old('lignes') as $index => $ligne)
                        @if ($index > 0)
                            <div class="ligne flex space-x-2 mb-2">
                                <select name="lignes[{{ $index }}][id_gamme]" required class="p-2 border rounded">
                                    <option value="">Sélectionner une gamme</option>
                                    @foreach($gammes as $gamme)
                                        <option value="{{ $gamme->id }}" {{ old("lignes.$index.id_gamme") == $gamme->id ? 'selected' : '' }}>
                                            {{ $gamme->nom }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="number" name="lignes[{{ $index }}][quantite_bouteilles]" min="1" required
                                       value="{{ old("lignes.$index.quantite_bouteilles") }}" placeholder="Nombre de bouteilles" class="p-2 border rounded">
                                <button type="button" onclick="this.parentElement.remove()" class="bg-red-500 text-white px-2 py-1 rounded">Supprimer</button>
                                @error("lignes.$index.id_gamme")
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                                @error("lignes.$index.quantite_bouteilles")
                                    <span class="text-red-600 text-sm">{{ $message }}</span>
                                @enderror
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Prévoir</button>
        </form>
    </div>
</body>
</html>