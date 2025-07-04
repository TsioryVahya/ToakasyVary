<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des Ventes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --bg: #f0f2f5;
            --card-bg: #ffffff;
            --primary: #007bff;
            --text-dark: #333;
            --text-light: #666;
            --border: #e0e0e0;
        }

        body {
            margin: 0;
            padding: 20px;
            font-family: 'Segoe UI', sans-serif;
            background-color: var(--bg);
            color: var(--text-dark);
        }

        h1 {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            color: var(--primary);
        }

        .ventes-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 900px;
            margin: 0 auto;
        }

        .vente-card {
            background-color: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 20px;
            transition: transform 0.2s ease;
        }

        .vente-card:hover {
            transform: translateY(-5px);
        }

        .vente-header {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .vente-header h2 {
            font-size: 1.2rem;
            margin: 0;
            color: var(--text-dark);
        }

        .vente-header span {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .vente-details {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .detail-item {
            flex: 1 1 200px;
        }

        .detail-item strong {
            color: var(--primary);
            display: block;
            font-size: 0.95rem;
            margin-bottom: 4px;
        }

        .detail-item span {
            font-size: 0.95rem;
            color: var(--text-dark);
        }

        @media (max-width: 600px) {
            .vente-details {
                flex-direction: column;
            }
        }
        .filtre-section {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    background-color: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    margin-bottom: 30px;
    align-items: flex-end;
}

.filtre-group {
    display: flex;
    flex-direction: column;
    flex: 1 1 200px;
}

.filtre-group label {
    font-weight: 600;
    color: #444;
    margin-bottom: 6px;
}

.filtre-group input {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 1rem;
}

.filtre-buttons {
    display: flex;
    gap: 10px;
}

.btn-primary,
.btn-secondary {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    font-size: 1rem;
}

.btn-primary {
    background-color: #007bff;
    color: #fff;
}

.btn-secondary {
    background-color: #f0f0f0;
    color: #333;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-secondary:hover {
    background-color: #e0e0e0;
}

@media (max-width: 768px) {
    .filtre-section {
        flex-direction: column;
    }

    .filtre-buttons {
        justify-content: flex-start;
        margin-top: 10px;
    }
}

    </style>
</head>
<body>
    <h1>Historique des Ventes</h1>
<!-- Zone de filtres -->
<div class="filtre-section">
    <div class="filtre-group">
        <label for="clientFilter">Client</label>
        <input type="text" id="clientFilter" placeholder="Nom du client">
    </div>

    <div class="filtre-group">
        <label for="gammeFilter">Gamme</label>
        <input type="text" id="gammeFilter" placeholder="Nom de la gamme">
    </div>

    <div class="filtre-group">
        <label for="dateDebut">Date début</label>
        <input type="date" id="dateDebut">
    </div>

    <div class="filtre-group">
        <label for="dateFin">Date fin</label>
        <input type="date" id="dateFin">
    </div>

    <div class="filtre-buttons">
        <button class="btn-primary" onclick="filtrerVentes()">Filtrer</button>
        <button class="btn-secondary" onclick="resetFiltres()">Réinitialiser</button>
    </div>
</div>


    <div class="ventes-container" id="ventes-container">
        @foreach($ventes as $vente)
            <div class="vente-card"
                 data-client="{{ strtolower($vente->nom_clients) }}"
                 data-gamme="{{ strtolower($vente->nom_gamme) }}"
                 data-date="{{ \Carbon\Carbon::parse($vente->vente)->format('Y-m-d') }}">

                <div class="vente-header">
                    <h2>{{ $vente->nom_clients }}</h2>
                    <span>{{ \Carbon\Carbon::parse($vente->vente)->format('d/m/Y') }}</span>
                </div>
                <div class="vente-details">
                    <div class="detail-item">
                        <strong>Email</strong>
                        <span>{{ $vente->email_clients }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Gamme</strong>
                        <span>{{ $vente->nom_gamme }}</span>
                    </div>
                    <div class="detail-item">
                        <strong>Montant</strong>
                        <span>{{ number_format($vente->montant, 2) }} €</span>
                    </div>
                    <div class="detail-item">
                        <strong>Quantité</strong>
                        <span>{{ $vente->quantite }} bouteilles</span>
                    </div>
                    <div class="detail-item">
                        <strong>Livraison</strong>
                        <span>{{ \Carbon\Carbon::parse($vente->livraison)->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</body>
<script>
    function filtrerVentes() {
        const client = document.getElementById('clientFilter').value.toLowerCase();
        const gamme = document.getElementById('gammeFilter').value.toLowerCase();
        const dateDebut = document.getElementById('dateDebut').value;
        const dateFin = document.getElementById('dateFin').value;

        document.querySelectorAll('.vente-card').forEach(card => {
            const dataClient = card.dataset.client;
            const dataGamme = card.dataset.gamme;
            const dataDate = card.dataset.date;

            let visible = true;

            if (client && !dataClient.includes(client)) visible = false;
            if (gamme && !dataGamme.includes(gamme)) visible = false;

            if (dateDebut && dataDate < dateDebut) visible = false;
            if (dateFin && dataDate > dateFin) visible = false;

            card.style.display = visible ? 'block' : 'none';
        });
    }

    function resetFiltres() {
        document.getElementById('clientFilter').value = '';
        document.getElementById('gammeFilter').value = '';
        document.getElementById('dateDebut').value = '';
        document.getElementById('dateFin').value = '';
        filtrerVentes();
    }
</script>

</html>
