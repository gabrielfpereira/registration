<div>
   <x-card title="Perfil do Guerreiro" class="mb-4 sm:w-1/2 w-full">
        <x-form wire:submit="validateProfile">
            <x-input label="Nome" placeholder="Nome do Guerreiro" wire:model="name" />
            
            <x-input label="E-mail" wire:model="email" />

            <x-slot:actions>
                <x-button label="Salvar Mudanças" class="btn-primary" type="submit" spinner="validateProfile" />
            </x-slot:actions>
        </x-form>
   </x-card>

   <x-card title="Mudar Senha" class="mb-4 sm:w-1/2 w-full">
        <x-form wire:submit="validatePassword">
            <x-password label="Nova Senha" type="password" wire:model="new_password" />
            <x-password label="Confirmação da Nova Senha" type="password" wire:model="new_password_confirmation" />

            <x-slot:actions>
                <x-button label="Salvar Nova Senha" class="btn-primary" type="submit" spinner="validatePassword" />
            </x-slot:actions>
        </x-form>
   </x-card>

   <x-modal wire:model="modal_profile" title="Tem Certeza?" class="backdrop-blur">
        <p>Você tem certeza que deseja fazer esta alterção?</p>

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal_profile = false" />
            <x-button label="Confirmar" class="btn-primary" wire:click="updateProfile" spinner="confirmProfile" />
        </x-slot:actions>
    </x-modal>

    <x-modal wire:model="modal_password" title="Tem Certeza?" class="backdrop-blur">
        <p>Você tem certeza que deseja fazer esta alterção?</p>

        <x-slot:actions>
            <x-button label="Cancel" @click="$wire.modal_password = false" />
            <x-button label="Confirmar" class="btn-primary" wire:click="updatePassword" spinner="confirmPassword" />
        </x-slot:actions>
    </x-modal>
</div>
