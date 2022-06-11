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

    public function test_signup_validation_email_regex(){
        $wrong_email = 'testexample.com';
        $response = $this->post(
            '/accounts/signup',
            [
                'name' => $this->username,
                'email' => $wrong_email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users',[
            'email' => $wrong_email
        ]);
    }

    public function test_signup_validation_no_name(){
        $response = $this->post(
            '/accounts/signup',
            [
                'email' =>  $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('users',[
            'email' => $this->email
        ]);
    }

    public function test_signup_validation_no_password(){
        $response = $this->post(
            '/accounts/signup',
            [
                'name' => $this->username,
                'email' => $this->email,
                'password_confirmation' => $this->password_confirmation
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users',[
            'email' => $this->email
        ]);
    }

        public function test_signup_validation_no_password_confirmation(){
        $response = $this->post(
            '/accounts/signup',
            [
                'name' => $this->username,
                'email' =>  $this->email,
                'password' => $this->password
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users',[
            'email' => $this->email
        ]);
    }

    public function test_signup_validation_password_confirmation(){
        $response = $this->post(
            '/accounts/signup',
            [
                'name' => $this->username,
                'email' => $this->email,
                'password' => 'password',
                'password_confirmation' => 'wrongpassword'
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users',[
            'email' => $this->email
        ]);
    }

    public function test_signup_validation_password_length_less_than_five(){
        $response = $this->post(
            '/accounts/signup',
            [
                'name' => $this->username,
                'email' => $this->email,
                'password' => 'pswd',
                'password_confirmation' => 'pswd'
            ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users',[
            'email' => $this->email
        ]);
    }
    
    public function test_login_get(){
        $response = $this->get('accounts/login');
        $response->assertStatus(200);
        $response->assertViewIs('accounts.login');
    }

    public function test_login_post(){
        $user = $this->create_user();
        $response = $this->post(
            '/accounts/login',
            [
                'email' => $user->email,
                'password' => 'password' 
            ]);
        $this->assertAuthenticated();
    }
}
