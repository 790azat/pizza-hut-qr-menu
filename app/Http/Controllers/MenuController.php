<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Image;
use App\Models\Setting;

class MenuController extends Controller
{
    /** Public QR menu. Cached briefly at Vercel's CDN, so admin edits appear within about a minute. */
    public function show()
    {
        $s = Setting::values();

        $menu = Category::query()
            ->where('is_active', true)
            ->orderBy('sort')->orderBy('id')
            ->with(['items' => fn ($q) => $q->where('is_active', true)->with('sizes')])
            ->get()
            ->map(fn (Category $c) => [
                'id' => 'c'.$c->id,
                'hy' => $c->name_hy, 'ru' => $c->name_ru, 'en' => $c->name_en,
                'items' => $c->items->map(fn ($it) => [
                    'id' => $it->id,
                    'hy' => $it->name_hy, 'ru' => $it->name_ru, 'en' => $it->name_en,
                    'dhy' => $it->desc_hy, 'dru' => $it->desc_ru, 'den' => $it->desc_en,
                    'img' => $it->imageUrl(),
                    'hit' => $it->is_hit,
                    'p' => (int) $it->price, 'w' => $it->weight,
                    's' => $it->sizes->map(fn ($sz) => [$sz->label, $sz->price, $sz->weight])->values(),
                ])->values(),
            ])
            ->filter(fn ($c) => count($c['items']) > 0)
            ->values();

        $texts = [];
        foreach (['hy', 'ru', 'en'] as $l) {
            foreach (['tag', 'sub', 'foot'] as $k) {
                $texts[$l][$k] = nl2br(e($s["{$k}_{$l}"] ?? ''), false);
            }
        }

        return response()
            ->view('menu', ['s' => $s, 'menu' => $menu, 'texts' => $texts])
            ->header('Cache-Control', 'public, max-age=0, s-maxage=60, stale-while-revalidate=300');
    }

    /** Photos are immutable: a new upload always gets a new id. */
    public function image(int $id)
    {
        $img = Image::findOrFail($id);

        return response(base64_decode($img->data))
            ->header('Content-Type', $img->mime)
            ->header('Cache-Control', 'public, max-age=31536000, immutable');
    }
}
