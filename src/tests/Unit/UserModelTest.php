<?php

namespace Tests\Unit;

use Tests\TestCase;

use App\Models\User;

class UserModelTest extends TestCase
{
	public $password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
	public $email = 'testuser@example.com';
	public $username = 'test user';
	
	public function test_save(){
		$user = new User;
		$user->name = $this->username;
		$user->email = $this->email;
		$user->password = $this->password;
		$this->assertTrue($user->save());
		$this->assertDatabaseHas('users', [
			'name' => $this->username,
			'email' => $this->email,
			'password' => $this->password
		]);
	}

	public function test_update(){
		$new_username = 'new test user';
		$user = User::where('email', $this->email)->first();
		$user->name = $new_username;
		$this->assertTrue($user->save());
		$this->assertDatabaseHas('users', [
			'name' => $new_username,
			'email' => $this->email
		]);
	}

	public function test_delete(){
		$user = User::where('email', $this->email);
		$this->assertTrue($user->delete()==1);
		$this->assertDatabaseMissing('users', [
			'email' => $this->email
		]);
	}
}
