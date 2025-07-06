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
        .input-field {
            background-color: #2d2d2d;
            border: 1px solid #3d3d3d;
            color: white;
        }
        .input-field:focus {
            outline: none;
            border-color: #cdb587;
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
        .filter-form {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: flex-end;
        }
        .filter-group {
            flex: 1;
            min-width: 200px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #cdb587;
            font-weight: 500;
        }
        select, input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #3d3d3d;
            border-radius: 4px;
            font-size: 14px;
            background-color: #2d2d2d;
            color: white;
        }
        button {
            background-color: #cdb587;
            color: #1b1b1b;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #d9c9a3;
        }
        .loading {
            text-align: center;
            padding: 20px;
            display: none;
            color: #cdb587;
        }
    </style>
</head>
<body class="min-h-screen flex">

<!-- Sidebar -->
<div class="sidebar text-white p-5 flex flex-col">
    <img src="../img/logo_rhum_rice.png" alt="logo" class="w-48 mx-auto mb-8">
    <nav class="flex-1">
        <a href="/home" class="flex items-center py-2 px-3 rounded mb-2 bg-gray-800 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i> Dashboard
        </a>
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-industry mr-3 w-5 text-center"></i> Production
        </a>
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-boxes mr-3 w-5 text-center"></i> Stocks
        </a>
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-calendar mr-3 w-5 text-center"></i> Calendrier
        </a>
        <a href="/statForm" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-chart-bar mr-3 w-5 text-center"></i> Statistiques des ventes
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
        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i> Déconnexion
    </a>
</div>

<!-- Main Content -->
<main class="flex-grow p-6 overflow-auto">
    <div class="main-content rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold gold-text mb-6 text-center">Histogramme de Production</h1>

        <div class="filter-container">
            <form id="filterForm" class="filter-form">
                <div class="filter-group">
                    <label for="id_gamme">Gamme</label>
                    <select id="id_gamme" name="id_gamme" class="input-field">
                        <option value="">Toutes les gammes</option>
                        <option value="1">Gamme 1</option>
                        <option value="2">Gamme 2</option>
                        <option value="3">Gamme 3</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="date_debut">Date début</label>
                    <input type="date" id="date_debut" name="date_debut" required class="input-field">
                </div>

                <div class="filter-group">
                    <label for="date_fin">Date fin</label>
                    <input type="date" id="date_fin" name="date_fin" required class="input-field">
                </div>

                <button type="submit" class="flex items-center">
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
        document.getElementById('date_fin').value = '2025-07-15';

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
                            label: 'Gamme 1 - Litres',
                            data: litreData.gamme1,
                            backgroundColor: 'rgba(255, 99, 132, 0.7)',
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Gamme 2 - Litres',
                            data: litreData.gamme2,
                            backgroundColor: 'rgba(54, 162, 235, 0.7)',
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1
                        },
                        {
                            label: 'Gamme 3 - Litres',
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
