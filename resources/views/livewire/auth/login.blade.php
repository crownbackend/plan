<div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-lg">
    <h2 class="text-2xl font-bold text-center text-indigo-600 mb-6">Connexion</h2>

    <form wire:submit.prevent="login" class="space-y-4">
        <div>
            <label>Email</label>
            <input type="email" wire:model.defer="email"
                   class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label>Mot de passe</label>
            <input type="password" wire:model.defer="password"
                   class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded-xl hover:bg-indigo-700 transition">Se connecter
        </button>
    </form>

    <p class="text-center text-sm text-gray-600 mt-4">
        Pas encore de compte ?
        <a href="{{ route('register') }}" class="text-indigo-600 hover:underline">S'inscrire</a>
    </p>
</div>
