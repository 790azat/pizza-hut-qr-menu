<?php

namespace Tests\Feature;

use App\Livewire\Admin\MenuManager;
use App\Models\Item;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    public function test_public_menu_renders_without_cookies(): void
    {
        $res = $this->get('/');
        $res->assertOk()->assertSee('Pizza Hut')->assertSee('Margherita');
        $this->assertEmpty($res->headers->getCookies());
    }

    public function test_images_are_served(): void
    {
        $this->get('/img/1')->assertOk()->assertHeader('Content-Type', 'image/webp');
    }

    public function test_admin_requires_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_admin_can_change_a_price(): void
    {
        $item = Item::whereNotNull('price')->first();

        Livewire::actingAs(User::first())
            ->test(MenuManager::class)
            ->call('editItem', $item->id)
            ->set('item.price', 4321)
            ->call('saveItem')
            ->assertHasNoErrors();

        $this->assertSame(4321, $item->fresh()->price);
        $this->get('/')->assertSee('4321');
    }
}
