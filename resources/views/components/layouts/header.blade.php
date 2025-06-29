<nav class="bg-white shadow-md px-6 py-4">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <!-- Logo -->
        <div class="text-2xl font-bold text-indigo-600">MonLogo</div>

        <!-- Menu desktop -->
        <div class="hidden md:flex space-x-6">
            <a href="#" class="text-gray-600 hover:text-indigo-600 transition">Accueil</a>
            <a href="#" class="text-gray-600 hover:text-indigo-600 transition">Services</a>
            <a href="#" class="text-gray-600 hover:text-indigo-600 transition">À propos</a>
            <a href="#" class="text-gray-600 hover:text-indigo-600 transition">Contact</a>
        </div>

        <!-- Bouton -->
        <div class="hidden md:block">
            <a href="{{ route('logout') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-xl shadow hover:bg-indigo-700 transition">Déconnexion</a>
        </div>

        <!-- Burger menu -->
        <div class="md:hidden">
            <button id="menu-btn" class="text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Menu mobile -->
    <div id="menu" class="md:hidden mt-4 space-y-2 hidden">
        <a href="#" class="block text-gray-700 hover:text-indigo-600 px-2">Accueil</a>
        <a href="#" class="block text-gray-700 hover:text-indigo-600 px-2">Services</a>
        <a href="#" class="block text-gray-700 hover:text-indigo-600 px-2">À propos</a>
        <a href="#" class="block text-gray-700 hover:text-indigo-600 px-2">Contact</a>
        <a href="#" class="block bg-indigo-600 text-white text-center py-2 rounded-xl mt-2">Déconnexion</a>
    </div>

</nav>
