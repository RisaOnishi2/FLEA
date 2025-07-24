<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
  
    use RefreshDatabase;

    /** @test */
    public function name_is_required()
    {
        $response = $this->post('/register', [
            'email' => 'test-mail@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('name');
    }

    /** @test */
    public function email_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function password_is_required()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test-mail@example.com',
            'password_confirmation' => 'password123',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function password_must_be_min_8_characters()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test-mail@example.com',
            'password' => 'short',
            'password_confirmation' => 'short',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function password_do_not_match()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test-mail@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password456',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
     public function valid_user_data_registers_successfully_and_redirects_to_login()
    {
        // 検証用のデータ
        $data = [
            'name' => 'テスト花子',
            'email' => 'test_hanako@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // POSTで登録
        $response = $this->post('/register', $data);

        // usersテーブルに登録されたことを確認
        $this->assertDatabaseHas('users', [
            'email' => 'test_hanako@example.com',
        ]);

        $response->assertRedirect('/mypage/profile');

        $this->assertAuthenticated(); // ログイン済み確認
    }
}
