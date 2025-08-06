<?php

use App\Livewire\Theme\ToggleTheme;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(ToggleTheme::class)
        ->assertStatus(200);
});
