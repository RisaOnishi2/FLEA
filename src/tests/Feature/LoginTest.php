<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function email_is_required()
    {
        $response = $this->from('/login')->post('/login', [
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_is_required()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'test-mail@example.com',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function input_information_is_incorrect()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'not_exist@example.com',
            'password' => 'invalid-password',
        ]);

        // ログイン画面に戻っているか確認
        $response->assertRedirect('/login');

        // セッションに 'login' キーでエラーメッセージがあるか確認
        $response->assertSessionHasErrors([
            'login' => 'ログイン情報が登録されていません。',
        ]);

        // 未ログイン状態の確認
        $this->assertGuest();
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        // 事前にユーザー作成（DBに登録）
        $user = User::create([
            'name' => 'テストユーザー',
            'email' => 'test_email@example.com',
            'password' => Hash::make('password123'),
        ]);

        // 正しい情報でログインPOST
        $response = $this->from('/login')->post('/login', [
            'email' => 'test_email@example.com',
            'password' => 'password123',
        ]);

        // リダイレクトを確認
        $response->assertRedirect('/');

        // 正しくログインされているか確認
        $this->assertAuthenticated();
        $this->assertAuthenticatedAs($user);
    }
}
