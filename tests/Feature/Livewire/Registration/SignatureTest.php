<?php

use App\Livewire\Registration\Signature;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Signature::class)
        ->assertStatus(200);
});
