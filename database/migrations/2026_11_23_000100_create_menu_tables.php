<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Photos live in the database (base64), so the site needs no extra file storage on Vercel.
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('mime', 32);
            $table->longText('data');
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name_hy');
            $table->string('name_ru');
            $table->string('name_en');
            $table->unsignedInteger('sort')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name_hy');
            $table->string('name_ru');
            $table->string('name_en');
            $table->text('desc_hy')->nullable();
            $table->text('desc_ru')->nullable();
            $table->text('desc_en')->nullable();
            $table->foreignId('image_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('price')->nullable();
            $table->string('weight')->nullable();
            $table->boolean('is_hit')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort')->default(0);
            $table->timestamps();
        });

        Schema::create('item_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->string('label', 32);
            $table->unsignedInteger('price');
            $table->string('weight')->nullable();
            $table->unsignedInteger('sort')->default(0);
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->text('value')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('item_sizes');
        Schema::dropIfExists('items');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('images');
    }
};
