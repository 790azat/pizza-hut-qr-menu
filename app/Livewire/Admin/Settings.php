<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::admin')]
#[Title('Настройки — админ-панель')]
class Settings extends Component
{
    public array $s = [];

    public string $current_password = '';

    public string $new_password = '';

    public string $new_password_confirmation = '';

    public function mount(): void
    {
        $this->s = array_intersect_key(Setting::values(), Setting::DEFAULTS);
    }

    public function save(): void
    {
        $rules = [
            's.name' => 'required|string|max:80',
            's.color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            's.currency' => 'nullable|string|max:8',
            's.default_lang' => 'required|in:hy,ru,en',
        ];
        foreach (['hy', 'ru', 'en'] as $l) {
            $rules["s.tag_$l"] = 'nullable|string|max:80';
            $rules["s.sub_$l"] = 'nullable|string|max:160';
            $rules["s.foot_$l"] = 'nullable|string|max:600';
        }

        $data = $this->validate($rules, [], ['s.name' => 'название', 's.color' => 'цвет'])['s'];
        Setting::put(array_map(fn ($v) => $v ?? '', $data));

        session()->flash('saved', 'Сохранено. На сайте изменения появятся в течение минуты.');
    }

    public function changePassword(): void
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ], [], ['new_password' => 'новый пароль']);

        $user = Auth::user();
        if (! Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Текущий пароль неверный.');

            return;
        }

        $user->update(['password' => $this->new_password]);
        $this->reset('current_password', 'new_password', 'new_password_confirmation');
        session()->flash('pw', 'Пароль изменён.');
    }

    public function render()
    {
        return view('livewire.admin.settings', ['siteUrl' => route('menu')]);
    }
}
