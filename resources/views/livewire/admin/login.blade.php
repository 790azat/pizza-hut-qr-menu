<div style="max-width:380px;margin:60px auto">
    <div class="card" style="padding:26px">
        <h1 style="margin-bottom:4px">Админ-панель</h1>
        <p class="muted" style="margin:0 0 18px">Управление QR-меню</p>
        <form wire:submit="login">
            <label class="f"><span>Email</span>
                <input class="inp" type="email" wire:model="email" autocomplete="username" autofocus>
                @error('email')<div class="err">{{ $message }}</div>@enderror
            </label>
            <label class="f"><span>Пароль</span>
                <input class="inp" type="password" wire:model="password" autocomplete="current-password">
                @error('password')<div class="err">{{ $message }}</div>@enderror
            </label>
            <label class="check"><input type="checkbox" wire:model="remember"> Запомнить меня</label>
            <button class="btn primary" style="width:100%;margin-top:10px" wire:loading.attr="disabled">Войти</button>
        </form>
    </div>
</div>
