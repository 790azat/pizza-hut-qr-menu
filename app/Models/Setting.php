<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'key';

    protected $keyType = 'string';

    protected $fillable = ['key', 'value'];

    public const DEFAULTS = [
        'name' => 'Pizza Hut',
        'color' => '#C8102E',
        'default_lang' => 'hy',
        'currency' => '֏',
        'tag_hy' => 'Ռեստորանի մենյու',
        'tag_ru' => 'Меню ресторана',
        'tag_en' => 'Restaurant menu',
        'sub_hy' => 'Պիցցա, պաստա, խորտիկներ և ըմպելիքներ',
        'sub_ru' => 'Пицца, паста, закуски и напитки',
        'sub_en' => 'Pizza, pasta, starters and drinks',
        'foot_hy' => "Գները նշված են ՀՀ դրամով (֏)։\nՊատվիրելու համար կանչեք մատուցողին։",
        'foot_ru' => "Цены указаны в армянских драмах (֏).\nЧтобы сделать заказ, позовите официанта.",
        'foot_en' => "Prices are in Armenian dram (֏).\nTo place an order, please call a waiter.",
    ];

    /** All settings merged over defaults. */
    public static function values(): array
    {
        try {
            $stored = static::query()->pluck('value', 'key')->all();
        } catch (\Throwable) {
            $stored = [];
        }

        return array_merge(self::DEFAULTS, array_filter($stored, fn ($v) => $v !== null));
    }

    public static function put(array $values): void
    {
        foreach ($values as $key => $value) {
            static::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
