<?php

namespace App\Livewire\Registration;

use App\Models\Signature as ModelsSignature;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

class Signature extends Component
{
    use Toast;

    public bool $drawer = false;

    public string $search = '';

    public string $name = '';

    public string $role = '';

    public bool $modal_delete = false;

    public ?int $user_id = null;

    public bool $trash = false;

    public function render()
    {
        return view('livewire.registration.signature');
    }

    #[Computed()]
    public function headers()
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'name', 'label' => 'Nome', 'class' => 'w-64'],
            ['key' => 'role', 'label' => 'Função'],
            ['key' => 'created_at', 'label' => 'Criado em'],
        ];
    }

    #[Computed()]
    public function signatures()
    {
        return ModelsSignature::query()
            ->when($this->trash, function ($query) {
                $query->onlyTrashed();
            })
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('role', 'like', '%' . $this->search . '%');
            })
            ->select(['id', 'name', 'role', 'created_at'])
            ->get();
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:70',
            'role' => 'required|string|max:50',
        ]);

        ModelsSignature::create([
            'name' => $this->name,
            'role' => $this->role,
        ]);

        $this->toast('Assinatura criada com sucesso!', 'success');
        $this->drawer = false;
        $this->reset(['name', 'role']);
    }

    public function delete($id)
    {
        $this->user_id      = $id;
        $this->modal_delete = true;
    }

    public function confirmDelete()
    {
        if ($this->user_id) {
            ModelsSignature::destroy($this->user_id);
            $this->toast('success', 'Assinatura excluída com sucesso!');
        }

        $this->modal_delete = false;
    }

    public function restore(int $id)
    {
        ModelsSignature::withTrashed()->findOrFail($id)->restore();
        $this->toast('success','Assinatura restaurada com sucesso!');
    }

    public function forceDelete(int $id)
    {
        ModelsSignature::withTrashed()->findOrFail($id)->forceDelete();
        $this->toast('success', 'Assinatura excluída permanentemente!');
    }

    #[Computed()]
    public function trashedCount()
    {
        return ModelsSignature::onlyTrashed()->count();
    }
}
