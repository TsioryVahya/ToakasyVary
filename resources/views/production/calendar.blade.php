<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendrier - ToakaVary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/calendar.js'])
</head>
<body class="bg-light-green font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="bg-primary-green text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold">ToakaVary</h1>
                <nav>
                    <a href="{{ route('home') }}" class="text-white hover:underline">Accueil</a>
                </nav>
            </div>
        </header>

        <main class="flex-grow">
            <div class="container mx-auto px-4 py-8">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-3xl font-bold text-gray-800">Calendrier</h1>
                        <div class="flex items-center space-x-4">
                            <button onclick="previousMonth()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                ← Mois précédent
                            </button>
                            <h2 id="monthYear" class="text-xl font-semibold text-gray-700"></h2>
                            <button onclick="nextMonth()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                Mois suivant →
                            </button>
                        </div>
                    </div>

                    <!-- Calendrier -->
                    <div class="grid grid-cols-7 gap-2 mb-4">
                        <!-- En-têtes des jours -->
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Dim</div>
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Lun</div>
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Mar</div>
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Mer</div>
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Jeu</div>
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Ven</div>
                        <div class="p-3 text-center font-semibold text-gray-600 bg-gray-100 rounded">Sam</div>
                    </div>

                    <!-- Grille du calendrier -->
                    <div id="calendar-grid" class="grid grid-cols-7 gap-2">
                        <!-- Les jours seront générés par JavaScript -->
                    </div>

                
                </div>
            </div>
        </main>
    </div>
</body>
</html>