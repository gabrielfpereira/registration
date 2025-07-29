<?php

namespace App\Livewire\Registration;

use App\Models\{Item, Registration, Signature};
use Livewire\Attributes\Computed;
use Livewire\Component;
use Mary\Traits\Toast;

class SuspensionCreate extends Component
{
    use Toast;

    public bool $drawer = false;

    public string $student_name = '';

    public $type_selected = 'suspensao';

    public $items_selected = [];

    public $class_number = '';

    public string $status = 'Pendente';

    public string $observation = '';

    public $start_date = '';

    public $end_date = '';

    public $signatures = null;

    public $signature_selected = null;

    public $signature_name = '';

    public $signature_role = '';

    public function render()
    {
        return view('livewire.registration.suspension-create');
    }

    public function mount()
    {
        $this->getSignatures();

        //dd($this->signatures);
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

    public function getSignatures()
    {
        $signatures = Signature::query()
            ->get();

        if ($signatures->isEmpty()) {
            $this->signatures = null;
        } else {
            $this->signatures = $signatures;
        }
    }

    public function save_signature()
    {
        $this->validate([
            'signature_name' => 'required|string|max:255',
            'signature_role' => 'required|string|max:255',
        ]);

        Signature::create([
            'name' => $this->signature_name,
            'role' => $this->signature_role,
        ]);

        $this->drawer = false;
        $this->getSignatures();
        $this->toast('success', 'Assinatura cadastrada com sucesso.');
    }

    public function save()
    {
        //dd($this->student_name,$this->items_selected, $this->class_number, $this->type_selected, $this->status, $this->start_date, $this->end_date, $this->signature_selected);
        $this->validate([
            'student_name'       => 'required|string|max:255',
            'type_selected'      => 'required|string',
            'items_selected'     => 'required|array',
            'class_number'       => 'required|string|max:50',
            'status'             => 'required|string|in:Pendente,Concluído',
            'observation'        => 'nullable|string|max:500',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after_or_equal:start_date',
            'signature_selected' => 'required|exists:signatures,id',
        ]);

        $registration = Registration::create([
            'student_name'            => $this->student_name,
            'class_number'            => (int) $this->class_number,
            'type'                    => $this->type_selected,
            'status'                  => $this->status,
            'observation'             => $this->observation,
            'registration_date_start' => $this->start_date,
            'registration_date_end'   => $this->end_date,
            'user_id'                 => auth()->id(),
        ]);
        $registration->items()->sync($this->items_selected);

        if ($this->signature_selected) {
            $registration->signatures()->attach($this->signature_selected);
        }

        $this->toast('success', 'Registro criado com sucesso.');
        $this->reset();
        $this->redirect(route('registration.index'));
    }
}
