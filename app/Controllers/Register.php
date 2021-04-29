<?php
namespace app\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;
use \Ninja\Uploader;

class Register {
	private $usersTable;
	private $filesTable;
	private $relationshipsTable;
	private $authentication;

	public function __construct(DatabaseTable $usersTable, DatabaseTable $filesTable,
		DatabaseTable $relationshipsTable, Authentication $authentication) {
		$this->usersTable = $usersTable;
		$this->relationshipsTable = $relationshipsTable;
		$this->filesTable = $filesTable;
		$this->authentication = $authentication;
	}


	public function success() {

		if (!$_SESSION['reg']) { // not in the registration process
			header('location:login');
		}

		$val = $_POST['val'];
		if($val)
		{
  

			
			if($user = $this->authentication->getUser())
			{
				$me  = $user;
				$userId = $user->getUserId();
			}else{
				header('location:login');
			}

			if($val['step']==1)
			{				

				// save profile about and picuture, and redirect to next step
				if($val['about'])
				{
					
					$updateUser['about'] = $val['about'];
					$updateUser['id'] = $userId; 
					$this->usersTable->save($updateUser);
					
				}
				
				if($input = inputFile('file'))
				{
  
					$upload = new Uploader($input, 'image');
					$upload->setPath("/files/images/".$userId .'/'.time()."/");
					if ($upload->passed()) {

  
						$result = $upload->uploadProfilePicture()->result();
						$file['user_id'] = $userId;
						$file['file_type'] = 'image';
						$file['file_name'] = $result;
						$file['role'] = \app\Models\File::PROFILE_PIC;
  
						$this->filesTable->save($file);
					} else {

						$fragment = loadTemplate('auth/fragments/profile.about.html.php');
						return ['template' => 'auth/register.success.html.php', 
								'variables' => [
									'successtitle' => 'Set Up your profile',
									'stepcount' => 1,
									'buttonTitle'=>'Next',
									'fragment' => $fragment,
									'errors' => $upload->getError()
									],
									'wrapper'=> true
								];
					}
				}

				// get friend recommendation for this 
				$users = $this->usersTable->findAll('id DESC', 5);
				
				
				$friendsgts = Array() ;
				foreach($users as $user)
				{
					if($user->getUserId()==$userId){
						continue;
					}
					$row = loadTemplate('home/fragments/users/user.main.search.html.php',
					[
						'user'=>$user,
						'me'=>$me,
					]);
					array_push($friendsgts,$row);
				}
				
				$fragment = loadTemplate('auth/fragments/profile.friends.html.php',
				['friendsgts'=>$friendsgts]);
				return ['template' => 'auth/register.success.html.php', 
					'variables' => [
					'successtitle' => 'People you may know',
					'stepcount' => 2,
					'buttonTitle'=>'Done',
					'fragment' => $fragment
					],
					'wrapper'=> true
				];
			}else{
  
				unset($_SESSION['reg']);
				header('location: home'); // after successful registration, send user to home

			}
		
		}

		$fragment = loadTemplate('auth/fragments/profile.about.html.php');
		return ['template' => 'auth/register.success.html.php', 
				'variables' => [
					'successtitle' => 'Set Up your profile',
					'stepcount' => 1,
					'buttonTitle'=>'Next',
					'fragment' => $fragment,
					],
					'wrapper'=> true
				];
	}

	public function registerUser() {
		$val = $_POST['val'];

		//Assume the data is valid to begin with
		$valid = true;
		$errors = [];

		//But if any of the fields have been left blank, set $valid to false

		if (empty($val['email'])) {
			$valid = false;
			$errors[] = 'Email cannot be blank';
		}
		else if (filter_var($val['email'], FILTER_VALIDATE_EMAIL) == false) {
			$valid = false;
			$errors[] = 'Invalid email address';
		}
		else { //if the email is not blank and valid:
			//convert the email to lowercase
			$val['email'] = strtolower($val['email']);

			//search for the lowercase version of `$val['email']`
			if (count($this->usersTable->find([['column'=>'email', 'match'=>'=','value'=>$val['email']]])) > 0) {
				$valid = false;
				$errors[] = 'That email address is already registered';
			}
		}


		if (empty($val['pwd1']) || empty($val['pwd2'])) {
			$valid = false;
			$errors[] = 'Password cannot be blank';
		}
		else if($val['pwd1'] != $val['pwd2'] )
		{
			$valid = false;
			$errors[] = 'Passwords dont match';
		}

		if(empty($val['gender']) )
		{
			$valid = false;
			$errors[] = 'Please select gender';
		}

		//If $valid is still true, no fields were blank and the data can be added
		if ($valid == true) {
			//Hash the password before saving it in the database
			$val['password'] = password_hash($val['pwd1'], PASSWORD_DEFAULT);

			//create a name from the user email
			$arr = explode("@", $val['email'], 2);
			$val['name'] = $arr[0];
			$pwd = $val['pwd1'];
			unset($val['pwd1']); unset($val['pwd2']);

			$val['created_at'] = time(); 

			//When submitted, the $val variable now contains a lowercase value for email
			//and a hashed password
			$this->usersTable->save($val);

			if ($this->authentication->login($val['email'], $pwd)) {
				$_SESSION['reg'] = 1;
			}else{
				[
					'msg'=>'fail',
					'errors'=>'auto login failed',
					'wrapper'=> false
				];
			}
			

			return[
				'msg'=>'success',
				'action'=>'url',
				'value'=> 'register-success',
				'wrapper'=> false
			];

		}
		else {
			//If the data is not valid, show the form again
			return [
					'msg'=>'fail',
					'errors' => $errors,
				    'val' => $val,
					'wrapper'=> false
				   ]; 
		}
	}

	public function list() {
		$authors = $this->authorsTable->findAll();

		return ['template' => 'authorlist.html.php',
				'title' => 'Author List',
				'variables' => [
						'authors' => $authors
					]
				];
	}

	public function permissions() {

		$author = $this->authorsTable->findById($_GET['id']);

		$reflected = new \ReflectionClass('\app\Models\Author');
		$constants = $reflected->getConstants();

		return ['template' => 'permissions.html.php',
				'title' => 'Edit Permissions',
				'variables' => [
						'author' => $author,
						'permissions' => $constants
					]
				];	
	}

	public function savePermissions() {
		$author = [
			'id' => $_GET['id'],
			'permissions' => array_sum($_POST['permissions'] ?? [])
		];

		$this->authorsTable->save($author);

		header('location: author-list');
	}
}