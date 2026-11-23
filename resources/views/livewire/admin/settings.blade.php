<div>
    <h1>Настройки и QR-код</h1>

    <div class="grid2" style="grid-template-columns:minmax(0,1fr) 320px">
        <div>
            <form class="card" style="padding:18px 20px" wire:submit="save">
                @if (session('saved'))<div class="flash">{{ session('saved') }}</div>@endif

                <div class="cols2">
                    <label class="f"><span>Название ресторана</span><input class="inp" wire:model="s.name">
                        @error('s.name')<div class="err">{{ $message }}</div>@enderror</label>
                    <label class="f"><span>Фирменный цвет</span>
                        <div style="display:flex;gap:8px">
                            <input type="color" wire:model.live="s.color" style="width:46px;height:40px;border:0;background:none;padding:0">
                            <input class="inp" wire:model.live="s.color">
                        </div>
                        @error('s.color')<div class="err">{{ $message }}</div>@enderror</label>
                    <label class="f"><span>Валюта</span><input class="inp" wire:model="s.currency"></label>
                    <label class="f"><span>Язык по умолчанию</span>
                        <select class="inp" wire:model="s.default_lang">
                            <option value="hy">Հայերեն</option><option value="ru">Русский</option><option value="en">English</option>
                        </select></label>
                </div>

                @foreach (['hy' => 'ՀԱՅ', 'ru' => 'RU', 'en' => 'EN'] as $l => $label)
                    <div class="sec">Тексты на сайте · {{ $label }}</div>
                    <div class="cols2">
                        <label class="f"><span>Надпись сверху</span><input class="inp" wire:model="s.tag_{{ $l }}"></label>
                        <label class="f"><span>Подзаголовок</span><input class="inp" wire:model="s.sub_{{ $l }}"></label>
                    </div>
                    <label class="f"><span>Текст внизу страницы</span><textarea class="inp" wire:model="s.foot_{{ $l }}"></textarea></label>
                @endforeach

                <button class="btn primary">Сохранить</button>
            </form>

            <form class="card" style="padding:18px 20px;margin-top:18px" wire:submit="changePassword">
                <h2 style="margin-bottom:12px">Смена пароля</h2>
                @if (session('pw'))<div class="flash">{{ session('pw') }}</div>@endif
                <label class="f"><span>Текущий пароль</span><input class="inp" type="password" wire:model="current_password" autocomplete="current-password">
                    @error('current_password')<div class="err">{{ $message }}</div>@enderror</label>
                <div class="cols2">
                    <label class="f"><span>Новый пароль</span><input class="inp" type="password" wire:model="new_password" autocomplete="new-password">
                        @error('new_password')<div class="err">{{ $message }}</div>@enderror</label>
                    <label class="f"><span>Ещё раз</span><input class="inp" type="password" wire:model="new_password_confirmation" autocomplete="new-password"></label>
                </div>
                <button class="btn dark">Сменить пароль</button>
            </form>
        </div>

        <div class="card" style="padding:18px 20px;text-align:center" wire:ignore>
            <h2 style="margin-bottom:6px">QR-код меню</h2>
            <p class="muted" style="margin:0 0 14px;font-size:12.5px;word-break:break-all">{{ $siteUrl }}</p>
            <div id="qr" style="display:inline-block;padding:14px;background:#fff;border-radius:12px;border:1px solid var(--line)"></div>
            <div style="margin-top:14px"><a class="btn primary" id="qrDl" download="qr-menu.png">Скачать PNG</a></div>
            <p class="muted" style="font-size:12px;margin-top:10px">Распечатайте и поставьте на столы.</p>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
            <script>
                (function () {
                    const box = document.getElementById('qr');
                    if (!window.QRCode || box.dataset.done) return;
                    box.dataset.done = 1;
                    new QRCode(box, { text: @json($siteUrl), width: 220, height: 220, correctLevel: QRCode.CorrectLevel.M });
                    setTimeout(() => {
                        const c = box.querySelector('canvas');
                        if (!c) return;
                        // Export a large, print-quality version
                        const big = document.createElement('div');
                        new QRCode(big, { text: @json($siteUrl), width: 1200, height: 1200, correctLevel: QRCode.CorrectLevel.M });
                        setTimeout(() => { const bc = big.querySelector('canvas'); document.getElementById('qrDl').href = (bc || c).toDataURL('image/png'); }, 50);
                    }, 50);
                })();
            </script>
        </div>
    </div>
</div>
