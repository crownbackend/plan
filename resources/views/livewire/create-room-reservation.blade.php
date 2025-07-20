<div class="space-y-4">
    @if (session()->has('success'))
        <div class="text-green-600 font-semibold">{{ session('success') }}</div>
    @endif

    <div>
        <label>Nom de la salle</label>
        <select wire:model="room_id" class="w-full border rounded p-2">
            <option value="">-- Choisir une salle --</option>
            @foreach($rooms as $room)
                <option value="{{ $room->id }}">
                    @if($room->capacity)
                        {{ $room->name }} ({{ $room->capacity }} places)
                    @else
                        {{ $room->name }}
                    @endif
                </option>
            @endforeach
        </select>
        @error('room_id') <div class="text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label>Titre</label>
        <input type="text" wire:model="title" class="w-full border rounded p-2">
        @error('title') <div class="text-red-600">{{ $message }}</div> @enderror
    </div>

    <div>
        <label>Description</label>
        <textarea wire:model="description" class="w-full border rounded p-2"></textarea>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label>Date & heure de début</label>
            <input type="datetime-local" wire:model="start_time" class="w-full border rounded p-2">
            @error('start_time') <div class="text-red-600">{{ $message }}</div> @enderror
        </div>
        <div>
            <label>Date & heure de fin</label>
            <input type="datetime-local" wire:model="end_time" class="w-full border rounded p-2">
            @error('end_time') <div class="text-red-600">{{ $message }}</div> @enderror
        </div>
    </div>

    <div>
        <label>Participants</label>
        <select wire:model="attendees" multiple class="w-full border rounded p-2">
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
        @error('attendees') <div class="text-red-600">{{ $message }}</div> @enderror
    </div>

    <button wire:click="save" class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700">
        Réserver
    </button>
</div>
