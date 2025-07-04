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

                    <!-- Légende des jours fériés -->
                    <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Jours Fériés à Madagascar</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-red-500 rounded"></div>
                                <span>Nouvel An (1er janvier)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-purple-500 rounded"></div>
                                <span>Insurrection de 1947 (29 mars)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-green-500 rounded"></div>
                                <span>Pâques (variable)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-yellow-500 rounded"></div>
                                <span>Fête du Travail (1er mai)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-blue-500 rounded"></div>
                                <span>Ascension (variable)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-indigo-500 rounded"></div>
                                <span>Lundi de Pentecôte (variable)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-orange-500 rounded"></div>
                                <span>Fête de l'Indépendance (26 juin)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-pink-500 rounded"></div>
                                <span>Assomption (15 août)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-teal-500 rounded"></div>
                                <span>Toussaint (1er novembre)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="w-4 h-4 bg-red-600 rounded"></div>
                                <span>Noël (25 décembre)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>