@php
    $currentRoute = Route::currentRouteName();
    $currentPath = Request::path();
@endphp

<!-- Sidebar Component -->
<div class="bg-gray-900 text-white w-64 min-h-screen fixed left-0 top-0 z-50 transform transition-transform duration-300 ease-in-out">
    <!-- Logo Section -->
    <div class="flex items-center justify-center py-6 px-4 border-b border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-gray-900" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-yellow-400">Toaka Vary</h1>
                <p class="text-xs text-gray-400">Brasserie Malgache</p>
            </div>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="mt-6 px-4">
        <div class="space-y-2">
            <!-- Dashboard -->
            <a href="{{ route('home') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-700 {{ request()->routeIs('home') ? 'text-yellow-400 bg-gray-800 border-l-4 border-yellow-400' : 'text-gray-300 hover:text-yellow-400' }}">
                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                </svg>
                <span class="font-medium">Dashboard</span>
            </a>

            <!-- Production -->
            <a href="{{ route('lot_productions.index') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-700 group {{ request()->routeIs('lot_productions.*') ? 'text-yellow-400 bg-gray-800 border-l-4 border-yellow-400' : 'text-gray-300 hover:text-yellow-400' }}">
                <svg class="w-5 h-5 mr-3 group-hover:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                <span class="font-medium">Production</span>
            </a>

            <!-- Stock Dropdown -->
            <div x-data="{ open: {{ request()->routeIs('stocks.produits-finis.*') || request()->routeIs('matieres.*') || request()->routeIs('mouvementsStockMatierePremiere.*') || request()->routeIs('suivistock') ? 'true' : 'false' }} }" class="relative">
                <button @click="open = !open" 
                        class="flex items-center w-full px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-700 group focus:outline-none {{ request()->routeIs('stocks.produits-finis.*') || request()->routeIs('matieres.*') || request()->routeIs('mouvementsStockMatierePremiere.*') || request()->routeIs('suivistock') ? 'text-yellow-400 bg-gray-800 border-l-4 border-yellow-400' : 'text-gray-300 hover:text-yellow-400' }}">
                    <svg class="w-5 h-5 mr-3 group-hover:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20 7h-3V6a4 4 0 0 0-4-4h-2a4 4 0 0 0-4 4v1H4a1 1 0 0 0-1 1v11a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3V8a1 1 0 0 0-1-1zM9 6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v1H9V6zm9 13a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V9h2v1a1 1 0 0 0 2 0V9h6v1a1 1 0 0 0 2 0V9h2v10z"/>
                    </svg>
                    <span class="font-medium flex-1 text-left">Stocks</span>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <!-- Sous-menu Stock -->
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="ml-6 mt-2 space-y-1">
                    <a href="{{ route('stocks.produits-finis.all') }}" 
                       class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('stocks.produits-finis.*') ? 'text-yellow-400 bg-gray-700' : 'text-gray-400 hover:text-yellow-400 hover:bg-gray-700' }}">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        Produits Finis
                    </a>
                    <a href="{{ route('matieres.index') }}" 
                       class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('matieres.*') ? 'text-yellow-400 bg-gray-700' : 'text-gray-400 hover:text-yellow-400 hover:bg-gray-700' }}">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Matières Premières
                    </a>
                    <a href="{{ route('mouvementsStockMatierePremiere.index') }}" 
                       class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('mouvementsStockMatierePremiere.*') ? 'text-yellow-400 bg-gray-700' : 'text-gray-400 hover:text-yellow-400 hover:bg-gray-700' }}">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2M8 4V2a2 2 0 012-2h4a2 2 0 012 2v2M8 4h8"/>
                        </svg>
                        Mouvements des Stock
                    </a>
                    <a href="{{ route('suivistock') }}" 
                       class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('suivistock') ? 'text-yellow-400 bg-gray-700' : 'text-gray-400 hover:text-yellow-400 hover:bg-gray-700' }}">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Suivi des Stocks
                    </a>
                </div>
            </div>

            <!-- Calendrier -->
            <a href="{{ route('production.calendar') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-700 group {{ request()->routeIs('production.calendar') ? 'text-yellow-400 bg-gray-800 border-l-4 border-yellow-400' : 'text-gray-300 hover:text-yellow-400' }}">
                <svg class="w-5 h-5 mr-3 group-hover:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                </svg>
                <span class="font-medium">Calendrier</span>
            </a>

            <!-- Commandes -->
            <a href="{{ route('commandes') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-700 group {{ request()->routeIs('commandes*') ? 'text-yellow-400 bg-gray-800 border-l-4 border-yellow-400' : 'text-gray-300 hover:text-yellow-400' }}">
                <svg class="w-5 h-5 mr-3 group-hover:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M7 4V2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v2h4a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V8H3a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h4zM9 3v1h6V3H9zm-3 5v11h12V8H6zm3 3h6v2H9v-2zm0 4h6v2H9v-2z"/>
                </svg>
                <span class="font-medium">Commandes</span>
            </a>

            <div x-data="{ open: {{ request()->is('statForm') || request()->routeIs('stat_vente') || request()->routeIs('production.histogram') || request()->routeIs('production.filter') ? 'true' : 'false' }} }" class="relative">
                <button @click="open = !open" 
                        class="flex items-center w-full px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-700 group focus:outline-none {{ request()->is('statForm') || request()->routeIs('stat_vente') || request()->routeIs('production.histogram') || request()->routeIs('production.filter') ? 'text-yellow-400 bg-gray-800 border-l-4 border-yellow-400' : 'text-gray-300 hover:text-yellow-400' }}">
                    <svg class="w-5 h-5 mr-3 group-hover:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4zm2.5 2.25l1.25-1.25-4.5-4.5L14 15.75l-3.25-3.25-1.25 1.25 4.5 4.5 2.25-2.25z"/>
                    </svg>
                    <span class="font-medium flex-1 text-left">Statistiques</span>
                    <svg :class="{'rotate-180': open}" class="w-4 h-4 ml-auto transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                
                <div x-show="open" 
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 transform scale-100"
                     x-transition:leave-end="opacity-0 transform scale-95"
                     class="ml-6 mt-2 space-y-1">
                    <a href="/statForm" 
                       class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->is('statForm') || request()->routeIs('stat_vente') ? 'text-yellow-400 bg-gray-700' : 'text-gray-400 hover:text-yellow-400 hover:bg-gray-700' }}">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Statistiques de Vente
                    </a>
                    <a href="{{ route('production.histogram') }}" 
                       class="flex items-center px-4 py-2 text-sm rounded-lg transition-all duration-200 {{ request()->routeIs('production.histogram') || request()->routeIs('production.filter') ? 'text-yellow-400 bg-gray-700' : 'text-gray-400 hover:text-yellow-400 hover:bg-gray-700' }}">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        Statistiques de Production
                    </a>
                </div>
            </div>

            <!-- Historique -->
            <a href="{{ route('historique_vente') }}" 
               class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-700 group {{ request()->routeIs('historique_vente') ? 'text-yellow-400 bg-gray-800 border-l-4 border-yellow-400' : 'text-gray-300 hover:text-yellow-400' }}">
                <svg class="w-5 h-5 mr-3 group-hover:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                </svg>
                <span class="font-medium">Historique de ventes</span>
            </a>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-700 my-6"></div>

        <!-- Settings Section -->
        <div class="space-y-2">
            <!-- Paramètres -->
            <a href="#" class="flex items-center px-4 py-3 text-gray-300 rounded-lg transition-all duration-200 hover:bg-gray-800 hover:text-yellow-400 group">
                <svg class="w-5 h-5 mr-3 group-hover:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 15.5A3.5 3.5 0 0 1 8.5 12A3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5 3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97 0-.33-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.31-.61-.22l-2.49 1c-.52-.39-1.06-.73-1.69-.98l-.37-2.65A.506.506 0 0 0 14 2h-4c-.25 0-.46.18-.5.42l-.37 2.65c-.63.25-1.17.59-1.69.98l-2.49-1c-.22-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1 0 .33.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1.01c.52.4 1.06.74 1.69.99l.37 2.65c.04.24.25.42.5.42h4c.25 0 .46-.18.5-.42l.37-2.65c.63-.26 1.17-.59 1.69-.99l2.49 1.01c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64l-2.11-1.66Z"/>
                </svg>
                <span class="font-medium">Paramètres</span>
            </a>

            <!-- Aide -->
            <a href="#" class="flex items-center px-4 py-3 text-gray-300 rounded-lg transition-all duration-200 hover:bg-gray-800 hover:text-yellow-400 group">
                <svg class="w-5 h-5 mr-3 group-hover:text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z"/>
                </svg>
                <span class="font-medium">Aide</span>
            </a>
        </div>
    </nav>

    <!-- User Profile Section -->
    <div class="absolute bottom-0 w-full p-4 border-t border-gray-700">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center">
                <span class="text-gray-900 font-semibold text-sm">TV</span>
            </div>
            <div class="flex-1">
                <p class="text-sm font-medium text-white">Toaka Vary Admin</p>
                <p class="text-xs text-gray-400">Administrateur</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 hover:text-yellow-400 transition-colors duration-200">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M16 17v-3H9v-4h7V7l5 5-5 5zM14 2a2 2 0 012 2v2h-2V4H4v16h10v-2h2v2a2 2 0 01-2 2H4a2 2 0 01-2-2V4a2 2 0 012-2h10z"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Mobile Toggle Button -->
<button id="sidebar-toggle" class="lg:hidden fixed top-4 left-4 z-50 bg-gray-900 text-yellow-400 p-2 rounded-lg shadow-lg">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
        <path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
    </svg>
</button>

<script>
document.getElementById('sidebar-toggle').addEventListener('click', function() {
    const sidebar = document.querySelector('.bg-gray-900');
    sidebar.classList.toggle('-translate-x-full');
});
</script>

<!-- Ajouter Alpine.js pour le dropdown -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>