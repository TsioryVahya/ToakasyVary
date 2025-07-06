<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Production Histogram - ToakaVary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .chart-container {
            position: relative;
            height: 400px;
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
        }
        .filter-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
            color: #2e8555;
            font-weight: 500;
        }
        select, input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        button {
            background-color: #2e8555;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #1e6b45;
        }
        .loading {
            text-align: center;
            padding: 20px;
            display: none;
        }
    </style>
</head>
<body class="bg-light-green font-sans antialiased">
    <div class="container">
        <h1 style="text-align: center; color: #2e8555; margin-bottom: 20px;">Production Histogram</h1>

        <div class="filter-container">
            <form id="filterForm" class="filter-form">
                <div class="filter-group">
                    <label for="id_gamme">Gamme</label>
                    <!-- <select id="id_gamme" name="id_gamme" required>
                        @foreach($gammes as $gamme)
                            <option value="{{ $gamme }}">Gamme {{ $gamme }}</option>
                        @endforeach
                        <option value="1">1</option>
                    </select> -->
                    <!-- Dans la partie select de gamme -->
                        <select id="id_gamme" name="id_gamme">
                            <option value="">Toutes les gammes</option> <!-- Option vide pour tout afficher -->
                            <option value="1">Gamme 1</option>
                            <option value="2">Gamme 2</option>
                            <option value="3">Gamme 3</option>
                        </select>
                </div>

                <div class="filter-group">
                    <label for="date_debut">Date début</label>
                    <input type="date" id="date_debut" name="date_debut" required>
                </div>

                <div class="filter-group">
                    <label for="date_fin">Date fin</label>
                    <input type="date" id="date_fin" name="date_fin" required>
                </div>

                <button type="submit">Filtrer</button>
            </form>
        </div>

        <div class="loading" id="loading">
            Chargement des données...
        </div>

        <div class="chart-container">
            <canvas id="productionChart"></canvas>
        </div>
    </div>

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
