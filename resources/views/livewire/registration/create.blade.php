<div>
     <!-- HEADER -->
    <x-header title="Registro de Medida | Infração" separator progress-indicator>
       
       
    </x-header>

   <x-form wire:submit="save">
        <x-input label="Nome do Aluno(a)" placeholder="Nome do Guerreiro" wire:model="student_name" />
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-select
            label="Tipo de Registro"
            wire:model="type_selected"
            placeholder="Selecione um Tipo de Registro"
            :options="$this->typesRegistration"
            />
            
            <x-input label="Turma" placeholder="Ex.: 2202" wire:model="class_number" x-mask="9999" hint="somente os numeros."/>
        </div>
        <x-choices 
            label="Itens de Registro" 
            wire:model="items_selected" 
            :options="$this->items" 
            allow-all />

        <x-textarea label="Observações" wire:model="observation" placeholder="Observações adicionais" rows="3" hint="campo opcional." />


        <x-slot:actions>
            <x-button label="Salvar" class="btn-primary" type="submit" spinner="save" />
        </x-slot:actions>
    </x-form>
</div>
