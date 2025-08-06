<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="{{ session('theme_dark') ? 'dark' : 'light' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title.' - '.config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen font-sans antialiased bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <span class="font-bold text-3xl me-3 bg-gradient-to-r from-blue-500 to-purple-300 bg-clip-text text-transparent ">
                SOAMAR
            </span>
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main>
        {{-- SIDEBAR --}}
        <x-slot:sidebar collapse-text="Menu" drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

            {{-- BRAND --}}
            <div class="hidden-when-collapsed">
                <div class="flex w-full mt-2 justify-center items-center gap-2">
                    <x-logo />
                    <span class="font-bold me-3 text-3xl bg-gradient-to-r from-blue-500 to-purple-300 bg-clip-text text-transparent ">
                        SOAMAR
                    </span>
                </div>
            </div>

            <div class="display-when-collapsed flex w-full mt-2 justify-center items-center gap-2">
                <div class="flex w-full mt-2 justify-center items-center gap-2">
                    <x-logo />
                </div>
            </div>

            {{-- MENU --}}
            <x-menu activate-by-route>

                {{-- User --}}
                @if($user = auth()->user())
                    <x-menu-separator />
                    <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover class="-mx-2 !-my-2 rounded">
                        <x-slot:actions>
                            <x-button icon="o-power" class="btn-circle btn-ghost btn-xs" tooltip-left="logoff" no-wire-navigate link="/logout" />
                        </x-slot:actions>
                    </x-list-item>
                    <livewire:theme.toggle-theme />

                    <x-menu-separator />
                @endif

                <x-menu-item title="Início" icon="o-sparkles" link="/dashboard" />

                @if(Gate::allows('supervisor'))
                    <x-menu-item title="Pessoal" icon="o-users" link="/people" /> 
                @endif

                <x-menu-sub title="Registros" icon="o-document">
                    <x-menu-item title="Itens de Registros" icon="o-rectangle-stack" link="/registration/items" />
                    <x-menu-item title="Arquivos de Registros" icon="o-archive-box" link="/registration" />
                    @can('supervisor')
                        <x-menu-item title="Assinaturas" icon="o-pencil" link="/registration/signature" />
                    @endcan
                </x-menu-sub>

                <x-menu-item title="Perfil" icon="o-user-circle" link="/profile" />

            </x-menu>
        </x-slot:sidebar>

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />

    {{-- scripts --}}
   @stack('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('toggled', (event) => {
                    window.location.reload();
            });
        });
    </script>
</body>
</html>
