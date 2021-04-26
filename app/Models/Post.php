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
    
    /**
     * Calculate and return how old the post is
     */
    public function howOld()
    {
        $now = time();
        $dif = $now - $this->created_at;
        if($dif < 60)
        {
            $age['num'] = $dif;
            $age['mag'] = 's';
        }else
        {
            $dif = $dif/60;
            if($dif < 60)
            {
                $age['num'] = $dif;
                $age['mag'] = 'm';
            }else{
                $dif = $dif/60;
                if($dif<24)
                {
                    $age['num'] = $dif;
                    $age['mag'] = 'h';
                }else{
                    $dif = $dif/24;
                    if($dif<30) // assume month is 30 days
                    {
                        $age['num'] = $dif;
                        $age['mag'] = 'd';
                    }else{
                        $dif = $dif/12; 
                        if($dif<12)
                        {
                            $age['num'] = $dif;
                            $age['mag'] = 'm';
                        }else{
                            $age['num'] = $dif;
                            $age['mag'] = 'y';
                        }
                    }
                }
            }
        }
        $age['num'] = floor($age['num']);
        return $age;
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