<div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold text-center text-indigo-600 mb-6">Inscription</h2>

    <form wire:submit="register" class="space-y-4">
        <div>
            <label>Nom</label>
            <input type="text" wire:model="name"
                   class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label>Email</label>
            <input type="email" wire:model="email"
                   class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label>Mot de passe</label>
            <input type="password" wire:model="password"
                   class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-xl hover:bg-indigo-700 transition">S'inscrire
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-4">
        Vous avez déjà un compte ?
        <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Se connecter</a>
    </p>
</div>
