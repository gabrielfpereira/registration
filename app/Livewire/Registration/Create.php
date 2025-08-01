<?php

namespace App\Livewire\Registration;

use App\Models\{Item, Registration};
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

class Create extends Component
{
    use Toast;

    public bool $drawer_medida = false;

    public string $student_name = '';

    public $type_selected = '';

    public $items_selected = [];

    public $class_number = '';

    public string $status = 'Pendente';

    public string $observation = '';

    public function render()
    {
        return view('livewire.registration.create');
    }

    #[Computed()]
    public function items()
    {
        return Item::query()
            ->select('id', 'description')
            ->get()
            ->map(fn ($item) => [
                'id'   => $item->id,
                'name' => $item->description,
            ]);
    }

    #[Computed()]
    public function typesRegistration()
    {
        if (auth()->user()->can('supervisor')) {
            return [
                ['id' => 'infracao', 'name' => 'Infração'],
                ['id' => 'medida', 'name' => 'Medida'],
            ];
        }

        return [
            ['id' => 'infraction', 'name' => 'Infração'],
        ];
    }

    public function save()
    {
        $this->validate([
            'student_name'   => 'required|string|max:70',
            'type_selected'  => 'required|string|max:20',
            'class_number'   => 'required|string|max:4',
            'items_selected' => 'required|array|exists:items,id',
            'status'         => 'in:Pendente,Concluído',
            'observation'    => 'nullable|string|max:500',
        ]);

        Registration::create([
            'user_id'      => auth()->id(),
            'student_name' => $this->student_name,
            'type'         => $this->type_selected,
            'class_number' => (int) $this->class_number,
            'status'       => $this->status,
            'observation'  => $this->observation,
        ])->items()->sync($this->items_selected);

        $this->toast('success', 'Registro criado com sucesso!');

        return redirect()->route('registration.index');
    }
}
