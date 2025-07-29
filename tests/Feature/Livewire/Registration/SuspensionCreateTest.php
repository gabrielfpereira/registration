<?php

use App\Livewire\Registration\SuspensionCreate;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(SuspensionCreate::class)
        ->assertStatus(200);
});
