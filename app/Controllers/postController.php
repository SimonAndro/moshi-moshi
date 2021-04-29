<?php
namespace app\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;
use \Ninja\Uploader;

class postController {
	private $usersTable;
	private $relationshipsTable;
	private $postsTable;
	private $notificationsTable;
	private $authentication;
	private $galleryFoldersTable;
	private $filesTable;
	private $thumbnailsTable;
	private $postLikesTable;
	private $postSharesTable;
	private $postRepliesTable;

    private  $User;


	public function __construct(Authentication $authentication, DatabaseTable $usersTable, DatabaseTable $relationshipsTable, DatabaseTable $postsTable, DatabaseTable $notificationsTable, DatabaseTable $galleryFoldersTable,DatabaseTable $filesTable,DatabaseTable $thumbnailsTable,DatabaseTable $postLikesTable,DatabaseTable $postSharesTable,DatabaseTable $postRepliesTable) {
		$this->authentication = $authentication;
		$this->usersTable = $usersTable;
		$this->relationshipsTable = $relationshipsTable;
		$this->postsTable = $postsTable;
		$this->notificationsTable = $notificationsTable;
		$this->galleryFoldersTable = $galleryFoldersTable;
		$this->filesTable = $filesTable;  
		$this->thumbnailsTable = $thumbnailsTable;
		$this->postLikesTable = $postLikesTable;
		$this->postSharesTable = $postSharesTable;
		$this->postRepliesTable = $postRepliesTable;

        $this->User = $this->authentication->getUser();

        dump_to_file($_GET); // debugging
        dump_to_file($_POST);
        
	}

    /**
     * Load users posts
     */
    public function index()
    {
        $userId = $_GET['userId'];
        // ...
    }

    public function createPost()
    {
        
    }

    public function getFollowing()
    {
        $msg = "fail";

        $target = $_GET['target'];
        $targetId = $_GET['targetId'];
        if($target == "followers")
        {
            $sql = "SELECT * FROM ".$this->relationshipsTable->getTableName()." WHERE followee_id=?";
            $following = $this->relationshipsTable->customQuery($sql, $targetId);

            $msg = "success";
            
        }elseif($target == "following")
        {
            $sql = "SELECT * FROM ".$this->relationshipsTable->getTableName()." WHERE follower_id=?";
            $following = $this->relationshipsTable->customQuery($sql, $targetId);

            $msg = "success";
            \dump_to_file($following);
        }

       
        if($msg == "success")
        {
            $fHtml = array();
            foreach($following as $f)
            {
                $peer = $f->getPeer($this->User->getUserId());
                $row = loadTemplate('home/fragments/users/user.main.search.html.php',
                                    [
                                        'user'=>$peer,
                                        'me'=>$this->User,
                                    ]);
                array_push($fHtml, $row);
            }
            if(empty($fHtml))
            {
                $fHtml[] = "<p style='text-align:center;'>No results found<p>";
            }
        }

        return[
            'msg'=>$msg,
            'wrapper'=>false,
            'fHtml'=>$fHtml
        ];
    }
    public function postFollowing()
    {

    }

	
}