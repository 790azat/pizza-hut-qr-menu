<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::admin')]
#[Title('Вход в админ-панель')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = true;

    public function login()
    {
        $this->validate(['email' => 'required|email', 'password' => 'required']);

        $key = 'login:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('email', 'Слишком много попыток. Подождите минуту.');

            return;
        }

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($key, 60);
            $this->addError('email', 'Неверный email или пароль.');

            return;
        }

        RateLimiter::clear($key);
        session()->regenerate();

        return $this->redirectRoute('admin.menu');
    }

    public function render()
    {
        return view('livewire.admin.login');
    }
}
