<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    protected $fillable = [
        'category_id', 'name_hy', 'name_ru', 'name_en', 'desc_hy', 'desc_ru', 'desc_en',
        'image_id', 'price', 'weight', 'is_hit', 'is_active', 'sort',
    ];

    protected $casts = ['is_hit' => 'boolean', 'is_active' => 'boolean', 'price' => 'integer'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function image(): BelongsTo
    {
        return $this->belongsTo(Image::class);
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ItemSize::class)->orderBy('sort')->orderBy('id');
    }

    public function imageUrl(): ?string
    {
        return $this->image_id ? url("/img/{$this->image_id}") : null;
    }
}
