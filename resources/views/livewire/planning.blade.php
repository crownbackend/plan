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

        <div>
            @php
                $anneeAffichee = $startDate ? \Carbon\Carbon::parse($startDate)->year : now()->year;
            @endphp
            <span class="inline-block px-4 py-2 rounded bg-gray-100 text-gray-700 font-semibold shadow border border-gray-300">
                Année : {{ $anneeAffichee }}
            </span>
        </div>
        <div class="ml-auto">
            <a
                href="{{ route('ajouter.dates') }}"
                class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700 shadow transition"
            >
                Ajouter mes jours
            </a>
        </div>
    </div>
    <!-- 🟡 Notice explicative -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 text-sm text-yellow-800 rounded shadow-sm">
        <strong>🛠️ Modifier un jour existant ?</strong><br>
        Pour changer un jour déjà renseigné (par exemple passer de télétravail à sur site),
        allez sur <a href="{{ route('ajouter.dates') }}" class="underline text-yellow-700 font-semibold hover:text-yellow-900">la page d’ajout</a>,
        sélectionnez le <strong>mode « dates »</strong>, puis choisissez la date concernée.
        Le système mettra à jour l’entrée si elle existe, sinon il la créera.
    </div>
    <div class="overflow-auto border rounded shadow-sm">
        <table class="table-auto w-full text-sm border-collapse border border-gray-300">
            <thead class="bg-gray-100">
            <tr>
                <th class="border border-gray-300 p-2 text-left">Utilisateur</th>
                @php
                    function joursFeries($annee) {
                        $dates = [
                            "$annee-01-01", "$annee-05-01", "$annee-05-08", "$annee-07-14",
                            "$annee-08-15", "$annee-11-01", "$annee-11-11", "$annee-12-25"
                        ];
                        $paques = date('Y-m-d', easter_date($annee));
                        $dates[] = date('Y-m-d', strtotime("$paques +1 day")); // Lundi de Pâques
                        $dates[] = date('Y-m-d', strtotime("$paques +39 days")); // Ascension
                        $dates[] = date('Y-m-d', strtotime("$paques +50 days")); // Lundi de Pentecôte
                        return $dates;
                    }
                    $joursFeries = joursFeries(\Carbon\Carbon::parse($startDate)->year);
                    $joursAffiches = [];
                    foreach(range(0, 6) as $i) {
                        $jour = \Carbon\Carbon::parse($startDate)->addDays($i);
                        // On ne prend que lundi à vendredi
                        if ($jour->isWeekend()) continue;
                        $joursAffiches[] = [
                            'date' => $jour,
                            'ferie' => in_array($jour->format('Y-m-d'), $joursFeries)
                        ];
                    }
                @endphp
                @foreach ($joursAffiches as $item)
                    @php
                        $jour = $item['date'];
                        $ferie = $item['ferie'];
                    @endphp
                    <th class="border border-gray-300 p-2 text-center {{ $ferie ? 'bg-gray-50 text-gray-400 italic' : '' }}">
                        {{ $jour->translatedFormat('D d/m') }}
                        @if($ferie)
                            <span title="Jour férié">🎉</span>
                        @endif
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @forelse ($users as $user)
                <tr class="hover:bg-gray-50">
                    <td class="border border-gray-300 p-2 font-semibold">{{ $user->name }}</td>
                    @foreach ($joursAffiches as $item)
                        @php
                            $jour = $item['date'];
                            $date = $jour->format('Y-m-d');
                            $ferie = $item['ferie'];
                            $status = optional($user->workLocations->firstWhere('date', $date))->location_type;
                        @endphp
                        <td class="border border-gray-300 p-2 text-center text-lg {{ $ferie ? 'bg-gray-50 text-gray-400 italic' : '' }}">
                            @if ($ferie)
                                <span title="Jour férié">—</span>
                            @elseif ($status === 'teletravail')
                                🏡 Télétravaille
                            @elseif ($status === 'sur_site')
                                🏢 Sur site
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
</div>
