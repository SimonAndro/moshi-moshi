<?php
namespace app\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;
use \Ninja\Uploader;

class Profile {

    private $authentication;
    private $usersTable;
	private $relationshipsTable;
	private $postsTable;
	private $mySelf;

	public function __construct(Authentication $authentication, DatabaseTable $usersTable, DatabaseTable $relationshipsTable,DatabaseTable $postsTable) {
		$this->authentication = $authentication;
		$this->usersTable = $usersTable;
		$this->relationshipsTable = $relationshipsTable;
		$this->postsTable = $postsTable;
	}

    public function profile(){
        $who = $_GET['who'];

        $user = $this->authentication->getUser();
		if(!$user)	
		{
			header('Location: login');
            
		}else{
			$this->mySelf = $user;
		}

        if(!isset($who) or empty($this->usersTable->findById($who)))
        {
            return
            [
                loadTemplate("error/404.html"),
                'wrapper' => 0, 
                'noAjax' => true
            ];
        }

        return 
        [
            'template' => 'home/index.html.php',
            'variables' => 
            [
                'user'=>$user,
                'feedData'=>$feedData,
                'notifData' => $notifData,
                'searchData'=>$searchData,
                'profile'=>"yes"
            ]
        ];
    }
}