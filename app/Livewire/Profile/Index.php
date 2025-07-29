<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Mary\Traits\Toast;

class Index extends Component
{
    use Toast;

    public ?string $email = '';

    public ?string $name = '';

    public ?string $new_password = '';

    public ?string $new_password_confirmation = '';

    public bool $modal_profile = false;

    public bool $modal_password = false;

    public function render()
    {
        return view('livewire.profile.index');
    }

    public function mount()
    {
        $this->email = auth()->user()->email;
        $this->name  = auth()->user()->name;
    }

    public function validateProfile()
    {
        $this->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
        ]);

        $this->modal_profile = true;
    }

    public function updateProfile()
    {

        $user        = User::find(auth()->id());
        $user->name  = $this->name;
        $user->email = $this->email;
        $user->save();

        $this->modal_profile = false;
        $this->toast('success', 'Perfil atualizado com sucesso!');

        return redirect()->route('profile.index');
    }

    public function validatePassword()
    {
        $this->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $this->modal_password = true;
    }

    public function updatePassword()
    {
        $user           = User::find(auth()->id());
        $user->password = bcrypt($this->new_password);
        $user->save();

        $this->toast('success', 'Senha atualizada com sucesso!');

        $this->modal_password = false;
        // Clear the password fields after updating
        $this->reset(['new_password', 'new_password_confirmation']);
    }
}
