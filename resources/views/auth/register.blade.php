<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription - ToakaVary</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-light-green font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center bg-light-green">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl font-bold text-primary-green text-center mb-6">Inscription</h2>

            @if(session('success'))
                <div class="mb-4 p-2 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-2 bg-red-100 text-red-800 rounded">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-primary-green">Nom</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    @error('name')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-primary-green">Adresse e-mail</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50"
                    pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
                @error('email')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="id_employe" class="block text-sm font-medium text-primary-green">Employé (facultatif)</label>
                    <select id="id_employe" name="id_employe" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                        <option value="">Aucun</option>
                        @foreach (\App\Models\Employe::all() as $employe)
                            <option value="{{ $employe->id }}" {{ old('id_employe') == $employe->id ? 'selected' : '' }}>{{ $employe->nom }}</option>
                        @endforeach
                    </select>
                    @error('id_employe')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-primary-green">Mot de passe</label>
                    <input id="password" type="password" name="password" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                    @error('password')
                        <span class="text-red-600 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-primary-green">Confirmer le mot de passe</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-green focus:ring focus:ring-primary-green focus:ring-opacity-50">
                </div>

                <div>
                    <button type="submit" class="w-full bg-white text-primary-green border border-primary-green font-bold py-2 px-4 rounded hover:bg-primary-green hover:text-white transition duration-200">
                        S'inscrire
                    </button>
                </div>
            </form>

            <p class="mt-4 text-center text-sm text-primary-green">
                Déjà un compte ? <a href="{{ route('login') }}" class="hover:underline">Connectez-vous</a>
            </p>
        </div>
    </div>
</body>
</html>