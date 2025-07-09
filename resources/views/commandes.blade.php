<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e6c6f2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #0c0c0c;
            color: white;
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar-custom {
            width: 16rem;
            background-color: #1b1b1b;
        }
        .main-content {
            background-color: #1b1b1b;
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
    </style>
</head>
<body class="min-h-screen flex">

    <!-- Sidebar -->
    <div class="w-64 sidebar-custom text-white p-5 flex flex-col">
        <img src="../img/logo_rhum_rice.png" alt="logo" class="w-48 mx-auto mb-8">
        <nav class="flex-1">
            <a href="/home" class="flex items-center py-2 px-3 rounded mb-2 bg-gray-800 hover:bg-opacity-20 hover:bg-white transition">
                <i class="fas fa-home mr-2"></i> Accueil
            </a>
            <a href="#" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
                <i class="fas fa-boxes mr-2"></i> Produits
            </a>
            <a href="{{ route('stockProduitsFinis.all') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
                <i class="fas fa-warehouse mr-2"></i> Stock Produits Finis
            </a>
            <a href="{{ route('production.calendar') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
                <i class="fas fa-calendar-alt mr-2"></i> Calendrier Production
            </a>
            <a href="{{ route('commandes') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
                <i class="fas fa-file-invoice mr-2"></i> Commandes Production
            </a>
        </nav>
        <a href="/login" class="flex items-center py-2 px-3 rounded hover:bg-opacity-20 hover:bg-white transition mt-auto">
            <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
        </a>
    </div>

    <!-- Main Content -->
    <main class="flex-grow p-6 overflow-auto">
        <div class="main-content rounded-lg shadow-lg p-6">
            <h1 class="mb-4 text-2xl font-bold gold-text">Gestion des Commandes</h1>

            @if(session('success'))
                <div class="alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Section des filtres -->
            <div class="filter-section mb-4">
                <h5 class="text-lg font-semibold mb-3 gold-text">Filtrer les commandes</h5>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label for="clientFilter" class="block mb-1">Client</label>
                        <input type="text" id="clientFilter" class="input-field" placeholder="Filtrer par client...">
                    </div>
                    <div>
                        <label for="dateCommandeFilter" class="block mb-1">Date Commande</label>
                        <input type="date" id="dateCommandeFilter" class="input-field">
                    </div>
                    <div>
                        <label for="dateLivraisonFilter" class="block mb-1">Date Livraison</label>
                        <input type="date" id="dateLivraisonFilter" class="input-field">
                    </div>
                    <div>
                        <label for="statutFilter" class="block mb-1">Statut</label>
                        <select id="statutFilter" class="input-field">
                            <option value="">Tous</option>
                            <option value="Annuler">Annulé</option>
                        </select>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button id="resetFilters" class="btn btn-secondary">Réinitialiser les filtres</button>
                    <button id="showToday" class="btn btn-primary">Livraisons aujourd'hui</button>
                </div>
            </div>

            <div class="table-container">
                <table class="w-full border-collapse table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th class="py-2 px-4">Client</th>
                            <th class="py-2 px-4">Date Commande</th>
                            <th class="py-2 px-4">Date Livraison</th>
                            <th class="py-2 px-4">Statut</th>
                            <th class="py-2 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="commandesTable">
                        @foreach($commandes as $commande)
                            <tr>
                                <td class="py-2 px-4">{{ $commande->nom }}</td>
                                <td class="py-2 px-4" data-date-commande="{{ $commande->date_commande }}">
                                    {{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}
                                </td>
                                <td class="py-2 px-4" data-date-livraison="{{ $commande->date_livraison }}">
                                    {{ $commande->date_livraison ? \Carbon\Carbon::parse($commande->date_livraison)->format('d/m/Y') : 'Non livré' }}
                                </td>
                                <td class="py-2 px-4 statut-cell">
                                    @if(isset($commande->statut))
                                        {{ $commande->statut }}
                                    @else
                                        <span class="statut-en-attente">En attente</span>
                                    @endif
                                </td>
                                <td class="py-2 px-4">
                                    <form action="{{ route('commandes.annulation', $commande->idCommande) }}" method="get" class="inline">
                                        <input type="hidden" name="idCommande" value="{{ $commande->idCommande }}">
                                        <button type="submit" class="btn btn-sm btn-warning mr-2"><i class="fas fa-times"></i> Annuler</button>
                                    </form>
                                    <form action="{{ route('commandes.valider', $commande->idCommande) }}" method="get" class="inline">
                                        <input type="hidden" name="idCommande" value="{{ $commande->idCommande }}">
                                        <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Valider</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </main>

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