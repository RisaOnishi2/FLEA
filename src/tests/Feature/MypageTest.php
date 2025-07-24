<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Item_condition;

class MypageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_only_liked_items_on_mylist_page()
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
        $likedItem = Item::create([
            'user_id' => $user->id,
            'name' => 'Liked Item',
            'brand' => 'ブランドA',
            'description' => '説明A',
            'price' => 1000,
            'image' => 'image.jpg',
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        $notLikedItem = Item::create([
            'user_id' => $user->id,
            'name' => 'Not Liked Item',
            'brand' => 'ブランドB',
            'description' => '説明B',
            'price' => 3000,
            'image' => 'sample.jpg',
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        // 「いいね」リレーションを手動で追加（likesテーブルへ挿入）
        $user->likes()->attach([$likedItem->id]);

        // マイリストページにアクセス（ログイン状態）
        $response = $this->actingAs($user)->get('/?page=mylist');

        $response->assertStatus(200);

        // 確認：いいねした商品は表示される
        $response->assertSeeText('Liked Item');

        // 確認：いいねしていない商品は表示されない
        $response->assertDontSeeText('Not Liked Item');

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
            'brand' => 'ブランドC',
            'description' => '説明C',
            'price' => 3000,
            'image' => 'image.jpg',
            'item_condition_id' => $condition->id,
            'is_sold' => true, // ← 購入済み
        ]);

        // いいね追加（マイリストで表示されるように）
        $user->likes()->attach($soldItem->id);

        //ログイン状態でマイページにアクセス
        $response = $this->actingAs($user)->get('/?page=mylist');

        $response->assertStatus(200);

        // Sold の表示があるか確認
        $response->assertSeeText('Sold');
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
        $response = $this->actingAs($me)->get('/?page=mylist');

        $response->assertStatus(200);

        // 自分の商品は見えない
        $response->assertDontSee('自分の商品');
    }

    /** @test */
    public function guest_user_sees_no_items_on_mylist_page()
    {
        // 他人の商品といいねを登録（ログインしていないので関係ないが念のため）
        $user = \App\Models\User::create([
            'name' => 'いいねユーザー',
            'email' => 'like@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = \App\Models\Item_condition::create([
            'condition' => 'やや傷あり',
        ]);

        $item = \App\Models\Item::create([
            'user_id' => $user->id,
            'name' => 'ゲストには見えない商品',
            'brand' => 'ブランドZ',
            'description' => '説明',
            'price' => 1000,
            'image' => 'image.jpg',
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        $user->likes()->attach($item->id);

        // 認証していない状態でマイリストにアクセス
        $response = $this->get('/?page=mylist');

        $response->assertStatus(200);

        // 商品が表示されないことを確認（商品名が見えない）
        $response->assertDontSeeText('ゲストには見えない商品');
    }

}
