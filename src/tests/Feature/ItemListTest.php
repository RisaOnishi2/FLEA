<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Item_condition;

class ItemListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_all_items_on_index_page()
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

        // 商品作成
        $item = Item::create([
            'user_id' => $user->id,
            'name' => '商品A',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 1000,
            'image' => 'image.jpg',
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('商品A');
    }

    /** @test */
    public function sold_item_displays_sold_label()
    {
        // ユーザー作成
        $user = User::create([
            'name' => 'テストユーザー2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
        ]);

        // コンディション作成
        $condition = Item_condition::create([
            'condition' => '目立った傷や汚れなし',
        ]);

        // 購入済み商品を1件登録
        $soldItem = Item::create([
            'user_id' => $user->id,
            'name' => '購入済み商品',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 3000,
            'image' => 'image.jpg',
            'item_condition_id' => $condition->id,
            'is_sold' => true, // ← 購入済み
        ]);

        // 商品一覧ページにアクセス
        $response = $this->get('/');

        $response->assertStatus(200);

        // Sold の表示があるか確認
        $response->assertSee('Sold');
    }

    /** @test */
    public function items_list_does_not_include_items_posted_by_the_logged_in_user()
    {
        // 自分（ログインユーザー）を作成
        $me = User::create([
            'name' => '出品者A',
            'email' => 'me@example.com',
            'password' => bcrypt('password'),
        ]);

        // コンディション作成
        $condition = Item_condition::create([
            'condition' => '多少の傷や汚れあり',
        ]);

        // 自分の商品（表示されないはず）
        Item::create([
            'user_id' => $me->id,
            'name' => '自分の商品',
            'brand' => 'ブランドA',
            'description' => 'これは自分の商品です',
            'price' => 1000,
            'image' => 'image_a.jpg',
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        // 自分としてログインして一覧にアクセス
        $response = $this->actingAs($me)->get('/');

        $response->assertStatus(200);

        // 自分の商品は見えない
        $response->assertDontSee('自分の商品');
    }
}
