<div>
    <!-- HEADER -->
    <x-header title="{{ $this->trash ? 'Itens Excluídos' : 'Itens de Registro' }}" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="pesquisar..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Cadastrar" @click="$wire.drawer = true" responsive icon="o-plus" class="btn-primary" />
            <x-button label="{{ $this->trash ? 'Mostrar Todos' : 'Lixeira' }}" badge="{{ !$this->trash ? $this->trashedCount() : '' }}" badge-classes="badge-warning" wire:click="$toggle('trash')" responsive icon="{{ $this->trash ? 'o-eye' : 'o-trash' }}" class="{{ $this->trash ? 'btn-primary' : 'btn-error' }}" />   
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card class="mb-4">
        <x-table :headers="$this->headers" :rows="$this->items" show-empty-text empty-text="Ops! Nenhum item encontrado">
            @scope('actions', $item)
                @if ($this->trash)
                    <x-button icon="o-arrow-path" tooltip-left="Restaura" wire:click="restore({{ $item['id'] }})" spinner class="btn-ghost btn-sm text-success" />
                    <x-button icon="o-trash" tooltip-left="Excluir permanentemente" wire:click="forceDelete({{ $item['id'] }})" spinner class="btn-ghost btn-sm text-error" />
                @else
                    <x-button icon="o-trash" wire:click="delete({{ $item['id'] }})" spinner class="btn-ghost btn-sm text-error" />
                @endif
            @endscope
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Cadastrar Item de Registro" right separator with-close-button class="lg:w-1/3">
        <x-form wire:submit="save">
        <x-input label="Descrição" placeholder="Descrição do Item" wire:model="description" />
       
        <x-slot:actions>
            <x-button label="Salvar" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
        </x-form>
    </x-drawer>

    <x-modal wire:model="modal_delete" title="Tem Certeza?" class="backdrop-blur">
        <p>Você tem certeza que deseja excluir este item?</p>

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal_delete = false" />
            <x-button label="Delete" class="btn-error" wire:click="confirmDelete" spinner="confirmDelete" />
        </x-slot:actions>
    </x-modal>
</div>

