<?php

use App\Livewire\Registration\Create;
use Livewire\Livewire;

it('renders successfully', function () {
    $user = \App\Models\User::factory()->create(['type' => 'supervisor']);
    $this->actingAs($user);
    Livewire::test(Create::class)
        ->assertStatus(200);
});
