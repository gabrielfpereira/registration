<?php

use App\Livewire\Profile\Index;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = \App\Models\User::factory()->create();
    $this->actingAs($user);
    Livewire::test(Index::class)
        ->assertStatus(200);
});
