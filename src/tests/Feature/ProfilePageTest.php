<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Item_condition;
use App\Models\Order;

class ProfilePageTest extends TestCase
{
    use RefreshDatabase;

      /** @test */
    public function test_profile_page_displays_user_info_and_items()
    {
        // ユーザーを作成
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Item_condition::create(['condition' => '新品']);

        // 出品商品を作成
        $sellingItems = collect([
            Item::create([
                'name' => '出品商品1',
                'brand' => 'Apple',
                'description' => 'これは出品商品1です。',
                'price' => 50000,
                'image' => 'sample.jpg',
                'user_id' => $user->id,
                'item_condition_id' => $condition->id,
                'is_sold' => false,
            ]),

            Item::create([
                'name' => '出品商品2',
                'brand' => 'Apple',
                'description' => 'これは出品商品2です。',
                'price' => 50000,
                'image' => 'sample.jpg',
                'user_id' => $user->id,
                'item_condition_id' => $condition->id,
                'is_sold' => false,
            ])
        ]);

        // ユーザーのプロフィールも作成
        $profile = Profile::create([
            'user_id' => $user->id,
            'image' => 'test_image.jpg',
            'postal_code' => '100-0001',
            'address' => '東京都千代田区1-1-1',
            'building' => '千代田マンション101'
        ]);

        // 別の出品者のアイテムを1件作り、購入済みにする
        $seller = User::create([
            'name' => 'テストユーザー2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
        ]);

        $purchasedItem = Item::create([
            'name' => '購入商品',
            'brand' => 'Apple',
            'description' => 'これは購入商品です。',
            'price' => 50000,
            'image' => 'sample.jpg',
            'user_id' => $seller->id,
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        Order::create([
            'user_id' => $user->id,
            'item_id' => $purchasedItem->id,
            'price' => $purchasedItem->price,
            'payment_method' => 'カード支払い',
            'address' => $profile->address,
            'postal_code' => $profile->postal_code,
            'building' => $profile->building,
        ]);

         // ユーザーとしてプロフィールページへアクセス
        $response = $this->actingAs($user)->get(route('mypage'));

        // レスポンス確認
        $response->assertStatus(200);
        $response->assertSee('テストユーザー');
        $response->assertSee('test_image.jpg');

        // 出品商品一覧が表示される（名前で確認）
        foreach ($sellingItems as $item) {
            $response->assertSee($item->name);
        }

         // 購入商品タブにアクセス
        $response = $this->actingAs($user)->get(route('mypage', ['page' => 'buy']));

        $response->assertStatus(200);

        // 購入商品が見えること
        $response->assertSee('購入商品');
    }
}
