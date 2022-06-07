<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    private $username = 'test user';
    private $email = 'testuser@example.com';
    private $password = 'password';
    private $password_confirmation = 'password';

    public function test_signup_get(){
        $response = $this->get('accounts/signup');
        $response->assertStatus(200);
        $response->assertViewIs('accounts.signup');
    }

    public function test_signup_post(){
        $response = $this->post(
            '/accounts/signup',
            [
                'name' => $this->username,
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation
            ]);
        $this->assertDatabaseHas('users',[
            'email' => $this->email
        ]);
        $user = User::where('email', $this->email)->first();
        $response->assertRedirect(sprintf('/accounts/detail/%d', $user->id));
    }
}
