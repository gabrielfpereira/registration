<?php

namespace App\Livewire\Registration;

use App\Models\Item;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

class Items extends Component
{
    use Toast;

    public bool $drawer = false;

    public string $search = '';

    public string $description = '';

    public float $points = 0;

    public bool $modal_delete = false;

    public ?int $item_id = null;

    public function render()
    {
        return view('livewire.registration.items');
    }

    #[Computed()]
    public function headers()
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'description', 'label' => 'Descrição', 'class' => 'w-64'],
            //['key' => 'points', 'label' => 'Pontos', 'sortable' => false],
        ];
    }

    #[Computed()]
    public function items()
    {
        return Item::query()
            ->when($this->search, function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%');
            })
            ->select(['id', 'description', 'points'])
            ->orderBy('description')
            ->get();
    }

    public function save()
    {
        $this->validate([
            'description' => 'required|string|max:255',
            'points'      => 'required|numeric|min:0',
        ]);

        // Logic to save the item
        Item::create(['description' => $this->description, 'points' => $this->points]);

        $this->toast('success', 'Salvo com sucesso!');
        $this->reset(['description', 'points']);
        $this->drawer = false;
    }

    public function delete(int $id)
    {
        $this->item_id      = $id;
        $this->modal_delete = true;
    }

    public function confirmDelete()
    {
        Item::destroy($this->item_id);
        $this->toast('success', 'Item excluído com sucesso!');
        $this->modal_delete = false;
        $this->item_id      = null;
    }
}
