<div>
     <!-- HEADER -->
    <x-header title="Registro" separator progress-indicator>
        <x-slot:middle class="!justify-end">
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="{{ auth()->user()->can('supervisor') ? 'Medida | Infração' : 'Infração' }}" link="{{ route('registration.create') }}" icon="o-plus" class="btn-primary" />
            @can('supervisor')
                <x-button label="Suspensão" link="{{ route('registration.suspension-create') }}" icon="o-plus" class="btn-primary" />
            @endcan
        </x-slot:actions>
    </x-header>


    <x-button label="Filtros" icon="o-funnel" class="btn-success mb-2 sm:hidden" wire:click="$toggle('show_filters')" />
    <div class="sm:flex items-center gap-4 mb-4 {{ $show_filters ? '' : 'hidden' }}">
        <x-select label='Filtro por Tipo:' wire:model.live="filter_type" :options="[
            ['id' => 'all', 'name' => 'Todos'],
            ['id' => 'medida', 'name' => 'Medida Disciplinar'],
            ['id' => 'infracao', 'name' => 'Infração Disciplinar'],
            ['id' => 'suspensao', 'name' => 'Suspensão'],
        ]" />
        <x-select label='Filtro por Status:' wire:model.live="filter_status" :options="[
            ['id' => 'all', 'name' => 'Todos'],
            ['id' => 'Pendente', 'name' => 'Pendente'],
            ['id' => 'Concluído', 'name' => 'Concluído'],
        ]" />

        <x-input label="Pesquisar:" placeholder="pesquisar..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />

    </div>
    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$this->headers" :rows="$this->registrations" with-pagination  show-empty-text empty-text="Nenhum registro encontrado">
            @scope('cell_status', $registration)
                @if ($registration['status'] == 'Pendente')
                    <x-badge value="{{ $registration['status'] }}" class="badge-warning" />
                @elseif ($registration['status'] == 'Concluído')
                    <x-badge value="{{ $registration['status'] }}" class="badge-success" />
                @endif
            @endscope

            @scope('cell_user', $registration)
                <span class="text-sm">{{ $registration['user_id'] ? $registration->user->name : 'N/A' }}</span>
            @endscope
           
            @scope('cell_type', $registration)
                <span class="text-sm">{{ match ($registration['type']) {
                    'medida' => 'Medida',
                    'infracao' => 'Infração',
                    'suspensao' => 'Suspensão',
                } }}</span>
            @endscope

            @scope('actions', $registration)
            <x-dropdown>
                 <x-slot:trigger>
                    <x-button icon="o-ellipsis-vertical" class="btn-circle" />
                </x-slot:trigger>
                <x-menu-item title="Imprimir" icon="o-printer"  wire:click.stop="print({{ $registration['id'] }})" spinner="print" />
                <x-menu-item title="Ver" icon="o-eye" wire:click.stop="showRegister({{ $registration['id'] }})" spinner="showRegister" class="btn-ghost btn-sm text-info" />
                <x-menu-item title="Deletar" icon="o-trash" wire:click.stop="delete({{ $registration['id'] }})" spinner="delete" class="btn-ghost btn-sm text-error" />

                @if ($registration['status'] == 'Pendente')
                    <x-menu-item title="Safo" icon="o-check-circle" wire:click.stop="toggleStatus({{ $registration['id'] }})" spinner class="btn-ghost btn-sm text-success" />
                @else
                    <x-menu-item title="Não Safo" icon="o-x-circle" wire:click.stop="toggleStatus({{ $registration['id'] }})" spinner class="btn-ghost btn-sm text-warning" />
                @endif
            </x-dropdown>
               
            @endscope

        </x-table>
    </x-card>

    <x-modal wire:model="modal_delete" title="Tem Certeza?" class="backdrop-blur">
        <p>Você tem certeza que deseja excluir este registro?</p>

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal_delete = false" />
            <x-button label="Delete" class="btn-error" wire:click="confirmDelete" spinner="confirmDelete" />
        </x-slot:actions>
    </x-modal>

    <x-modal wire:model="modal_show_register" title="Detalhes do Registro" class="backdrop-blur">
    @if ($registration)
        <x-input label="Nome do Aluno(a)" value="{{ $registration->student_name }}" readonly />
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-input label="Número da Turma" value="{{ $registration->class_number }}" readonly />
            <x-input label="Status" value="{{ $registration->status }}" readonly />
        </div>
        <div class="border-1 border-gray-300 p-4 mb-4 mt-4">
            <p><strong>Itens de Registro:</strong></p>
            <ul class="list-disc pl-5">
                @foreach($registration->items as $item)
                    <li>{{ $item->description }}</li>
                @endforeach
            </ul>

        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-input label="Tipo" value="{{ $registration->type }}" readonly />
            <x-input label="Data de Criação" value="{{ $registration->created_at->format('d/m/Y') }}" readonly />
        </div>
        <x-input label="Autor" value="{{ $registration->user->name }}" readonly />

        @if ($registration->observation)
            <div class="border-1 border-gray-300 p-4 mb-4 mt-4">
                <p><strong>Observações:</strong></p>
                <p>{{ $registration->observation }}</p>
            </div>
        @endif

        @if ($registration->type == 'Suspensão')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="Data de Início da Suspensão" value="{{ \Carbon\Carbon::parse($registration->registration_date_start)->format('d/m/Y') }}" readonly />
                <x-input label="Data de Fim da Suspensão" value="{{ \Carbon\Carbon::parse($registration->registration_date_end)->format('d/m/Y') }}" readonly />
            </div>
            @if ($registration->signatures->isNotEmpty())
                <div class="border-1 border-gray-300 p-4 mb-4 mt-4">
                    <p><strong>Assinaturas:</strong></p>
                    <ul class="list-disc pl-5">
                        @foreach($registration->signatures as $signature)
                            <li>{{ $signature->name }} - {{ $signature->role }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
        @endif
        <x-slot:actions>
            <x-button label="Fechar" @click="$wire.modal_show_register = false" />
        </x-slot:actions>
        @endif
    </x-modal>
   
</div>
