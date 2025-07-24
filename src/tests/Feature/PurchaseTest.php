<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Item_condition;
use App\Models\Order;
use App\Models\Profile;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logged_in_user_can_purchase_item()
    {
        // 出品者（別のユーザー）を作成
        $seller = User::create([
            'name' => '出品ユーザー',
            'email' => 'seller@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Item_condition::create(['condition' => '新品']);

        // 商品を出品（購入されていない）
        $item = Item::create([
            'name' => '出品商品',
            'brand' => 'Apple',
            'description' => 'これは出品商品です。',
            'price' => 50000,
            'image' => 'sample.jpg',
            'user_id' => $seller->id,
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        // 購入者ユーザーを作成
        $buyer = User::create([
            'name' => '購入者ユーザー',
            'email' => 'buyer@example.com',
            'password' => bcrypt('password'),
        ]);

        // ログインして購入リクエストを送信
        $response = $this->actingAs($buyer)->post("/purchase/{$item->id}", [
            'payment_method' => 'クレジットカード',
            'address' => '東京都新宿区1-2-3',
            'postal_code' => '160-0000',
            'building' => 'サンプルビル101',
        ]);

        // リダイレクトまたは成功ステータスを確認
        $response->assertStatus(302);
        $response->assertRedirect(route('purchase.show', $item->id));

        // 購入レコードが orders テーブルに存在することを確認
        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'address' => '東京都新宿区1-2-3',
        ]);

        // 該当商品が「購入済み」状態になっていることを確認
        $this->assertEquals(1, $item->fresh()->is_sold);
    }
 
     /** @test */
    public function purchased_item_displays_sold_after_purchase()
    {
        // 出品者と購入者を作成
        $seller = User::create([
            'name' => '出品ユーザー2',
            'email' => 'seller2@example.com',
            'password' => bcrypt('password'),
        ]);

        $buyer = User::create([
            'name' => '購入者ユーザー2',
            'email' => 'buyer2@example.com',
            'password' => bcrypt('password'),
        ]);

        // コンディション設定
        $condition = Item_condition::create(['condition' => '新品']);

        // 商品を出品（購入されていない）
        $item = Item::create([
            'name' => '出品商品2',
            'brand' => 'Apple',
            'description' => 'これは出品商品2です。',
            'price' => 50000,
            'image' => 'sample.jpg',
            'user_id' => $seller->id,
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        // 購入処理（ログイン + POST）
        $response = $this->actingAs($buyer)->post("/purchase/{$item->id}", [
            'payment_method' => 'クレジットカード',
            'address' => '東京都渋谷区1-1-1',
            'postal_code' => '150-0001',
            'building' => '購入ビル101',
        ]);

        // 購入が完了してリダイレクトされたことを確認
        $response->assertStatus(302);
        $response->assertRedirect(route('purchase.show', $item->id));

        // 商品一覧ページを開く
        $listResponse = $this->get('/');

        // ステータス確認
        $listResponse->assertStatus(200);

        // 商品名が表示されていること
        $listResponse->assertSeeText('出品商品2');

        // 「sold」の表示があること
        $listResponse->assertSeeText('Sold');
    }

    //     /** @test */
    // public function purchased_item_appears_in_profile_page()
    // {
    //     // 出品者と購入者を作成
    //     $seller = User::create([
    //         'name' => '出品ユーザー3',
    //         'email' => 'seller3@example.com',
    //         'password' => bcrypt('password'),
    //     ]);

    //     $buyer = User::create([
    //         'name' => '購入者ユーザー3',
    //         'email' => 'buyer3@example.com',
    //         'password' => bcrypt('password'),
    //     ]);

    //     // 購入者にプロフィール作成
    //     Profile::create([
    //         'user_id' => $buyer->id,
    //         'image' => null,
    //         'postal_code' => '150-0002',
    //         'address' => '東京都渋谷区2-2-2',
    //         'building' => '購入ビル102',
    //     ]);

    //     // コンディション設定
    //     $condition = Item_condition::create(['condition' => '新品']);

    //     // 商品を出品（購入されていない）
    //     $item = Item::create([
    //         'name' => '出品商品3',
    //         'brand' => 'Apple',
    //         'description' => 'これは出品商品3です。',
    //         'price' => 50000,
    //         'image' => 'sample.jpg',
    //         'user_id' => $seller->id,
    //         'item_condition_id' => $condition->id,
    //         'is_sold' => false,
    //     ]);

    //     // 購入処理
    //     $response = $this->actingAs($buyer)->post("/purchase/{$item->id}", [
    //         'payment_method' => 'カード支払い',
    //         'address' => '東京都渋谷区2-2-2',
    //         'postal_code' => '150-0002',
    //         'building' => '購入ビル102',
    //     ]);

    //     $response->assertRedirect(route('purchase.show', $item->id));

    //     // プロフィールページへアクセス（購入履歴一覧表示）
    //     $mypage = $this->actingAs($buyer)->get('/mypage?tab=buy');

    //     $mypage->assertStatus(200);

    //     // 購入した商品名が表示されていること
    //     $mypage->assertSeeText('出品商品3');
    // }
}
