<?php

namespace App\Livewire\Theme;

use Livewire\Component;

class ToggleTheme extends Component
{
    public $themeDark = false;

    public function render()
    {
        return view('livewire.theme.toggle-theme');
    }

    public function mount()
    {
        $this->themeDark = session('theme_dark', false);
    }

    public function themeToggle()
    {
        $themeDark = !session('theme_dark', false);
        session(['theme_dark' => $this->themeDark]);
        $this->themeDark = $themeDark;

        $this->dispatch('toggled');
    }
}
