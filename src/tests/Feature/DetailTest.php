<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Item_condition;
use App\Models\Comment;
use App\Models\Like;

class DetailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function all_information_on_the_product_detail_page_is_displayed()
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

        $category1 = Category::create(['category' => '家電']);
        $category2 = Category::create(['category' => '生活用品']);

        // 中間テーブルに紐づける
        $item->categories()->attach([$category1->id, $category2->id]);

        // いいねとコメントを追加
        $item->likes()->create(['user_id' => $user->id]);
        $comment = $item->comments()->create([
            'user_id' => $user->id,
            'comment' => '良さそうな商品ですね！',
        ]);

        // 詳細ページにアクセス
        $response = $this->get('/item/' . $item->id);

        // 表示内容をアサート
        $response->assertStatus(200);
        $response->assertSee('テスト商品'); // 商品名
        $response->assertSee('Apple'); // ブランド名
        $response->assertSee('¥50,000 (税込)'); // 価格
        $response->assertSee('これはテスト商品です。'); // 商品説明
        $response->assertSee('家電'); // カテゴリ1
        $response->assertSee('生活用品'); // カテゴリ1
        $response->assertSee('新品'); // 状態
        $response->assertSee('1'); // いいね数・コメント数（数値的に）
        $response->assertSee('テストユーザー'); // コメントしたユーザー
        $response->assertSee('良さそうな商品ですね！'); // コメント内容
        $response->assertSee('sample.jpg'); // 画像ファイル名が含まれているか
    }
}
