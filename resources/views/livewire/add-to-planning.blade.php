<div class="max-w-3xl mx-auto mt-12 bg-white rounded-xl shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3A9 9 0 11 3 12a9 9 0 0115 6.71"/></svg>
        Ajouter des jours de travail
    </h2>

    @if (session()->has('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Mode d'ajout -->
        <div>
            <label class="block font-semibold mb-2 text-gray-700">Mode d'ajout</label>
            <select wire:model.live="mode" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="dates">Dates précises</option>
                <option value="recurrence">Récurrence</option>
            </select>
            @error('mode') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
        </div>

        <!-- Section Dates précises -->
        @if ($mode === 'dates')
            <div>
                <label class="block font-semibold mb-2 text-gray-700">Choisir les dates</label>
                <div class="space-y-2" id="dates-list">
                    @foreach ($dates as $i => $date)
                        <div class="flex items-center gap-2">
                            <input type="date" wire:model="dates.{{ $i }}" class="flex-1 rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            <select wire:model="date_types.{{ $i }}" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="teletravail">Télétravail</option>
                                <option value="sur_site">Sur site</option>
                            </select>
                            @if (count($dates) > 1)
                                <button type="button" wire:click="removeDateInput({{ $i }})" class="text-red-500 hover:text-red-700 text-lg" title="Supprimer cette date">&times;</button>
                            @endif
                        </div>
                        @error("dates.$i") <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                        @error("date_types.$i") <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    @endforeach
                    <button type="button" wire:click="addDateInput" class="text-indigo-500 hover:text-indigo-700 text-sm">+ Ajouter une date</button>
                </div>
            </div>
        @endif

        <!-- Section Récurrence -->
        @if ($mode === 'recurrence')
            <div>
                <label class="block font-semibold mb-2 text-gray-700">Sélectionnez les jours de la semaine</label>
                <div class="grid grid-cols-2 gap-2 mb-3">
                    @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'] as $day)
                        <div>
                            <label class="flex items-center gap-1 text-gray-600 mb-1">
                                <input type="checkbox" wire:model="recurrence_days" value="{{ $day }}" class="accent-indigo-500 rounded">
                                {{ $day }}
                            </label>
                            <select wire:model="recurrence_types.{{ $day }}" class="rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 w-full mt-1">
                                <option value="teletravail">Télétravail</option>
                                <option value="sur_site">Sur site</option>
                            </select>
                        </div>
                    @endforeach
                </div>
                @error('recurrence_days') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-semibold mb-1 text-gray-700">Date de début</label>
                        <input type="date" wire:model="recurrence_start" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('recurrence_start') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label class="block font-semibold mb-1 text-gray-700">Date de fin</label>
                        <input type="date" wire:model="recurrence_end" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('recurrence_end') <div class="text-red-600 text-sm">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        @endif

        <div class="flex justify-end pt-4">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold shadow">
                Valider
            </button>
        </div>
    </form>
</div>
