<div class="space-y-4">
    <div class="flex flex-wrap gap-4 items-end">
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700">Rechercher un utilisateur</label>
            <input
                id="search"
                type="text"
                wire:model.live.debounce.500ms="search"
                placeholder="Nom"
                class="border rounded px-3 py-2 w-48 shadow-sm focus:ring focus:ring-blue-200"
            />
        </div>

        <div>
            <label for="startDate" class="block text-sm font-medium text-gray-700">Début de la semaine</label>
            <input
                id="startDate"
                type="date"
                wire:model.live="startDate"
                class="border rounded px-3 py-2 shadow-sm focus:ring focus:ring-blue-200"
            />
        </div>

        <div class="ml-auto">
            <button
                wire:click="exportCsv"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow"
            >
                Export CSV
            </button>
        </div>
    </div>

    <div class="overflow-auto border rounded shadow-sm">
        <table class="table-auto w-full text-sm border-collapse border border-gray-300">
            <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 p-2 text-left">Utilisateur</th>
                @foreach (range(0, 6) as $i)
                    <th class="border border-gray-300 p-2 text-center">
                        {{ \Carbon\Carbon::parse($startDate)->addDays($i)->translatedFormat('D d/m') }}
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @forelse ($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 p-2 font-semibold">{{ $user->name }}</td>
                    @foreach (range(0, 6) as $i)
                        @php
                            $date = \Carbon\Carbon::parse($startDate)->addDays($i)->format('Y-m-d');
                            $status = optional($user->workLocations->firstWhere('date', $date))->location_type;
                        @endphp
                        <td class="border border-gray-300 p-2 text-center text-lg">
                            @if ($status === 'teletravail')
                                🏡
                            @elseif ($status === 'sur_site')
                                🏢
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="p-4 text-center text-gray-500">Aucun utilisateur trouvé.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
