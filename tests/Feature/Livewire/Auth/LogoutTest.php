<?php

use App\Livewire\Auth\Logout;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Logout::class)
        ->assertStatus(200);
});

test('user can log out', function () {
    $user = \App\Models\User::factory()->create();

    $this->actingAs($user);
    Livewire::test('auth.logout')
        ->call('logout')
        ->assertRedirect(route('home'));

    $this->assertGuest();

});
