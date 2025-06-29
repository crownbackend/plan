<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use App\Models\User;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Planning extends Component
{
    use WithPagination;

    public $search = '';
    public $startDate;

    protected $queryString = ['search', 'startDate'];
    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->startDate = $this->startDate ?? Carbon::now()->startOfWeek()->format('Y-m-d');
    }

    public function updated($property)
    {
        if (in_array($property, ['search', 'startDate'])) {
            $this->resetPage();
        }
    }

    public function exportCsv()
    {
        $users = $this->getFilteredUsers();

        $startDate = Carbon::parse($this->startDate);

        $response = new StreamedResponse(function () use ($users, $startDate) {
            $handle = fopen('php://output', 'w');

            $header = ['Utilisateur'];
            for ($i = 0; $i <= 6; $i++) {
                $header[] = $startDate->copy()->addDays($i)->translatedFormat('D d/m');
            }
            fputcsv($handle, $header);

            foreach ($users as $user) {
                $row = [$user->name];
                for ($i = 0; $i <= 6; $i++) {
                    $date = $startDate->copy()->addDays($i)->format('Y-m-d');
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
        $users = User::with('workLocations')
            ->when($this->search, fn($q) => $q->where('name', 'like', '%' . $this->search . '%'))
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.planning', [
            'users' => $users,
        ]);
    }
}
