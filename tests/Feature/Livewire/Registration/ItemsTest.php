<?php

use App\Livewire\Registration\Items;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Items::class)
        ->assertStatus(200);
});
