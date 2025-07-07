<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Commandes - ToakaVary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e6c6f2.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/">
    <style>
        /* Styles de base */
        body {
            background-color: #0c0c0c;
            color: white;
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar {
            width: 16rem;
            background-color: #1b1b1b;
        }
        .main-content {
            background-color: #1b1b1b;
        }
        .gold-text {
            color: #cdb587;
        }

        /* Styles des inputs */
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

        /* Styles des boutons */
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
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }

        /* Autres styles */
        .filter-section {
            background-color: #2d2d2d;
            border: 1px solid #3d3d3d;
        }
        .table-container {
            overflow-x: auto;
        }
        .statut-en-attente {
            color: #fbbf24; /* amber-400 */
        }
        .statut-valide {
            color: #34d399; /* emerald-400 */
        }
        .statut-annule {
            color: #f87171; /* red-400 */
        }
    </style>
</head>
<body class="min-h-screen flex">

<!-- Sidebar -->
<div class="w-64 sidebar-custom text-white p-5 flex flex-col">
    <img src="../img/logo_rhum_rice.png" alt="logo" class="w-48 mx-auto mb-8">
    <nav class="flex-1">
        <a href="/home" class="flex items-center py-2 px-3 rounded mb-2 bg-gray-800 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>
            Dashboard
        </a>
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-industry mr-3 w-5 text-center"></i>
            Production
        </a>
        <a href="{{ route('stockProduitsFinis.all') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-boxes mr-3 w-5 text-center"></i>
            Stocks
        </a>

        <a href="{{ route('production.calendar') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
            Calendrier
        </a>
        <a href="{{ route('production.commandes') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
            Commandes des clients
        </a>
        <a href="/statForm" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
            Statistiques des ventes
        </a>
        <a href="{{ route('production.histogram') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
            Statistiques des productions
        </a>
        <a href="{{ route('historique_vente') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-history mr-3 w-5 text-center"></i>
            Historique Vente
        </a>


    </nav>
    <a href="/login" class="flex items-center py-2 px-3 rounded hover:bg-opacity-20 hover:bg-white transition mt-auto">
        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
        Déconnexion
    </a>
</div>

<!-- Main Content -->
<main class="flex-grow p-6 overflow-auto">
    <div class="main-content rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold gold-text mb-6">Gestion des Commandes</h1>

        @if(session('success'))
            <div class="bg-green-600 text-white p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Section des filtres -->
        <div class="filter-section rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold gold-text mb-4">Filtrer les commandes</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                <div class="filter-group">
                    <label for="clientFilter" class="block text-gray-300 mb-2">Client</label>
                    <input type="text" id="clientFilter" class="input-field" placeholder="Filtrer par client...">
                </div>
                <div class="filter-group">
                    <label for="dateCommandeFilter" class="block text-gray-300 mb-2">Date Commande</label>
                    <input type="date" id="dateCommandeFilter" class="input-field">
                </div>
                <div class="filter-group">
                    <label for="dateLivraisonFilter" class="block text-gray-300 mb-2">Date Livraison</label>
                    <input type="date" id="dateLivraisonFilter" class="input-field">
                </div>
                <div class="filter-group">
                    <label for="statutFilter" class="block text-gray-300 mb-2">Statut</label>
                    <select id="statutFilter" class="input-field">
                        <option value="">Tous</option>
                        <option value="En attente">En attente</option>
                        <option value="Validé">Validé</option>
                        <option value="Annulé">Annulé</option>
                    </select>
                </div>
            </div>
            <div class="flex space-x-4">
                <button id="resetFilters" class="btn btn-secondary">
                    <i class="fas fa-redo mr-2"></i> Réinitialiser
                </button>
                <button id="showToday" class="btn btn-primary">
                    <i class="fas fa-calendar-day mr-2"></i> Livraisons aujourd'hui
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="w-full border-collapse">
                <thead>
                <tr class="bg-[#2d2d2d] text-[#cdb587]">
                    <th class="p-3 border border-[#3d3d3d] text-left">Client</th>
                    <th class="p-3 border border-[#3d3d3d] text-left">Date Commande</th>
                    <th class="p-3 border border-[#3d3d3d] text-left">Date Livraison</th>
                    <th class="p-3 border border-[#3d3d3d] text-left">Statut</th>
                    <th class="p-3 border border-[#3d3d3d] text-left">Actions</th>
                </tr>
                </thead>
                <tbody id="commandesTable">
                @foreach($commandes as $commande)
                    <tr class="hover:bg-[#3d3d3d] transition"
                        data-client="{{ strtolower($commande->nom) }}"
                        data-date-commande="{{ \Carbon\Carbon::parse($commande->date_commande)->format('Y-m-d') }}"
                        data-date-livraison="{{ $commande->date_livraison ? \Carbon\Carbon::parse($commande->date_livraison)->format('Y-m-d') : '' }}"
                        data-statut="{{ $commande->statut ?? 'En attente' }}">
                        <td class="p-3 border border-[#3d3d3d]">{{ $commande->nom }}</td>
                        <td class="p-3 border border-[#3d3d3d]">{{ \Carbon\Carbon::parse($commande->date_commande)->format('d/m/Y') }}</td>
                        <td class="p-3 border border-[#3d3d3d]">
                            {{ $commande->date_livraison ? \Carbon\Carbon::parse($commande->date_livraison)->format('d/m/Y') : 'Non livré' }}
                        </td>
                        <td class="p-3 border border-[#3d3d3d]">
                            @php
                                $statut = $commande->statut ?? 'En attente';
                                $statutClass = [
                                    'En attente' => 'statut-en-attente',
                                    'Validé' => 'statut-valide',
                                    'Annulé' => 'statut-annule'
                                ][$statut] ?? '';
                            @endphp
                            <span class="{{ $statutClass }} font-semibold">{{ $statut }}</span>
                        </td>
                        <td class="p-3 border border-[#3d3d3d]">
                            <div class="flex space-x-2">
                                <form action="{{ route('commandes.valider', $commande->idCommande) }}" method="get" class="inline">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="fas fa-check mr-1"></i> Valider
                                    </button>
                                </form>
                                <form action="{{ route('commandes.annulation', $commande->idCommande) }}" method="get" class="inline">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-times mr-1"></i> Annuler
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    // Votre script JavaScript ici (identique à la version précédente)
</script>

</body>
</html>
