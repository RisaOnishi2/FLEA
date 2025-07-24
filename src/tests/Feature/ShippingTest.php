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

class ShippingTest extends TestCase
{
    use RefreshDatabase;

      /** @test */
    public function test_shipping_address_is_reflected_on_purchase_page_after_edit()
    {
        // ユーザーを作成
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

        // ユーザーのプロフィールも作成（コントローラーで使用されるため）
        Profile::create([
            'user_id' => $user->id,
            'image' => null,
            'postal_code' => '100-0001',
            'address' => '東京都千代田区1-1-1',
            'building' => '千代田マンション101'
        ]);

        // Act①: ユーザーとしてログインし、送付先住所を更新
        $this->actingAs($user)->post("/purchase/{$item->id}/update", [
            'postal_code' => '150-0001',
            'address'     => '東京都渋谷区神南1-1-1',
            'building'    => '渋谷ビル201',
        ]);

        // Act②: セッションを明示的に指定して再度購入画面を開く
        $response = $this->withSession([
            'shipping_address' => [
            'postal_code' => '150-0001',
            'address'     => '東京都渋谷区神南1-1-1',
            'building'    => '渋谷ビル201',
            ]
        ])->actingAs($user)->get("/purchase/{$item->id}");
    
        // Assert: 登録した住所が画面に表示されているか
        $response->assertStatus(200);
        $response->assertSee('150-0001');
        $response->assertSee('東京都渋谷区神南1-1-1');
        $response->assertSee('渋谷ビル201');
    }

    public function test_shipping_address_is_saved_to_order_after_purchase()
    {
        // ユーザーを作成
        $user = User::create([
            'name' => 'テストユーザー2',
            'email' => 'test2@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Item_condition::create(['condition' => '新品']);

        // 商品を作成
        $item = Item::create([
            'name' => 'テスト商品2',
            'brand' => 'Apple',
            'description' => 'これはテスト商品2です。',
            'price' => 50000,
            'image' => 'sample.jpg',
            'user_id' => $user->id,
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        // ユーザーのプロフィールも作成（コントローラーで使用されるため）
        Profile::create([
            'user_id' => $user->id,
            'image' => null,
            'postal_code' => '100-0001',
            'address' => '東京都千代田区1-1-1',
            'building' => '千代田マンション101'
        ]);

         // Step 1: ユーザーとしてログインして住所変更リクエストを送る
        $this->actingAs($user)->post("/purchase/{$item->id}/update", [
            'postal_code' => '160-0022',
            'address'     => '東京都新宿区新宿1-1-1',
            'building'    => '新宿ビル303',
        ]);

        // Step 2: セッションを引き継いで購入リクエストを送信
        $response = $this->withSession([
            'shipping_address' => [
                'postal_code' => '160-0022',
                'address'     => '東京都新宿区新宿1-1-1',
                'building'    => '新宿ビル303',
            ]
        ])->actingAs($user)->post("/purchase/{$item->id}", [
            'payment_method' => 'コンビニ払い',
            'address'        => '東京都新宿区新宿1-1-1',
            'postal_code'    => '160-0022',
            'building'       => '新宿ビル303',
        ]);

        // Step 3: DBに注文が正しく保存されているかを確認
        $this->assertDatabaseHas('orders', [
            'user_id'      => $user->id,
            'item_id'      => $item->id,
            'price'        => $item->price,
            'payment_method' => 'コンビニ払い',
            'postal_code'  => '160-0022',
            'address'      => '東京都新宿区新宿1-1-1',
            'building'     => '新宿ビル303',
        ]);

        // 商品の is_sold フラグも true になっていることを確認
        $this->assertEquals(1, $item->fresh()->is_sold);
    }
}
