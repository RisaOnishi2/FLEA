<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Item_condition;
use App\Models\Like;

class LikeTest extends TestCase
{
    use RefreshDatabase;

     /** @test */
    public function logged_in_users_can_like_products_by_pressing_the_like_icon()
    {
        // ユーザーと関連データを作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Item_condition::create(['condition' => '新品']);

        // 商品を作成
        $item = Item::create([
            'name' => 'テスト商品',
            'brand' => 'Apple',
            'description' => 'これはテスト商品です。',
            'price' => 50000,
            'image' => 'sample.jpg',
            'user_id' => $user->id,
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        // ログインしてリクエストを送信
        $response = $this->actingAs($user)->post('/item/' . $item->id . '/like');

        // リダイレクト or 成功ステータス
        $response->assertStatus(302);
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

    /** @test */
    public function like_icon_is_colored_if_you_have_already_liked_the_page()
    {

        // ユーザーと関連データを作成
        $user = User::create([
            'name' => 'テストユーザー2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Item_condition::create(['condition' => '多少の傷や汚れあり']);

        // 商品を作成
        $item = Item::create([
            'name' => 'テスト商品2',
            'brand' => 'Apple',
            'description' => 'これはテスト商品2です。',
            'price' => 50000,
            'image' => 'sample2.jpg',
            'user_id' => $user->id,
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        // いいねを登録
        $item->likes()->create([
            'user_id' => $user->id,
        ]);

        // ログイン状態で詳細ページにアクセス
        $response = $this->actingAs($user)->get('/item/' . $item->id);

        // 色付きアイコン（例: like_filled.png）が表示されていることを確認
        $response->assertSee('liked.png');
    }

    /** @test */
    public function logged_in_users_can_press_the_like_icon_again_and_unliked()
    {
        // ユーザーと関連データを作成
        $user = User::create([
            'name' => 'テストユーザー3',
            'email' => 'test3@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Item_condition::create(['condition' => '傷や汚れあり']);

        // 商品を作成
        $item = Item::create([
            'name' => 'テスト商品3',
            'brand' => 'Apple',
            'description' => 'これはテスト商品3です。',
            'price' => 50000,
            'image' => 'sample3.jpg',
            'user_id' => $user->id,
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        // いいねを登録
        $item->likes()->create([
            'user_id' => $user->id,
        ]);

        // 削除前にレコードが存在していることを確認
        $this->assertDatabaseHas('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // ログインして「いいね解除」のリクエストを送る
        $response = $this->actingAs($user)->delete('/item/' . $item->id . '/unlike');

        // リダイレクト or 成功レスポンス
        $response->assertStatus(302); // or 200（あなたの実装に合わせて）

        // いいねが解除されていることを確認
        $this->assertDatabaseMissing('likes', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }
}
