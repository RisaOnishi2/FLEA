<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function authenticated_user_can_logout_successfully()
    {
        // ユーザーを作成しログインさせる
        $user = User::factory()->create();

        // 認証状態でログアウトを実行
        $response = $this->actingAs($user)->post('/logout');

        // リダイレクト先を確認
        $response->assertRedirect('/'); 

        // ログアウト済みであることを確認
        $this->assertGuest();
    }
}
