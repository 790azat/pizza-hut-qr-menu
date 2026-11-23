<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Image;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts::admin')]
#[Title('Меню — админ-панель')]
class MenuManager extends Component
{
    #[Url(as: 'c')]
    public ?int $categoryId = null;

    // Category modal
    public bool $showCategory = false;

    public array $cat = [];

    // Item modal
    public bool $showItem = false;

    public array $item = [];

    public function mount(): void
    {
        if (! $this->categoryId || ! Category::whereKey($this->categoryId)->exists()) {
            $this->categoryId = Category::orderBy('sort')->orderBy('id')->value('id');
        }
    }

    #[Computed]
    public function categories()
    {
        return Category::withCount('items')->orderBy('sort')->orderBy('id')->get();
    }

    #[Computed]
    public function current(): ?Category
    {
        return $this->categoryId ? Category::find($this->categoryId) : null;
    }

    #[Computed]
    public function items()
    {
        return $this->categoryId
            ? Item::where('category_id', $this->categoryId)->with('sizes')->orderBy('sort')->orderBy('id')->get()
            : collect();
    }

    public function selectCategory(int $id): void
    {
        $this->categoryId = $id;
    }

    /* ---------------- Categories ---------------- */

    public function newCategory(): void
    {
        $this->resetValidation();
        $this->cat = ['id' => null, 'name_hy' => '', 'name_ru' => '', 'name_en' => '', 'is_active' => true];
        $this->showCategory = true;
    }

    public function editCategory(int $id): void
    {
        $this->resetValidation();
        $c = Category::findOrFail($id);
        $this->cat = $c->only(['id', 'name_hy', 'name_ru', 'name_en', 'is_active']);
        $this->showCategory = true;
    }

    public function saveCategory(): void
    {
        $data = $this->validate([
            'cat.name_ru' => 'required|string|max:120',
            'cat.name_hy' => 'nullable|string|max:120',
            'cat.name_en' => 'nullable|string|max:120',
            'cat.is_active' => 'boolean',
        ], [], ['cat.name_ru' => 'название (RU)'])['cat'];

        $data['name_hy'] = $data['name_hy'] ?: $data['name_ru'];
        $data['name_en'] = $data['name_en'] ?: $data['name_ru'];

        if ($this->cat['id']) {
            Category::findOrFail($this->cat['id'])->update($data);
        } else {
            $data['sort'] = (int) Category::max('sort') + 1;
            $this->categoryId = Category::create($data)->id;
        }

        $this->showCategory = false;
    }

    public function deleteCategory(int $id): void
    {
        $c = Category::findOrFail($id);
        DB::transaction(function () use ($c) {
            $imageIds = $c->items()->pluck('image_id')->filter();
            $c->delete();
            Image::whereIn('id', $imageIds)->delete();
        });

        if ($this->categoryId === $id) {
            $this->categoryId = Category::orderBy('sort')->value('id');
        }
        $this->showCategory = false;
    }

    public function toggleCategory(int $id): void
    {
        $c = Category::findOrFail($id);
        $c->update(['is_active' => ! $c->is_active]);
    }

    public function moveCategory(int $id, int $dir): void
    {
        $this->move(Category::orderBy('sort')->orderBy('id')->get(), $id, $dir);
    }

    /* ---------------- Items ---------------- */

    public function newItem(): void
    {
        $this->resetValidation();
        $this->item = [
            'id' => null, 'category_id' => $this->categoryId,
            'name_hy' => '', 'name_ru' => '', 'name_en' => '',
            'desc_hy' => '', 'desc_ru' => '', 'desc_en' => '',
            'price' => '', 'weight' => '', 'is_hit' => false, 'is_active' => true,
            'sizes' => [], 'photo' => null, 'remove_photo' => false, 'image_url' => null,
        ];
        $this->showItem = true;
    }

    public function editItem(int $id): void
    {
        $this->resetValidation();
        $it = Item::with('sizes')->findOrFail($id);
        $this->item = array_merge(
            $it->only(['id', 'category_id', 'name_hy', 'name_ru', 'name_en', 'desc_hy', 'desc_ru', 'desc_en', 'price', 'weight', 'is_hit', 'is_active']),
            [
                'sizes' => $it->sizes->map(fn ($s) => ['label' => $s->label, 'price' => $s->price, 'weight' => $s->weight])->all(),
                'photo' => null, 'remove_photo' => false, 'image_url' => $it->imageUrl(),
            ],
        );
        $this->showItem = true;
    }

    public function addSize(): void
    {
        $this->item['sizes'][] = ['label' => '', 'price' => '', 'weight' => ''];
    }

    public function removeSize(int $i): void
    {
        unset($this->item['sizes'][$i]);
        $this->item['sizes'] = array_values($this->item['sizes']);
    }

    public function removePhoto(): void
    {
        $this->item['photo'] = null;
        $this->item['remove_photo'] = true;
        $this->item['image_url'] = null;
    }

    public function saveItem(): void
    {
        $hasSizes = count($this->item['sizes'] ?? []) > 0;

        $data = $this->validate([
            'item.category_id' => 'required|exists:categories,id',
            'item.name_ru' => 'required|string|max:160',
            'item.name_hy' => 'nullable|string|max:160',
            'item.name_en' => 'nullable|string|max:160',
            'item.desc_ru' => 'nullable|string|max:600',
            'item.desc_hy' => 'nullable|string|max:600',
            'item.desc_en' => 'nullable|string|max:600',
            'item.price' => $hasSizes ? 'nullable' : 'required|integer|min:0|max:10000000',
            'item.weight' => 'nullable|string|max:40',
            'item.is_hit' => 'boolean',
            'item.is_active' => 'boolean',
            'item.sizes.*.label' => 'required|string|max:32',
            'item.sizes.*.price' => 'required|integer|min:0|max:10000000',
            'item.sizes.*.weight' => 'nullable|string|max:40',
            'item.photo' => 'nullable|string',
        ], [], [
            'item.name_ru' => 'название (RU)', 'item.price' => 'цена',
            'item.sizes.*.label' => 'размер', 'item.sizes.*.price' => 'цена размера',
        ])['item'];

        $newImage = null;
        if (! empty($data['photo'])) {
            $newImage = Image::fromDataUrl($data['photo']);
            if (! $newImage) {
                $this->addError('item.photo', 'Не удалось прочитать фото. Попробуйте JPG, PNG или WebP до 1,5 МБ.');

                return;
            }
        }

        DB::transaction(function () use ($data, $hasSizes, $newImage) {
            $fields = [
                'category_id' => $data['category_id'],
                'name_ru' => $data['name_ru'],
                'name_hy' => $data['name_hy'] ?: $data['name_ru'],
                'name_en' => $data['name_en'] ?: $data['name_ru'],
                'desc_ru' => $data['desc_ru'] ?: null,
                'desc_hy' => $data['desc_hy'] ?: null,
                'desc_en' => $data['desc_en'] ?: null,
                'price' => $hasSizes ? null : (int) $data['price'],
                'weight' => $hasSizes ? null : ($data['weight'] ?: null),
                'is_hit' => (bool) $data['is_hit'],
                'is_active' => (bool) $data['is_active'],
            ];

            $item = $this->item['id'] ? Item::findOrFail($this->item['id']) : new Item;
            if (! $item->exists || $item->category_id != $fields['category_id']) {
                $fields['sort'] = (int) Item::where('category_id', $fields['category_id'])->max('sort') + 1;
            }

            $oldImageId = $item->image_id;
            if ($newImage) {
                $fields['image_id'] = $newImage->id;
            } elseif ($this->item['remove_photo']) {
                $fields['image_id'] = null;
            }

            $item->fill($fields)->save();

            if ($oldImageId && $oldImageId !== $item->image_id) {
                Image::whereKey($oldImageId)->delete();
            }

            $item->sizes()->delete();
            foreach (array_values($data['sizes'] ?? []) as $i => $s) {
                $item->sizes()->create([
                    'label' => trim($s['label']), 'price' => (int) $s['price'],
                    'weight' => $s['weight'] ?: null, 'sort' => $i + 1,
                ]);
            }
        });

        $this->showItem = false;
    }

    public function deleteItem(int $id): void
    {
        $it = Item::findOrFail($id);
        $imageId = $it->image_id;
        $it->delete();
        if ($imageId) {
            Image::whereKey($imageId)->delete();
        }
        $this->showItem = false;
    }

    public function toggleItem(int $id): void
    {
        $it = Item::findOrFail($id);
        $it->update(['is_active' => ! $it->is_active]);
    }

    public function toggleHit(int $id): void
    {
        $it = Item::findOrFail($id);
        $it->update(['is_hit' => ! $it->is_hit]);
    }

    public function moveItem(int $id, int $dir): void
    {
        $it = Item::findOrFail($id);
        $this->move(Item::where('category_id', $it->category_id)->orderBy('sort')->orderBy('id')->get(), $id, $dir);
    }

    /** Swap a row with its neighbour and renumber the list. */
    private function move($rows, int $id, int $dir): void
    {
        $list = $rows->values();
        $i = $list->search(fn ($r) => $r->id === $id);
        $j = $i + ($dir < 0 ? -1 : 1);
        if ($i === false || $j < 0 || $j >= $list->count()) {
            return;
        }

        $arr = $list->all();
        [$arr[$i], $arr[$j]] = [$arr[$j], $arr[$i]];

        DB::transaction(function () use ($arr) {
            foreach ($arr as $n => $row) {
                if ($row->sort !== $n + 1) {
                    $row->update(['sort' => $n + 1]);
                }
            }
        });
    }

    public function render()
    {
        return view('livewire.admin.menu-manager');
    }
}
