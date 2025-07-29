<?php

namespace App\Livewire\People;

use App\Models\User;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;

    public bool $drawer = false;

    public string $search = '';

    public string $name = '';

    public string $email = '';

    public string $type_selected = '';

    public bool $modal_delete = false;

    public ?int $user_id = null;

    public function render()
    {
        return view('livewire.people.index');
    }

    #[Computed()]
    public function headers()
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Nome', 'class' => 'w-64'],
            ['key' => 'email', 'label' => 'E-mail', 'sortable' => false],
            ['key' => 'type', 'label' => 'Função', 'class' => 'w-20'],
        ];
    }

    #[Computed()]
    public function users()
    {
        return User::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->select(['id', 'name', 'email', 'type'])
            ->orderBy('name')
            ->get();
    }

    public function save()
    {
        $this->validate([
            'name'          => 'required|string|max:70',
            'email'         => 'required|email|max:70',
            'type_selected' => 'required|string|max:20',
        ]);

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'password' => bcrypt('Password@123'),
        ]);

        $user->type = $this->type_selected;
        $user->save();

        $this->reset(['name', 'email', 'type_selected']);
        $this->drawer = false;

        $this->toast('success', 'Usuário cadastrado com sucesso!');
    }

    public function delete($id)
    {
        $this->user_id      = $id;
        $this->modal_delete = true;
    }

    public function confirmDelete()
    {
        if ($this->user_id) {
            User::destroy($this->user_id);
            $this->toast('success', 'Usuário excluído com sucesso!');
        }

        $this->modal_delete = false;
    }
}
