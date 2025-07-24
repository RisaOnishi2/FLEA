<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Profile;
use App\Models\Item_condition;

class PaymentTest extends TestCase
{
   use RefreshDatabase;

      /** @test */
    public function test_selected_payment_method_is_displayed_on_purchase_page()
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

        $paymentMethod = 'カード支払い';

        // Act
        $response = $this->actingAs($user)->get("/purchase/{$item->id}?payment_method={$paymentMethod}");

        // Assert
        $response->assertStatus(200);
        $response->assertSee($paymentMethod); // Blade に選択内容が表示されているか

    }
}
