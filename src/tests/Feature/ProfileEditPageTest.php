<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileEditPageTest extends TestCase
{
   use RefreshDatabase;

      /** @test */
   public function test_profile_edit_page_displays_existing_user_info()
    {
        Storage::fake('public');

        // テスト用のプロフィール画像
        $image = UploadedFile::fake()->create('profile.jpg', 100, 'image/jpeg')->store('profile_images', 'public');

        // ユーザーを作成
        $user = User::create([
            'name' => 'テスト太郎',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $user->profile()->create([
            'image'         => basename($image),
            'postal_code'   => '123-4567',
            'address'       => '東京都渋谷区道玄坂1-1-1',
            'building'      => '渋谷ビル5F',
        ]);

        // ログインして編集ページにアクセス
        $response = $this->actingAs($user)->get(route('profile.edit'));

        $response->assertStatus(200);

        // プロフィール画像の表示確認（画像名が含まれている）
        $response->assertSee('profile_images/' . basename($image));

        // フォームに過去情報が初期値として表示されているか確認
        $response->assertSee('value="テスト太郎"', false);         // ユーザー名
        $response->assertSee('value="123-4567"', false);          // 郵便番号
        $response->assertSee('value="東京都渋谷区道玄坂1-1-1"', false);  // 住所
        $response->assertSee('value="渋谷ビル5F"', false);        // 建物名
    }
}
