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

    </div>
</div>
