<?php
namespace app\Models;

class Post {
    public $id;
    public $message_data;
	public $creator_id;
	public $created_at;
	private $usersTable;
    private $postLikesTable;
    private $postSharesTable;
	private $user;

	public function __construct(\Ninja\DatabaseTable $usersTable, \Ninja\DatabaseTable $postLikesTable, \Ninja\DatabaseTable $postSharesTable) {
		$this->usersTable = $usersTable;
        $this->postLikesTable = $postLikesTable;
        $this->postSharesTable = $postSharesTable;
	}

    public function getPostId()
	{
		return $this->id;
    }

    public function  getMsg()
	{
		return $this->message_data;
	}
    
	public function getCreator() {
		if (empty($this->user)) {
			$this->user = $this->usersTable->findById($this->creator_id);
		}
		
		return $this->user;
    }
    
    public function getCreatedAt()
	{
		return $this->created_at;
	}

    public function getLikeCount(){
        $cond1=[
            'column'=>"post_id",
            'match'=>"=",
            'value'=>$this->id
        ];

        $cond2=[
            'joint'=>"AND",
            'column'=>"react_status",
            'match'=>"=",
            'value'=>1
        ];
        $likeCount = count($this->postLikesTable->find([$cond1, $cond2]));
        return $likeCount;
    }

    public function getUserLikeStatus($user){
       
        $cond1=[
            'column'=>"post_id",
            'match'=>"=",
            'value'=>$this->id
        ];

        $cond2=[
            'joint'=>"AND",
            'column'=>"reactor_id",
            'match'=>"=",
            'value'=>$user->getUserId()
        ];

        $postLikeCheck = $this->postLikesTable->find([$cond1, $cond2])[0];

        if(!empty($postLikeCheck))
        {
            return $postLikeCheck->getReactStatus();
        }

        return 0;
    }

    public function getShareCount(){
        $cond1=[
            'column'=>"post_id",
            'match'=>"=",
            'value'=>$this->id
        ];

        $cond2=[
            'joint'=>"AND",
            'column'=>"react_status",
            'match'=>"=",
            'value'=>1
        ];
        $shareCount = count($this->postSharesTable->find([$cond1, $cond2]));
        return $shareCount;
    }

    public function getUserShareStatus($user){
       
        $cond1=[
            'column'=>"post_id",
            'match'=>"=",
            'value'=>$this->id
        ];

        $cond2=[
            'joint'=>"AND",
            'column'=>"reactor_id",
            'match'=>"=",
            'value'=>$user->getUserId()
        ];

        $postShareCheck = $this->postSharesTable->find([$cond1, $cond2])[0];

        if(!empty($postShareCheck))
        {
            return $postShareCheck->getReactStatus();
        }

        return 0;
    }

}