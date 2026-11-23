# QR-меню (Laravel + Livewire, деплой на Vercel)

Сайт-меню для ресторана, который открывается по QR-коду, и админ-панель для управления им.

- **`/`** — меню для гостей: 3 языка (ՀԱՅ / RU / EN), поиск, категории, размеры пиццы, список «Мой выбор», который гость показывает официанту.
- **`/admin`** — админ-панель на Livewire: категории, блюда, цены, размеры, вес, фото, метка «ХИТ», скрытие позиций, порядок, тексты сайта, фирменный цвет, QR-код для печати, смена пароля.

## Как устроено

| Часть | Решение |
|---|---|
| Фреймворк | Laravel 13 + Livewire 4 |
| Хостинг | Vercel, рантайм [`vercel-php`](https://github.com/vercel-community/php) (PHP 8.4), вся логика в одной функции `api/index.php` |
| База данных | Postgres (Neon через Vercel Marketplace). Локально — SQLite |
| Фото | Хранятся в базе (у Vercel нет постоянного диска). Браузер сам уменьшает фото до 1000 px в WebP перед загрузкой, а `/img/{id}` отдаётся с вечным кэшем |
| Сессии | В зашифрованной cookie (не нужны Redis или файлы) |
| Скорость меню | Страница меню отдаётся без cookie и кэшируется на CDN Vercel на 60 секунд. Изменения из админки видны на сайте в течение минуты |

## Деплой на Vercel

1. **Импортируйте репозиторий** в Vercel (Add New → Project). Framework preset: *Other*. Ничего больше менять не нужно: всё описано в `vercel.json`.
2. **Подключите базу**: в проекте Vercel откройте *Storage → Create Database → Neon (Postgres)* и привяжите её к проекту. Vercel сам добавит переменную `DATABASE_URL`.
3. **Добавьте переменные окружения** (Settings → Environment Variables):
   - `APP_KEY` — сгенерируйте командой `php artisan key:generate --show`
   - `APP_URL` — адрес сайта, например `https://pizza-menu.vercel.app`
   - `ADMIN_EMAIL`, `ADMIN_PASSWORD` — логин в админку (создаётся при установке)
   - `SETUP_TOKEN` — любая длинная случайная строка
4. **Задеплойте** (Redeploy), затем откройте один раз `https://ваш-сайт/install?token=ВАШ_SETUP_TOKEN`. Это создаст таблицы, админа и загрузит стартовое меню (77 блюд с фото). После каждого обновления, где есть новые миграции, откройте эту ссылку снова — она безопасна для повторного запуска.
5. Зайдите в `/admin`, проверьте меню и скачайте QR-код в разделе «Настройки и QR».

## Локальный запуск

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve
```

Админка: http://localhost:8000/admin (логин и пароль из `ADMIN_EMAIL` / `ADMIN_PASSWORD` в `.env`).

Тесты: `php artisan test`.

## Где что лежит

- `resources/views/menu.blade.php` — публичная страница меню (вёрстка и JS из исходного макета)
- `app/Livewire/Admin/*` — компоненты админки (`MenuManager`, `Settings`, `Login`)
- `app/Http/Controllers/MenuController.php` — данные меню и отдача фото
- `app/Http/Controllers/InstallController.php` — миграции через `/install?token=…`
- `database/seed/` — стартовое меню и фото, извлечённые из исходного HTML
