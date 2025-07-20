<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Planning extends Component
{
    use WithPagination, WithoutUrlPagination;

    public string $search = '';
    public $startDate;

    protected $queryString = ['search', 'startDate'];

    public function mount(): void
    {
        $dayOfWeek = Carbon::now()->dayOfWeekIso; // 1 = lundi, 7 = dimanche
        if ($dayOfWeek <= 5) {
            $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        } else {
            $this->startDate = Carbon::now()->addWeek()->startOfWeek()->format('Y-m-d');
        }
    }

    public function updated($property): void
    {
        if ($property === 'search') {
            $this->search = substr($this->search, 0, 100); // Limite à 100 caractères
        }
        if ($property === 'startDate') {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->startDate)) {
                $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d');
            }
        }
        if (in_array($property, ['search', 'startDate'])) {
            $this->resetPage();
        }
    }

    public function getFilteredUsers()
    {
        return User::with('workLocations')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('name')
            ->get();
    }

    public function render()
    {
        app()->setLocale('fr');
        $users = User::with('workLocations')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('name')
            ->get();

        $startDate = Carbon::parse($this->startDate);
        $joursFeries = $this->getJoursFeries($startDate->year);

        // Prépare les jours à afficher (lundi à vendredi hors fériés)
        $joursAffiches = [];
        for ($i = 0; $i < 7; $i++) {
            $jour = $startDate->copy()->addDays($i);
            if ($jour->isWeekend() || in_array($jour->format('Y-m-d'), $joursFeries)) continue;
            $joursAffiches[] = $jour->copy();
        }

        return view('livewire.planning', [
            'users' => $users,
            'joursAffiches' => $joursAffiches,
        ]);
    }

    public function getJoursFeries($annee): array
    {
        // Dates fixes
        $dates = [
            "$annee-01-01", // Jour de l'an
            "$annee-05-01", // Fête du Travail
            "$annee-05-08", // Victoire 1945
            "$annee-07-14", // Fête nationale
            "$annee-08-15", // Assomption
            "$annee-11-01", // Toussaint
            "$annee-11-11", // Armistice
            "$annee-12-25", // Noël
        ];

        // Calculs pour Pâques et jours fériés mobiles
        $paques = date('Y-m-d', easter_date($annee));
        $dates[] = date('Y-m-d', strtotime("$paques +1 day")); // Lundi de Pâques
        $dates[] = date('Y-m-d', strtotime("$paques +39 days")); // Ascension
        $dates[] = date('Y-m-d', strtotime("$paques +50 days")); // Lundi de Pentecôte

        return $dates;
    }
}
