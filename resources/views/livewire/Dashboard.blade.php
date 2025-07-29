<div>
   <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <x-stat
            title="Usuarios"
            value="{{ $this->usersCount }}"
            icon="o-users"
            color="text-primary" />

        <x-stat
            title="Registros Hoje"
            value="{{ $this->todayRecordsCount }}"
            icon="o-rectangle-stack"
            color="text-primary" />

        <x-stat
            title="Registros Suspensos Hoje"
            value="{{ $this->studentsSuspendedToday->count() }}"
            icon="o-exclamation-triangle"
            color="text-warning" />

    </div>

    @if ($this->studentsSuspendedToday->count() > 0)
        <x-card title="Alunos em Suspensão" class="mt-6" shadow>
           <x-table :headers="$this->headers" :rows="$this->studentsSuspendedToday" striped />
        </x-card>
    @endif
</div>
