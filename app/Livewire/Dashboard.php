<?php

namespace App\Livewire;

use App\Models\{Registration, User};
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

class Dashboard extends Component
{
    use Toast;

    public string $search = '';

    public bool $drawer = false;

    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    // Delete action
    public function delete($id): void
    {
        $this->warning("Will delete #$id", 'It is fake.', position: 'toast-bottom');
    }

    #[Computed()]
    public function usersCount()
    {
        return User::count();
    }

    #[Computed()]
    public function todayRecordsCount()
    {
        return Registration::whereDate('created_at', today())->count();
    }

    #[Computed()]
    public function studentsSuspendedToday()
    {
        return Registration::query()
            ->whereDate('registration_date_start', '<', today())
            ->whereDate('registration_date_end', '>', today())
            ->where('type', 'suspensao')
            ->get();
    }

    #[Computed()]
    public function headers()
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'student_name', 'label' => 'Nome do Aluno(a)', 'class' => 'w-64'],
            ['key' => 'status', 'label' => 'Status', 'class' => 'w-20'],
            ['key' => 'registration_date_start', 'label' => 'Data de Início', 'class' => 'w-32', 'format' => ['date', 'd/m/Y']],
            ['key' => 'registration_date_end', 'label' => 'Data de Término', 'class' => 'w-32', 'format' => ['date', 'd/m/Y']],
        ];
    }

    public function render()
    {
        return view('livewire.Dashboard');
    }
}
