<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Production Histogram - ToakaVary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a2e0e6c6f2.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            background-color: #0c0c0c;
            color: white;
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
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
        }
        .filter-container {
            background: #2d2d2d;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #3d3d3d;
            max-width: 800px;
            margin: 0 auto;
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
        .loading {
            text-align: center;
            padding: 20px;
            display: none;
            color: #cdb587;
        }
    </style>
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
<body class="min-h-screen flex">

@include('slidebar')

<!-- Main Content -->
<main class="flex-grow p-6 overflow-auto">
    <div class="main-content rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold gold-text mb-6 text-center">Histogramme de Production</h1>

        <div class="filter-container">
            <form id="filterForm" class="flex flex-col md:flex-row gap-4 items-end">
                <div class="w-full md:w-auto">
                    <label for="id_gamme" class="block text-gray-300 mb-2">Gamme</label>
                    <select id="id_gamme" name="id_gamme" class="input-field">
                        <option value="">Toutes les gammes</option>

                        <?php
                        $gammes = \App\Models\Gamme::all();
                        foreach ($gammes as $gamme) 
                        {
                            echo "<option value=\"{$gamme->id}\">{$gamme->nom}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="w-full md:w-auto">
                    <label for="date_debut" class="block text-gray-300 mb-2">Date début</label>
                    <input type="date" id="date_debut" name="date_debut" required class="input-field">
                </div>

                <div class="w-full md:w-auto">
                    <label for="date_fin" class="block text-gray-300 mb-2">Date fin</label>
                    <input type="date" id="date_fin" name="date_fin" required class="input-field">
                </div>

                <button type="submit" class="btn btn-primary w-full md:w-auto">
                    <i class="fas fa-filter mr-2"></i> Filtrer
                </button>
            </form>
        </div>

        <div class="loading" id="loading">
            <i class="fas fa-spinner fa-spin mr-2"></i> Chargement des données...
        </div>

        <div class="chart-container">
            <canvas id="productionChart"></canvas>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialiser les dates
        document.getElementById('date_debut').value = '2024-02-01';
        document.getElementById('date_fin').value = '2099-07-15';

        let productionChart = null;

        // Initialiser le graphique
        function initChart(dates, litreData, bouteilleData) {
            const ctx = document.getElementById('productionChart').getContext('2d');

            if (productionChart) {
                productionChart.destroy();
            }

            productionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: dates.map(date => new Date(date).toLocaleDateString()),
                    datasets: [
                        {
                            label: 'Bas de gamme - Litres',
                            data: litreData.gamme1,
                            backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Moyen de gamme - Litres',
                            data: litreData.gamme2,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Haut de gamme - Litres',
                            data: litreData.gamme3,
                            backgroundColor: 'rgba(255, 206, 86, 0.7)',
                            borderColor: 'rgba(255, 206, 86, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Total Bouteilles',
                            data: bouteilleData,
                            backgroundColor: 'rgba(255, 0, 0, 0.5)',
                            borderColor: 'rgb(247, 4, 4)',
                            borderWidth: 5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Litres',
                                color: '#cdb587'
                            },
                            grid: {
                                color: '#3d3d3d'
                            },
                            ticks: {
                                color: '#cdb587'
                            }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Bouteilles',
                                color: '#cdb587'
                            },
                            grid: {
                                drawOnChartArea: false,
                                color: '#3d3d3d'
                            },
                            ticks: {
                                color: '#cdb587'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date',
                                color: '#cdb587'
                            },
                            grid: {
                                color: '#3d3d3d'
                            },
                            ticks: {
                                color: '#cdb587'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            labels: {
                                color: '#cdb587'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += context.parsed.y.toLocaleString();
                                    label += context.datasetIndex < 3 ? ' litres' : ' bouteilles';
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Charger les données initiales
        function loadInitialData() {
            const formData = {
                id_gamme: document.getElementById('id_gamme').value,
                date_debut: document.getElementById('date_debut').value,
                date_fin: document.getElementById('date_fin').value
            };

            fetchData(formData);
        }

        // Fonction pour récupérer les données via AJAX
        function fetchData(formData) {
            document.getElementById('loading').style.display = 'block';

            fetch("{{ route('production.filter') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            })
                .then(response => response.json())
                .then(data => {
                    initChart(data.dates, data.litreData, data.bouteilleData);
                    document.getElementById('loading').style.display = 'none';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('loading').style.display = 'none';
                });
        }

        // Écouter la soumission du formulaire
        document.getElementById('filterForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = {
                id_gamme: this.id_gamme.value,
                date_debut: this.date_debut.value,
                date_fin: this.date_fin.value
            };

            fetchData(formData);
        });

        // Charger les données initiales
        loadInitialData();
    });
</script>
</body>
</html>
