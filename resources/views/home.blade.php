<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accueil - ToakaVary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light-green font-sans antialiased">
    <div class="min-h-screen flex flex-col">
        <header class="bg-primary-green text-white p-4">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-2xl font-bold">ToakaVary</h1>
                <nav>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="text-white hover:underline">
                        Déconnexion
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </nav>
            </div>
        </header>

        <main class="flex-grow container mx-auto p-6">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-primary-green mb-4">Bienvenue, {{ auth()->user()->name }} !</h2>
                <a href="/statForm"><p class="text-gray-700">stat vente</p></a>
                <a href="{{ route('historique_vente') }}"><p class="text-gray-700">Historique vente</p></a>
                <p class="text-gray-700">Ceci est la page d'accueil de ToakaVary. Vous êtes maintenant connecté.</p>
            </div>
        </main>

        <footer class="bg-primary-green text-white p-4 text-center">
            <p>© {{ date('Y') }} ToakaVary. Tous droits réservés.</p>
        </footer>
    </div>
</body>
</html>
