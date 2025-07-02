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

    public $search = '';
    public $startDate;

    protected $queryString = ['search', 'startDate'];

    public function mount()
    {
        $this->startDate = $this->startDate ?? Carbon::now()->startOfWeek()->format('Y-m-d');
    }

    public function updated($property)
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

    public function exportCsv()
    {
        $users = $this->getFilteredUsers();
        $startDate = Carbon::parse($this->startDate);

        // Récupère les jours fériés dynamiquement
        $joursFeries = $this->getJoursFeries($startDate->year);

        $response = new StreamedResponse(function () use ($users, $startDate, $joursFeries) {
            $handle = fopen('php://output', 'w');

            $header = ['Utilisateur'];
            // On boucle sur 7 jours mais on n’ajoute QUE lundi à vendredi hors férié
            for ($i = 0; $i < 7; $i++) {
                $jour = $startDate->copy()->addDays($i);
                if ($jour->isWeekend() || in_array($jour->format('Y-m-d'), $joursFeries)) continue;
                $header[] = $jour->translatedFormat('D d/m');
            }
            fputcsv($handle, $header);

            foreach ($users as $user) {
                $row = [$user->name];
                for ($i = 0; $i < 7; $i++) {
                    $jour = $startDate->copy()->addDays($i);
                    if ($jour->isWeekend() || in_array($jour->format('Y-m-d'), $joursFeries)) continue;
                    $date = $jour->format('Y-m-d');
                    $status = optional($user->workLocations->firstWhere('date', $date))->location_type;
                    $icon = match ($status) {
                        'teletravail' => '🏡',
                        'sur_site' => '🏢',
                        default => '-',
                    };
                    $row[] = $icon;
                }
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $filename = 'planning_' . $startDate->format('Ymd') . '.csv';

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', "attachment; filename=\"$filename\"");

        return $response;
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
            ->paginate(20);

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

    // Retourne un tableau des jours fériés pour une année (France métropolitaine)
    public function getJoursFeries($annee)
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
