<?php

use App\Livewire\Auth\{RecoveryPassword, ResetPassword};
use Livewire\Livewire;

//use App\Livewire\Auth\ResetPassword as ResetPasswordComponent;

it('renders successfully', function () {
    Livewire::test(RecoveryPassword::class)
        ->assertStatus(200);
});

it('validates email input', function () {
    Livewire::test(RecoveryPassword::class)
        ->set('email', 'invalid-email')
        ->call('submit')
        ->assertHasErrors(['email' => 'email']);
});

it('sends recovery email', function () {
    $user = \App\Models\User::factory()->create(['email' => 'joe@doe.com']);
    \Illuminate\Support\Facades\Notification::fake();

    Livewire::test(RecoveryPassword::class)
        ->set('email', 'joe@doe.com')
        ->call('submit')
        ->assertSee('Verification link sent to your email address.');

    \Illuminate\Support\Facades\Notification::assertSentTo(
        [$user],
        \Illuminate\Auth\Notifications\ResetPassword::class
    );
});

test('need create a token to reset password', function () {
    $user = \App\Models\User::factory()->create(['email' => 'joe@doe.com']);
    \Illuminate\Support\Facades\Notification::fake();

    Livewire::test(RecoveryPassword::class)
        ->set('email', 'joe@doe.com')
        ->call('submit')
        ->assertSee('Verification link sent to your email address.');

    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => 'joe@doe.com',
    ]);

});

test('verify the token is valid', function () {
    $user = \App\Models\User::factory()->create();

    \Illuminate\Support\Facades\Notification::fake();

    Livewire::test(RecoveryPassword::class)
        ->set('email', $user->email)
        ->call('submit')
        ->assertHasNoErrors();

    $this->get(route('password.reset', ["token" => "invalid-token", "email" => $user->email]))
        ->assertRedirect(route('login'));

    \Illuminate\Support\Facades\Notification::assertNotSentTo(
        [$user],
        \Illuminate\Auth\Notifications\ResetPassword::class,
        function ($notification) use ($user) {

            $this->get(route('password.reset', ['token' => $notification->token, 'email' => $user->email]))
                ->assertStatus(200);
        }
    );

});

test('save new password', function () {
    \Illuminate\Support\Facades\Notification::fake();

    $user = \App\Models\User::factory()->create();

    Livewire::test(RecoveryPassword::class)
        ->set('email', $user->email)
        ->call('submit')
        ->assertHasNoErrors();

    \Illuminate\Support\Facades\Notification::assertSentTo(
        [$user],
        \Illuminate\Auth\Notifications\ResetPassword::class,
        function ($notification) use ($user) {

            Livewire::test(
                ResetPassword::class,
                ['token' => $notification->token, 'email' => $user->email]
            )
                ->set('password', 'NewPassword123!')
                ->set('password_confirmation', 'NewPassword123!')
                ->call('submit')
                ->assertHasNoErrors()
                ->assertRedirect(route('dashboard'));

            return true;
        }
    );

    $user->refresh();

    $this->assertTrue(\Illuminate\Support\Facades\Hash::check('NewPassword123!', $user->password));

});
