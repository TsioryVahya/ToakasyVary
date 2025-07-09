<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suivi du Stock - Toaka Vary</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#43766C',
                        secondary: '#F8FAE5',
                        accent: '#76453B',
                        dark: '#76453B',
                        gold: {
                            50: '#fefce8',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        }
                    }
                }
            }
        }
    </script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        .text-gold { color: #FFD700; }
        .bg-gold { background-color: #FFD700; }
        .border-gold { border-color: #FFD700; }
    </style>
</head>
<body class="bg-dark text-secondary">
    @include('real_sidebar')
    <div class="lg:ml-64 min-h-screen">
        <!-- Header -->
        <header class="bg-dark border-b border-gold p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gold">Suivi du Stock</h1>
                    <p class="text-accent">Visualisez l'état et les mouvements du stock</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-secondary">{{ date('d/m/Y') }}</span>
                    <div class="w-8 h-8 bg-gradient-to-br from-gold to-accent rounded-full flex items-center justify-center">
                        <span class="text-dark font-semibold text-xs">TV</span>
                    </div>
                </div>
            </div>
        </header>
        <main class="p-8">
            <div class="bg-dark bg-opacity-90 border border-gold rounded-xl p-8">
                <h2 class="text-xl font-semibold text-gold mb-4">Tableau de suivi du stock</h2>
                <p class="text-secondary mb-4">(Ici, vous pouvez afficher un tableau ou des graphiques de suivi du stock...)</p>
                <!-- Exemple de tableau -->
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-dark text-secondary rounded-lg border border-gold">
                        <thead class="bg-dark">
                            <tr>
                                <th class="px-4 py-2 text-left text-gold border-b border-gold">Produit</th>
                                <th class="px-4 py-2 text-left text-gold border-b border-gold">Quantité</th>
                                <th class="px-4 py-2 text-left text-gold border-b border-gold">Seuil minimum</th>
                                <th class="px-4 py-2 text-left text-gold border-b border-gold">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hover:bg-dark hover:text-gold transition-colors">
                                <td class="px-4 py-2">Bière Blonde</td>
                                <td class="px-4 py-2">1 200 L</td>
                                <td class="px-4 py-2">1 000 L</td>
                                <td class="px-4 py-2"><span class="px-2 py-1 rounded border border-gold text-gold">OK</span></td>
                            </tr>
                            <tr class="hover:bg-dark hover:text-gold transition-colors">
                                <td class="px-4 py-2">Bière Ambrée</td>
                                <td class="px-4 py-2">800 L</td>
                                <td class="px-4 py-2">1 000 L</td>
                                <td class="px-4 py-2"><span class="px-2 py-1 rounded border border-gold text-gold">Alerte</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
