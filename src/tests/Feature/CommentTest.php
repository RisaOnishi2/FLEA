<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Item_condition;
use App\Models\Comment;

class CommentTest extends TestCase
{
    use RefreshDatabase;

      /** @test */
    public function logged_in_user_can_post_comment()
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

        // コメント内容
        $data = [
            'comment' => 'これはテストコメントです。',
        ];

        // 認証された状態でPOSTリクエストを送信
        $response = $this->actingAs($user)->post('/item/' . $item->id . '/comments', $data);

        // リダイレクトやレスポンスコードの確認（例: 成功時は302など）
        $response->assertStatus(302);

        // コメントがデータベースに保存されているか確認
        $this->assertDatabaseHas('comments', [
            'comment' => 'これはテストコメントです。',
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

    }

    /** @test */
    public function guest_cannot_post_comment()
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

        // ゲスト状態でコメント送信を試みる
        $response = $this->post('/item/' . $item->id . '/comments', [
            'comment' => '未ログインでのコメント',
        ]);

        // 未認証の場合、通常は302でログインページにリダイレクトされる
        $response->assertRedirect('/login');

        // データベースにコメントが保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'comment' => '未ログインでのコメント',
        ]);
    }

     /** @test */
    public function comment_is_required_validation_error()
    {
         // ユーザーを作成
        $user = User::create([
            'name' => 'テストユーザー3',
            'email' => 'test3@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Item_condition::create(['condition' => '新品']);

        // 商品を作成
        $item = Item::create([
            'name' => 'テスト商品3',
            'brand' => 'Apple',
            'description' => 'これはテスト商品3です。',
            'price' => 50000,
            'image' => 'sample.jpg',
            'user_id' => $user->id,
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        // コメント欄を空で送信
        $response = $this->actingAs($user)->post('/item/' . $item->id . '/comments', [
            'content' => '', // 空
        ]);

        // リダイレクトされる（バリデーションエラーで元のページに戻る想定）
        $response->assertStatus(302);

        // セッションにバリデーションエラーが含まれていることを確認
        $response->assertSessionHasErrors(['comment']);

        // データベースにコメントが保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'comment' => '',
        ]);
    }

     /** @test */
    public function comment_exceeding_255_characters_fails_validation()
    {
         // ユーザーを作成
        $user = User::create([
            'name' => 'テストユーザー4',
            'email' => 'test4@example.com',
            'password' => bcrypt('password'),
        ]);

        $condition = Item_condition::create(['condition' => '新品']);

        // 商品を作成
        $item = Item::create([
            'name' => 'テスト商品4',
            'brand' => 'Apple',
            'description' => 'これはテスト商品4です。',
            'price' => 50000,
            'image' => 'sample.jpg',
            'user_id' => $user->id,
            'item_condition_id' => $condition->id,
            'is_sold' => false,
        ]);

        // 256文字の文字列を生成
        $longComment = str_repeat('あ', 256);

        // コメントを送信（バリデーションエラーを期待）
        $response = $this->actingAs($user)->post('/item/' . $item->id . '/comments', [
            'comment' => $longComment,
        ]);

        // ステータスコード 302（バリデーションエラーによるリダイレクト）
        $response->assertStatus(302);

        // content に対するバリデーションエラーを持っている
        $response->assertSessionHasErrors(['comment']);

        // データベースに保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'comment' => $longComment,
        ]);
    }
}
