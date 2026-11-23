<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemSize extends Model
{
    public $timestamps = false;

    protected $fillable = ['item_id', 'label', 'price', 'weight', 'sort'];

    protected $casts = ['price' => 'integer'];
}
