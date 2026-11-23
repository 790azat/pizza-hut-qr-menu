<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Image;
use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/** Imports the original QR menu (database/seed/menu.json + images). */
class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $dir = database_path('seed');
        $menu = json_decode(file_get_contents("$dir/menu.json"), true);

        DB::transaction(function () use ($menu, $dir) {
            foreach ($menu as $ci => $c) {
                $category = Category::create([
                    'name_hy' => $c['hy'], 'name_ru' => $c['ru'], 'name_en' => $c['en'], 'sort' => $ci + 1,
                ]);

                foreach ($c['items'] as $ii => $it) {
                    $image = null;
                    if ($it['img'] && is_file("$dir/images/{$it['img']}")) {
                        $image = Image::fromBytes(file_get_contents("$dir/images/{$it['img']}"));
                    }

                    $item = Item::create([
                        'category_id' => $category->id,
                        'name_hy' => $it['hy'], 'name_ru' => $it['ru'], 'name_en' => $it['en'],
                        'desc_hy' => $it['dhy'] ?: null, 'desc_ru' => $it['dru'] ?: null, 'desc_en' => $it['den'] ?: null,
                        'image_id' => $image?->id,
                        'price' => $it['sizes'] ? null : $it['price'],
                        'weight' => $it['sizes'] ? null : $it['weight'],
                        'is_hit' => $it['hit'],
                        'sort' => $ii + 1,
                    ]);

                    foreach ($it['sizes'] as $si => $s) {
                        $item->sizes()->create([
                            'label' => $s['label'], 'price' => $s['price'], 'weight' => $s['weight'], 'sort' => $si + 1,
                        ]);
                    }
                }
            }
        });
    }
}
