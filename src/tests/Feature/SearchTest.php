<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Item_condition;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function partial_match_search_by_product_name_is_available() 
    {
        
        // ユーザー作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // コンディション作成（例: ID=1）
        $condition = Item_condition::create([
            'condition' => '良好',
        ]);

        Item::create([
            'name' => 'Apple Watch',
            'brand' => 'Apple',
            'description' => 'Smart watch',
            'price' => 30000,
            'image' => 'applewatch.jpg',
            'user_id' => $user->id,
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        Item::create([
            'name' => 'Apple iPhone',
            'brand' => 'Apple',
            'description' => 'Smartphone',
            'price' => 90000,
            'image' => 'iphone.jpg',
            'user_id' => $user->id,
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        Item::create([
            'name' => 'Samsung Galaxy',
            'brand' => 'Samsung',
            'description' => 'Android phone',
            'price' => 80000,
            'image' => 'galaxy.jpg',
            'user_id' => $user->id,
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        // Act: 「Apple」で検索
        $response = $this->get('/item/search?keyword=Apple');

        // Assert: Apple関連の商品が見えること、Galaxyは見えないこと
        $response->assertStatus(200);
        $response->assertSee('Apple Watch');
        $response->assertSee('Apple iPhone');
        $response->assertDontSee('Samsung Galaxy');
    }
}
