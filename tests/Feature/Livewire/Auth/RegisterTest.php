<?php

use App\Livewire\Auth\Register;
use App\Notifications\WelcomeUser;
use Illuminate\Support\Facades\{Auth, Notification};
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(Register::class)
        ->assertStatus(200);
});

test('user can register with valid data', function () {
    Livewire::test('auth.register')
        ->set('name', 'John Doe')
        ->set('email', 'john@doe.com')
        ->set('password', 'Password@123')
        ->set('password_confirmation', 'Password@123')
        ->call('register')
        ->assertRedirect('/dashboard');

    $this->assertDatabaseHas('users', [
        'name'  => 'John Doe',
        'email' => 'john@doe.com', ]);
});

test('user cannot register with invalid data', function () {
    Livewire::test('auth.register')
        ->set('name', '')
        ->set('email', 'invalid-email')
        ->set('password', 'short')
        ->set('password_confirmation', 'notmatching')
        ->call('register')
        ->assertHasErrors([
            'name'                  => 'required',
            'email'                 => 'email',
            'password'              => 'min',
            'password_confirmation' => 'same',
        ]);
});

test('user cannot register with existing email', function () {
    \App\Models\User::factory()->create(['email' => 'john@doe.com']);
    Livewire::test('auth.register')
        ->set('name', 'Jane Doe')
        ->set('email', 'john@doe.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123')
        ->call('register')
        ->assertHasErrors(['email' => 'unique']);
});

test('user is logged in after registration', function () {
    Livewire::test('auth.register')
        ->set('name', 'Alice Smith')
        ->set('email', 'alice@example.com')
        ->set('password', 'Password@123')
        ->set('password_confirmation', 'Password@123')
        ->call('register');
    $this->assertAuthenticated();
    $this->assertEquals('Alice Smith', Auth::user()->name);
    $this->assertEquals('alice@example.com', Auth::user()->email);
});

test('the registration have password security', function () {
    Livewire::test('auth.register')
        ->set('name', 'Bob Brown')
        ->set('email', 'bob@example.com')
        // Password missing uppercase, lowercase, and special character
        ->set('password', '12345678')
        ->set('password_confirmation', '12345678')
        ->call('register')
        ->assertHasErrors([
            'password' => ['regex'],
        ]);

    Livewire::test('auth.register')
        ->set('name', 'Bob Brown')
        ->set('email', 'bob@example.com')
        // Password missing special character
        ->set('password', 'Password123')
        ->set('password_confirmation', 'Password123')
        ->call('register')
        ->assertHasErrors([
            'password' => ['regex'],
        ]);

    Livewire::test('auth.register')
        ->set('name', 'Bob Brown')
        ->set('email', 'bob@example.com')
        // Valid password
        ->set('password', 'Password@123')
        ->set('password_confirmation', 'Password@123')
        ->call('register')
        ->assertSuccessful()
        ->assertSessionHasNoErrors()
        ->assertRedirect('/dashboard');

    $this->assertDatabaseHas('users', [
        'name'  => 'Bob Brown',
        'email' => 'bob@example.com', ]);

});

test("send email welcome after registration", function () {
    // This test would require a mail fake or similar setup to check if an email was sent.
    Notification::fake();
    Livewire::test('auth.register')
        ->set('name', 'Charlie Green')
        ->set('email', 'jonh@doe.com')
        ->set('password', 'Password@123')
        ->set('password_confirmation', 'Password@123')
        ->call('register')
        ->assertHasNoErrors()
        ->assertRedirect('/dashboard');

    // Here you would check if the email was sent, using a mail fake or similar.
    // For example:
    Notification::assertSentTo(
        Auth::user(),
        WelcomeUser::class
    );

});
