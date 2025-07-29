<div>
    <div class="flex w-full mb-4 justify-center items-center gap-2">
        <x-logo />
        <span class="font-bold me-3 text-3xl bg-gradient-to-r from-blue-500 to-purple-300 bg-clip-text text-transparent ">
            SOAMAR
        </span>
     </div>
     <x-card title="Fazer Login" separator>
        <x-form wire:submit="login">
            <x-errors title="Oops!" description="Please, fix them." icon="o-face-frown" />
        
            <x-input label="E-mail" wire:model="email" />
            <x-password label="Password" wire:model="password" clearable />
        
            <x-slot:actions>
                <div class="flex items-center justify-between w-full">
                    <a href="{{ route('auth.recovery-password') }}" class="text-sm text-blue-600 hover:underline">Esqueci minha senha</a>
                    <x-button label="Login" class="btn-primary" type="submit" spinner="login" />
                </div>
            </x-slot:actions>
        </x-form>
    </x-card>

</div>
