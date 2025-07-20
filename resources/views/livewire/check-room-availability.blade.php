<div class="space-y-6 mt-6">

    <!-- Boutons -->
    <div class="flex justify-end gap-4">
        <a href="{{ route('salles.creer') }}"
           class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 shadow transition">
            ➕ Créer une salle
        </a>
        <a href="{{ route('salles.reservation') }}"
           class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 shadow transition">
            📅 Réserver un créneau
        </a>
        <button wire:click="exportCsv"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow transition">
            📤 Export CSV
        </button>
        <button onclick="window.print()"
                class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 shadow transition">
            🖨️ Imprimer
        </button>
    </div>

    <!-- Filtres -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block font-semibold mb-1 text-gray-700">Salle</label>
            <select wire:model.live="room_id" class="w-full border rounded p-2">
                <option value="">Toutes les salles</option>
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1 text-gray-700">Date</label>
            <input type="date" wire:model.live="date" class="w-full border rounded p-2">
        </div>

        <div>
            <label class="block font-semibold mb-1 text-gray-700">À partir de (heure)</label>
            <input type="time" wire:model.live="hour" class="w-full border rounded p-2">
        </div>
    </div>

    <!-- Résultats -->
    <div>
        <h3 class="font-semibold text-lg text-gray-800 mb-2">
            Réservations pour {{ \Carbon\Carbon::parse($date)->translatedFormat('l d F Y') }}
            @if($hour) à partir de {{ $hour }}h @endif
        </h3>

        @forelse($reservations as $res)
            <div class="p-4 bg-white border rounded shadow-sm mb-2">
                <div class="font-semibold text-indigo-600">{{ $res->title }}</div>
                <div>
                    🏢 Salle : <strong>{{ $res->room->name }}</strong><br>
                    👤 Réservé par : {{ $res->user->name }}<br>
                    ⏰ De {{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }}
                    à {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }}
                </div>
            </div>
        @empty
            <div class="text-gray-500">Aucune réservation pour ces critères.</div>
        @endforelse
    </div>
</div>
