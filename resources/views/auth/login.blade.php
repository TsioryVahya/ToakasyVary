<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion - ToakaVary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light-green font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center bg-light-green">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-primary-green text-center mb-6">Connexion</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-primary-green">Adresse e-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    @error('email')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-primary-green">Mot de passe</label>
                    <input id="password" type="password" name="password" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    @error('password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-green shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-primary-green">Se souvenir de moi</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-primary-green hover:underline" href="{{ route('password.request') }}">
                            Mot de passe oublié ?
                        </a>
                    @endif
                </div>

                <div>
                    <button type="submit" class="w-full bg-primary-green text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition duration-200">
                        Se connecter
                    </button>
                </div>
            </form>

            <p class="mt-4 text-center text-sm text-primary-green">
                Pas de compte ? <a href="{{ route('register') }}" class="hover:underline">Inscrivez-vous</a>
            </p>
        </div>
    </div>
</body>
</html>