<div>
    <!-- HEADER -->
    <x-header title="Pessoal" separator progress-indicator>
        <x-slot:middle class="!justify-end">
            <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
        </x-slot:middle>
        <x-slot:actions>
            <x-button label="Cadastrar" @click="$wire.drawer = true" responsive icon="o-plus" class="btn-primary" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <x-table :headers="$this->headers" :rows="$this->users">
            @scope('actions', $user)
            <x-button icon="o-trash" wire:click="delete({{ $user['id'] }})" spinner class="btn-ghost btn-sm text-error" />
            @endscope

            @scope('cell_type', $user)
                <span class="text-sm">{{ match ($user['type']) {
                    'instructor' => 'Instrutor',
                    'supervisor' => 'Supervisor',
                    'support' => 'Apoio',
                } }}</span>
            @endscope
        </x-table>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Cadastrar Pessoal" right separator with-close-button class="lg:w-1/3">
        <x-form wire:submit="save">
        <x-input label="Nome" placeholder="Nome do Guerreiro" wire:model="name" />
        <x-select
            label="Função"
            wire:model="type_selected"
            placeholder="Selecione uma função"
            :options="[
                ['id' => 'instructor', 'name' => 'Instrutor'],
                ['id' => 'supervisor', 'name' => 'Supervisor'],
                ['id' => 'support', 'name' => 'Apoio'],
            ]"
             />
        <x-input label="E-mail" wire:model="email" />
        <p><strong>Senha:</strong> <span class="text-sm text-gray-500">Será gerada uma senha padrão: Password@123</span></p>

        <x-slot:actions>
            <x-button label="Salvar" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
        </x-form>
    </x-drawer>

    <x-modal wire:model="modal_delete" title="Tem Certeza?" class="backdrop-blur">
        <p>Você tem certeza que deseja excluir este usuário?</p>

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal_delete = false" />
            <x-button label="Delete" class="btn-error" wire:click="confirmDelete" spinner="confirmDelete" />
        </x-slot:actions>
    </x-modal>
</div>

