<div>
     <!-- HEADER -->
    <x-header title="Registro de Suspensão" separator progress-indicator>


    </x-header>

   <x-form wire:submit="save">
        <x-input label="Nome do Aluno(a)" placeholder="Nome do Guerreiro" wire:model="student_name" />
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-select
                disabled
                label="Tipo de Registro"
                wire:model="type_selected"
                :options="[
                    ['id' => 'suspension', 'name' => 'Suspensão'],
                ]"
                />

            <x-input label="Turma" placeholder="Ex.: 2202" wire:model="class_number" x-mask="9999" hint="somente os numeros."/>

        </div>
        <x-choices 
            label="Itens de Registro" 
            wire:model="items_selected" 
            :options="$this->items" 
            allow-all />

        <x-textarea label="Observações" wire:model="observation" placeholder="Observações adicionais" rows="3" hint="campo opcional." />
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-datetime label="Data de Início da Suspensão" wire:model="start_date" />
            <x-datetime label="Data de Fim da Suspensão" wire:model="end_date" />
        </div>

        @if($signatures)
            <x-select
            label="Assinaturas"
            wire:model="signature_selected"
            placeholder="Selecione as Assinaturas"
            :options="$signatures"
            hint="Selecione as assinaturas necessárias para o registro." />
        @else
            <x-alert class="alert-warning" title="Nenhuma assinatura disponível. Cadastre uma assinatura primeiro." />
            <x-button label="Cadastrar Assinatura" @click="$wire.drawer = true" responsive icon="o-plus" class="btn-primary" />
        @endif

        <x-slot:actions>
            <x-button label="Salvar" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>

     <!-- SIGNATURE DRAWER -->
    <x-drawer wire:model="drawer" title="Cadastrar Assinatura" right separator with-close-button class="lg:w-1/3">
        <x-form wire:submit="save_signature">
        <x-input label="Nome" placeholder="Nome do Guerreiro" wire:model="signature_name" />
        <x-input label="Cargo" placeholder="Ex.: Diretor" wire:model="signature_role" />
        <x-slot:actions>
            <x-button label="Salvar" class="btn-primary" type="submit" spinner="save_signature" />
        </x-slot:actions>
        </x-form>
    </x-drawer>
</div>
