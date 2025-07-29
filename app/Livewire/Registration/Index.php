<?php

namespace App\Livewire\Registration;

use App\Models\Registration;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\{Component, WithPagination};
use Mary\Traits\Toast;
use Spatie\LaravelPdf\Facades\Pdf as FacadesPdf;

class Index extends Component
{
    use Toast;
    use WithPagination;

    public bool $drawer_medida = false;

    public string $search = '';

    public string $filter_type = 'all';

    public string $filter_status = 'all';

    public bool $modal_delete = false;

    public bool $modal_show_register = false;

    public ?int $registration_id = null;

    public bool $show_filters = false;

    public Registration $registration;

    public function render()
    {
        return view('livewire.registration.index');
    }

    public function mount()
    {
        //$this->registration = new Registration();
    }

    #[Computed()]
    public function headers()
    {
        return [
            ['key' => 'id', 'label' => '#', 'class' => 'w-1'],
            ['key' => 'student_name', 'label' => 'Nome do Aluno(a)', 'class' => 'w-64'],
            ['key' => 'status', 'label' => 'Status', 'sortable' => false, 'class' => 'w-20'],
            ['key' => 'type', 'label' => 'Tipo', 'class' => 'w-20'],
            ['key' => 'class_number', 'label' => 'Turma', 'class' => 'w-20'],
            ['key' => 'user', 'label' => 'Autor'],
        ];
    }

    #[Computed()]
    public function registrations()
    {
        return Registration::query()
        ->with(['user'])
        ->when($this->filter_type !== 'all', function ($query) {
            $query->where('type', $this->filter_type);
        })
        ->when($this->filter_status !== 'all', function ($query) {
            $query->where('status', $this->filter_status);
        })
        ->when($this->search, function ($query) {
            $query->where('student_name', 'like', '%' . $this->search . '%')
            ->orWhere('class_number', 'like', '%' . $this->search . '%')
            ->orWhere('id', 'like', '%' . $this->search . '%');
        })
        ->select(['id', 'student_name', 'status', 'type', 'class_number', 'user_id'])
        ->orderBy('student_name')
        ->paginate(10);
    }

    public function delete(int $id)
    {
        $this->registration_id = $id;
        $this->modal_delete    = true;
    }

    public function confirmDelete()
    {
        Gate::authorize('delete', Registration::findOrFail($this->registration_id));

        if ($this->registration_id) {
            Registration::destroy($this->registration_id);
            $this->toast('success', 'Registro excluído com sucesso!');
        }

        $this->modal_delete    = false;
        $this->registration_id = null;
    }

    public function showRegister(int $id)
    {
        $this->registration       = Registration::with(['items', 'signatures', 'user'])->findOrFail($id);
        $this->registration->type = match($this->registration->type) {
            'infracao'  => 'Infração',
            'medida'    => 'Medida',
            'suspensao' => 'Suspensão',
            default     => 'N/A',
        };
        $this->modal_show_register = true;
    }

    public function print(int $id)
    {
        $this->registration = Registration::with('items')->findOrFail($id);

        $fileName = $this->registration->student_name . '-' . $this->registration->class_number . '-' . now()->format('dmY') . '.pdf';

        // Gera o PDF
        FacadesPdf::view('pdfs.registration', [
            'registration' => $this->registration,
        ])
        ->save(storage_path("app/public/{$fileName}"));

        return response()->stream(function () use ($fileName) {
            echo file_get_contents(storage_path("app/public/{$fileName}"));
        }, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    public function toggleStatus(int $id)
    {
        Gate::authorize('checkStatus', Registration::findOrFail($id));

        $registration         = Registration::findOrFail($id);
        $registration->status = $registration->status == 'Pendente' ? 'Concluído' : 'Pendente';
        $registration->save();

        $this->toast('success', 'Status atualizado com sucesso!');
    }
}
