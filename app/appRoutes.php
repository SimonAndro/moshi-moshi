<?php
namespace app;

class appRoutes implements \Ninja\Routes {
	private $usersTable;
	private $chatsTable;
	private $filesTable;
	private $thumbnailsTable;
	private $galleryFoldersTable;
	private $relationshipsTable;
	private $postsTable;
	private $notificationsTable;
	private $postLikesTable;
	private $postSharesTable;
	private $authentication;

	public function __construct() {
		include __DIR__ . '/includes/DatabaseConnection.php';

		$this->thumbnailsTable = new \Ninja\DatabaseTable($pdo,'thumbnail','id','\app\Models\Thumbnail');
		$this->filesTable = new \Ninja\DatabaseTable($pdo,'file','id','\app\Models\File',[&$this->thumbnailsTable]);
		$this->galleryFoldersTable = new \Ninja\DatabaseTable($pdo,'galleryfolder','id','\app\Models\GalleryFolder',[&$this->filesTable]);
		$this->chatsTable = new \Ninja\DatabaseTable($pdo, 'chat', 'id', '\app\Models\Chat');
		$this->notificationsTable = new \Ninja\DatabaseTable($pdo, 'notification', 'id', '\app\Models\Notification',[&$this->usersTable]);
		$this->usersTable = new \Ninja\DatabaseTable($pdo, 'user', 'id', '\app\Models\User',[&$this->relationshipsTable,&$this->chatsTable, &$this->filesTable, &$this->notificationsTable]);
		$this->relationshipsTable = new \Ninja\DatabaseTable($pdo, 'relationship', 'id', '\app\Models\Relationship',[&$this->usersTable]);
		$this->postLikesTable = new \Ninja\DatabaseTable($pdo,'postlike','id','\app\Models\PostLike');
		$this->postSharesTable = new \Ninja\DatabaseTable($pdo,'postshare','id','\app\Models\PostShare');
		$this->postsTable = new \Ninja\DatabaseTable($pdo, 'post', 'id', '\app\Models\Post',[&$this->usersTable,&$this->postLikesTable,&$this->postSharesTable]);
		$this->authentication = new \Ninja\Authentication($this->usersTable, 'email', 'password');
	}

	public function getRoutes(): array {
		$homeController = new \app\Controllers\Home($this->authentication,$this->usersTable, $this->relationshipsTable, $this->chatsTable,$this->postsTable,$this->notificationsTable, $this->galleryFoldersTable,$this->filesTable,$this->thumbnailsTable,$this->postLikesTable,$this->postSharesTable);
		$profileController = new \app\Controllers\Profile($this->authentication,$this->usersTable,$this->relationshipsTable,$this->postsTable);
		$userController = new \app\Controllers\Register($this->usersTable,$this->filesTable,$this->relationshipsTable,$this->authentication);
		$loginController = new \app\Controllers\Login($this->authentication);

		$routes = [
			'user-register' => [
				'GET' => [
					'controller' => $userController,
					'action' => 'registrationForm'
				],
				'POST' => [
					'controller' => $userController,
					'action' => 'registerUser'
				]
			],
			'register-success'=>[
				'GET'=>[
					'controller'=> $userController,
					'action'=>'success'
				],
				'POST'=>[
					'controller'=> $userController,
					'action'=>'success'
				]
			],
			'login-error' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'error'
				]
			],
			'logout' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'logout'
				]
			],
			'login' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'loginForm'
				],
				'POST' => [
					'controller' => $loginController,
					'action' => 'processLogin'
				]
			],
			'home' => [
				'GET' => [
					'controller' => $homeController,
					'action' => 'home'
				],
				'POST' => [
					'controller' => $homeController,
					'action' => 'homePost'
				],
				'login' => true
			],
			'profile' => [
				'GET' => [
					'controller' => $homeController,
					'action' => 'profile'
				],
				'POST' => [
					'controller' => $homeController,
					'action' => 'editProfile'
				],
				'login' => true
			],
			'gallery'=>[
				'GET' => [
					'controller' => $homeController,
					'action' => 'loadGalleryMedia'
				],
				'POST' => [
					'controller' => $homeController,
					'action' => 'gallery'
				],
				'login' => true
			],
			'settings' => [
				'GET' => [
					'controller' => $settingController,
					'action' => 'settings'
				],
				'POST' => [
					'controller' => $settingController,
					'action' => 'editsettings'
				],
				'login' => true
			],
			'post_reaction'=>[
				'GET' => [
					'controller' => $homeController,
					'action' => 'loadPostReaction'
				],
				'POST' => [
					'controller' => $homeController,
					'action' => 'postReaction'
				],
				'login' => true
			]
		];

		return $routes;
	}

	public function getAuthentication(): \Ninja\Authentication {
		return $this->authentication;
	}

	public function checkPermission($permission): bool {
		$user = $this->authentication->getUser();

		if ($user && $user->hasPermission($permission)) {
			return true;
		} else {
			return false;
		}
	}

}