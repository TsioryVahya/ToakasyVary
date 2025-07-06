<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .filter-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filter-group {
            margin-bottom: 10px;
        }
        .filter-buttons {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container py-4">
        <h1 class="mb-4">Gestion des Commandes</h1>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        <!-- Section des filtres -->
        <div class="filter-section mb-4">
            <h5>Filtrer les commandes</h5>
            <div class="row">
                <div class="col-md-3 filter-group">
                    <label for="clientFilter" class="form-label">Client</label>
                    <input type="text" id="clientFilter" class="form-control" placeholder="Filtrer par client...">
                </div>
                <div class="col-md-3 filter-group">
                    <label for="dateCommandeFilter" class="form-label">Date Commande</label>
                    <input type="date" id="dateCommandeFilter" class="form-control">
                </div>
                <div class="col-md-3 filter-group">
                    <label for="dateLivraisonFilter" class="form-label">Date Livraison</label>
                    <input type="date" id="dateLivraisonFilter" class="form-control">
                </div>
                <div class="col-md-3 filter-group">
                    <label for="statutFilter" class="form-label">Statut</label>
                    <select id="statutFilter" class="form-select">
                        <option value="Annuler">Annulé</option>
                    </select>
                </div>
            </div>
            <div class="filter-buttons">
                <button id="resetFilters" class="btn btn-secondary">Réinitialiser les filtres</button>
                <button id="showToday" class="btn btn-primary">Livraisons aujourd'hui</button>
            </div>
        </div>
        
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
                <tbody id="commandesTable">
                    @foreach($commandes as $commande)
                        <tr>
                            <td>{{ $commande->nom }}</td>
                            <td data-date-commande="{{ $commande->date_commande }}">
                                {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}
                            </td>
                            <td data-date-livraison="{{ $commande->date_livraison }}">
                                {{ $commande->date_livraison ? \Carbon\Carbon::parse($commande->date_livraison)->format('d/m/Y') : 'Non livré' }}
                            </td>
                            <td class="statut-cell">
                                @if(isset($commande->statut))
                                    {{ $commande->statut }}
                                @else
                                    En attente
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('commandes.annulation', $commande->idCommande) }}" method="get" class="d-inline">
                                    <input type="hidden" name="idCommande" value="{{ $commande->idCommande }}">
                                    <button type="submit" class="btn btn-sm btn-warning">Annuler</button>
                                </form>
                                <form action="{{ route('commandes.valider', $commande->idCommande) }}" method="get" class="d-inline">
                                    <input type="hidden" name="idCommande" value="{{ $commande->idCommande }}">
                                    <button type="submit" class="btn btn-sm btn-success">Valider</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer les éléments du DOM
            const clientFilter = document.getElementById('clientFilter');
            const dateCommandeFilter = document.getElementById('dateCommandeFilter');
            const dateLivraisonFilter = document.getElementById('dateLivraisonFilter');
            const statutFilter = document.getElementById('statutFilter');
            const resetFilters = document.getElementById('resetFilters');
            const showToday = document.getElementById('showToday');
            const commandesTable = document.getElementById('commandesTable');
            const rows = commandesTable.getElementsByTagName('tr');
            
            // Fonction pour obtenir la date d'aujourd'hui au format YYYY-MM-DD
            function getTodayDate() {
                const today = new Date();
                const year = today.getFullYear();
                const month = String(today.getMonth() + 1).padStart(2, '0');
                const day = String(today.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }
            
            // Fonction pour appliquer tous les filtres
            function applyFilters() {
                const clientValue = clientFilter.value.toLowerCase();
                const dateCommandeValue = dateCommandeFilter.value;
                const dateLivraisonValue = dateLivraisonFilter.value;
                const statutValue = statutFilter.value;
                const today = getTodayDate();
                
                for (let row of rows) {
                    const cells = row.getElementsByTagName('td');
                    if (cells.length === 0) continue;
                    
                    const clientCell = cells[0].textContent.toLowerCase();
                    const dateCommandeCell = cells[1].getAttribute('data-date-commande');
                    const dateCommandeFormatted = dateCommandeCell ? new Date(dateCommandeCell).toISOString().split('T')[0] : '';
                    const dateLivraisonCell = cells[2].getAttribute('data-date-livraison');
                    const dateLivraisonFormatted = dateLivraisonCell ? new Date(dateLivraisonCell).toISOString().split('T')[0] : '';
                    const statutCell = cells[3].textContent.trim();
                    
                    // Vérifier chaque condition de filtre
                    const clientMatch = clientValue === '' || clientCell.includes(clientValue);
                    const dateCommandeMatch = dateCommandeValue === '' || dateCommandeFormatted === dateCommandeValue;
                    const dateLivraisonMatch = dateLivraisonValue === '' || 
                        (dateLivraisonFormatted === dateLivraisonValue) || 
                        (dateLivraisonValue === '' && dateLivraisonCell === null && statutCell === 'Non livré');
                    const statutMatch = statutValue === '' || statutCell === statutValue;
                    
                    // Afficher ou masquer la ligne en fonction des filtres
                    if (clientMatch && dateCommandeMatch && dateLivraisonMatch && statutMatch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            }
            
            // Fonction pour afficher les livraisons d'aujourd'hui
            function showTodayDeliveries() {
                const today = getTodayDate();
                
                for (let row of rows) {
                    const cells = row.getElementsByTagName('td');
                    if (cells.length === 0) continue;
                    
                    const dateLivraisonCell = cells[2].getAttribute('data-date-livraison');
                    const dateLivraisonFormatted = dateLivraisonCell ? new Date(dateLivraisonCell).toISOString().split('T')[0] : '';
                    
                    if (dateLivraisonFormatted === today) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
                
                // Réinitialiser les autres filtres
                clientFilter.value = '';
                dateCommandeFilter.value = '';
                dateLivraisonFilter.value = '';
                statutFilter.value = '';
            }
            
            // Écouteurs d'événements pour les filtres
            clientFilter.addEventListener('input', applyFilters);
            dateCommandeFilter.addEventListener('change', applyFilters);
            dateLivraisonFilter.addEventListener('change', applyFilters);
            statutFilter.addEventListener('change', applyFilters);
            
            // Réinitialiser les filtres
            resetFilters.addEventListener('click', function() {
                clientFilter.value = '';
                dateCommandeFilter.value = '';
                dateLivraisonFilter.value = '';
                statutFilter.value = '';
                applyFilters();
            });
            
            // Afficher les livraisons d'aujourd'hui
            showToday.addEventListener('click', showTodayDeliveries);
        });
    </script>
</body>
</html>