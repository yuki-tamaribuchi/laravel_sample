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

    public function test_detail_not_found_status(){
        $response = $this->get('accounts/detail/1');
        $response->assertStatus(404);
    }

    public function test_detail_get(){
        $user = $this->create_user();
        $response = $this->get(sprintf('accounts/detail/%d', $user->id));
        $response->assertStatus(200);
        $response->assertViewIs('accounts.detail');
        $response->assertViewHas('user', $user);
    }

    public function test_not_authed_update_get(){
        $user = $this->create_user();
        $response = $this->get(sprintf('accounts/update/%d', $user->id));
        $response->assertStatus(403);
    }

    public function test_authed_update_get(){
        $user = $this->create_user();
        $response = $this->actingAs($user)->get(sprintf('accounts/update/%d', $user->id));
        $response->assertStatus(200);
        $response->assertViewIs('accounts.update');
        $response->assertViewHas('user', $user);
    }

    public function test_authed_update_post(){
        $user = $this->create_user();
        $response = $this->actingAs($user)
            ->post(sprintf('accounts/update/%d', $user->id), [
                'name' => 'test user',
                'biograph' => 'test biograph'
            ]);
        $this->assertDatabaseHas('users', [
            'name' => 'test user',
            'biograph' => 'test biograph'
        ]);
        $response->assertRedirect(sprintf('/accounts/detail/%d', $user->id));
    }

    public function test_not_authed_delete_get_method_status(){
        $user = $this->create_user();
        $response = $this->get(sprintf('accounts/delete/%d', $user->id));
        $response->assertStatus(403);
    }

    public function test_auth_delete_get(){
        $user = $this->create_user();
        $response = $this->actingAs($user)->get(sprintf('accounts/delete/%d', $user->id));
        $response->assertStatus(200);
        $response->assertViewIs('accounts.delete');
        $response->assertViewHas('id', $user->id);
    }

    public function test_delete_user_post(){
        $user = $this->create_user();
        $response = $this->actingAs($user)->post(sprintf('accounts/delete/%d', $user->id));
        $this->assertDatabaseMissing('users', [
            'id' => $user->id
        ]);
    }
}
