<div class="space-y-4">
    @if (session()->has('success'))
        <div class="text-green-600 font-semibold">{{ session('success') }}</div>
    @endif

    <div>
        <label class="block font-semibold">Nom de la salle *</label>
        <input type="text" wire:model="name" class="w-full border rounded p-2">
        @error('name') <div class="text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block font-semibold">Capacité</label>
        <input type="number" wire:model="capacity" class="w-full border rounded p-2">
        @error('capacity') <div class="text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label class="block font-semibold">Emplacement</label>
        <input type="text" wire:model="location" class="w-full border rounded p-2">
        @error('location') <div class="text-red-600">{{ $message }}</div> @enderror
    </div>

    <button wire:click="save" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
        Créer la salle
    </button>
</div>
