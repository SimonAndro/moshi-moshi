<?php
namespace app\Controllers;

class Login {
	private $authentication;

	public function __construct(\Ninja\Authentication $authentication) {
		$this->authentication = $authentication;
	}

	public function loginForm() {
		if($this->authentication->isLoggedIn())
		{
			header("Location: home");
		}
		return ['template' => 'auth/login.html.php', 'title' => 'Log In'];
	}

	public function processLogin() {
		if($this->authentication->isLoggedIn())
		{
			header("Location: home");
		}

		$val = $_POST['val'];
		if ($val and $this->authentication->login($val['username'], $val['password'])) {
			return[
				'msg'=>'success',
				'action'=>'url',
				'value'=> 'home',
				'wrapper'=> false
			];
		}
		else {
			return ['msg' => 'fail',
					'errors' => ['Invalid username/password.'],
					'wrapper'=> false
					];
		}
	}

	public function success() {
		return ['template' => 'loginsuccess.html.php', 'title' => 'Login Successful'];
	}

	public function error() {
		return ['template' => 'loginerror.html.php', 'title' => 'You are not logged in'];
	}

	public function permissionsError() {
		return ['template' => 'permissionserror.html.php', 'title' => 'Access Denied'];
	}

	public function logout() {
		unset($_SESSION);
		session_destroy();
		return ['template' => 'auth/login.html.php', 'title' => 'Log In'];
	}
}
