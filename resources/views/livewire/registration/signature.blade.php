<div>
    <!-- HEADER -->
    <x-header title="Assinaturas" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Cadastrar" @click="$wire.drawer = true" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$this->headers" :rows="$this->signatures">
            @scope('actions', $signature)
            <x-button icon="o-trash" wire:click="delete({{ $signature['id'] }})" spinner class="btn-ghost btn-sm text-error" />
            @endscope

        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Cadastrar Assinatura" right separator with-close-button class="lg:w-1/3">
        <x-form wire:submit="save">
        <x-input label="Nome" placeholder="Nome do Guerreiro" wire:model="name" />
        <x-input label="Cargo" wire:model="role" />

        <x-slot:actions>
            <x-button label="Salvar" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
        </x-form>
    </x-drawer>

    <x-modal wire:model="modal_delete" title="Tem Certeza?" class="backdrop-blur">
        <p>Você tem certeza que deseja excluir esta assinatura?</p>

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal_delete = false" />
            <x-button label="Delete" class="btn-error" wire:click="confirmDelete" spinner="confirmDelete" />
        </x-slot:actions>
    </x-modal>
</div>
