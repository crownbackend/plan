<div class="space-y-4 mt-6">
    <div class="ml-auto">
        <a
            href="{{ route('salles.creer') }}"
            class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 shadow transition"
        >
            Créer une salle
        </a>

        <a
            href="{{ route('salles.reservation') }}"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 shadow transition"
        >
            Réservation de créneaux
        </a>
    </div>
    <div>
        <label>Salle</label>
        <select wire:model.live="room_id" class="w-full border rounded p-2">
            <option value="">-- Choisir une salle --</option>
            @foreach($rooms as $room)
                <option value="{{ $room->id }}">{{ $room->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label>Date</label>
        <input type="date" wire:model.live="date" class="w-full border rounded p-2">
    </div>

    <div>
        <h3 class="font-semibold">Réservations ce jour-là :</h3>
        @forelse($reservations as $res)
            <div class="p-2 border rounded">
                <strong>{{ $res->title }}</strong> par {{ $res->user->name }}<br>
                De {{ \Carbon\Carbon::parse($res->start_time)->format('H:i') }} à {{ \Carbon\Carbon::parse($res->end_time)->format('H:i') }}
            </div>
        @empty
            <div class="text-gray-500">Aucune réservation ce jour-là.</div>
        @endforelse
    </div>
</div>

