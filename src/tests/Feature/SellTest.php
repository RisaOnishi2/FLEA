<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Category;
use App\Models\Condition;
use App\Models\Item;
use App\Models\Item_condition;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SellTest extends TestCase
{
    use RefreshDatabase;

     /** @test */
    public function logged_in_user_can_create_item_with_valid_input()
    {
        // 画像の保存をフェイク
        Storage::fake('public');

        // テスト用のカテゴリと商品の状態を作成
        $category = Category::create(['category' => '家電']);
        $condition = Item_condition::create(['condition' => '新品']);

        // ログインユーザー作成
        $user = User::create([
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // 商品データ
        $formData = [
            'categories'       => [$category->id],
            'item_condition_id'=> $condition->id,
            'name'             => 'テスト商品',
            'description'      => 'これはテスト商品の説明です。',
            'price'            => 5000,
            'image'            => UploadedFile::fake()->create('test.jpg', 100, 'image/jpeg'),
        ];

        // 商品作成ページにアクセス（表示確認）
        $response = $this->actingAs($user)->get(route('sell.create'));
        $response->assertStatus(200);
        $response->assertSee('出品'); // 出品フォームの文字などを確認

        // 商品を保存（POST送信）
        $postResponse = $this->post(route('sell.store'), $formData);
        $response->assertSessionHasNoErrors();

        // 成功後のリダイレクト確認（適宜修正）
        $postResponse->assertRedirect('/mypage');

        // DBに保存されているか確認
        $this->assertDatabaseHas('items', [
            'user_id'           => $user->id,
            'item_condition_id' => $condition->id,
            'name'              => 'テスト商品',
            'description'       => 'これはテスト商品の説明です。',
            'price'             => 5000,
        ]);

        // 画像が保存されているか確認
        $item = Item::first();
        Storage::disk('public')->assertExists('sample_images/' . $item->image);
    }

}
