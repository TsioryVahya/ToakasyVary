<div class="w-64 sidebar-custom text-white p-5 flex flex-col">
    <img src="img/logo_rhum_rice.png" alt="logo" class="w-48 mx-auto mb-8">
    <nav class="flex-1">
        <a href="/home" class="flex items-center py-2 px-3 rounded mb-2 bg-gray-800 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>
            Dashboard
        </a>
        <a href="#" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-industry mr-3 w-5 text-center"></i>
            Production
        </a>
        <a href="{{ route('stocks.produits-finis.all') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-boxes mr-3 w-5 text-center"></i>
            Stocks
        </a>

        <a href="{{ route('production.calendar') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
            Calendrier
        </a>
        <a href="{{ route('commandes.preview') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
            Prevision de commandes
            
        </a>
        <a href="{{ route('commandes') }}" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
            Commandes des clients
        </a>
        <a href="/statForm" class="flex items-center py-2 px-3 rounded mb-2 hover:bg-opacity-20 hover:bg-white transition">
            <i class="fas fa-chart-bar mr-3 w-5 text-center"></i>
            Statistiques des ventes
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
        <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
        Déconnexion
    </a>
</div>
