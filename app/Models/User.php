<?php
namespace app\Models;

class User {

	const EDIT_JOKES = 1;
	const DELETE_JOKES = 2;
	const ADD_CATEGORIES = 4;
	const EDIT_CATEGORIES = 8;
	const REMOVE_CATEGORIES = 16;
	const EDIT_USER_ACCESS = 32;

	public $id;
	public $name;
	public $email;
	public $gender;
	public $about;
	public $password;
	public $created_at;
	public $profilePictures = [];

	private $chatsTable;
	private $filesTable;
	private $relationshipsTable;
	private $notificationsTable;

	public function __construct(\Ninja\DatabaseTable $relationshipsTable,\Ninja\DatabaseTable $chatTable,\Ninja\DatabaseTable $fileTable,\Ninja\DatabaseTable $notificationsTable) {
		$this->relationshipsTable = $relationshipsTable;
		$this->chatsTable = $chatTable;
		$this->filesTable = $fileTable;
		$this->notificationsTable = $notificationsTable;
	}

	public function getNewNotificationCount()
	{
		$cond1 = [
			'column'=>'receiver_id',
			'match'=>'=',
			'value'=>$this->getUserId()
		];
		$cond2 = [
			'joint'=>'AND',
			'column'=>'is_seen',
			'match'=>'=',
			'value'=>0
		];
		
		return count($this->notificationsTable->find([$cond1,$cond2]));
	}

	public function getUserId(){
		return $this->id;
	}

	public function getName(){
		return $this->name;
	}

	public function getAbout(){
		return $this->about;
	}

	
	public function getProfilePicture(){
		$condition_1 = [
			'column'=>'user_id',
			'match'=>"=",
			'value'=>$this->id
		];
		$condition_2 = [
			'joint'=>"AND",
			'column'=>'role',
			'match'=>"=",
			'value'=>\app\Models\File::PROFILE_PIC
		];
		$file = $this->filesTable->find([$condition_1,$condition_2]);
		
		if($file[0]){
			return $file[0]->getFileName();
		}else{
			return false;
		}
	}

	public function getLastMessage($peerId){
		$cond1 = [
			'open_brackets'=>1,
			'column'=>'sender_id',
			'match'=>'=',
			'value'=>$peerId
		];
		$cond2 = [
			'joint'=>'OR',
			'column'=>'receiver_id',
			'match'=>'=',
			'value'=>$peerId,
			'close_brackets'=>1,
		];
		$cond3 = [
			'joint'=>'AND',
			'open_brackets'=>1,
			'column'=>'sender_id',
			'match'=>'=',
			'value'=>$this->getUserId()
		];
		$cond4 = [
			'joint'=>'OR',
			'column'=>'receiver_id',
			'match'=>'=',
			'value'=>$this->getUserId(),
			'close_brackets'=>1,
		];
		$chat = $this->chatsTable->find([$cond1,$cond2,$cond3,$cond4],'created_at DESC',1);
		return $chat;
	}


	public function isFriendsWith($peerId)
	{
		$cond2 = [
			'open_brackets'=> 2,
			'column'=>'follower_id',
			'match'=>'=',
			'value'=>$this->getUserId()
		];
		$cond3 = [
			'joint'=>'AND',
			'close_brackets'=>1,
			'column'=>'followee_id',
			'match'=>'=',
			'value'=>$peerId
		];
		$cond4 = [
			'joint'=>'OR',
			'open_brackets'=> 1,
			'column'=>'follower_id',
			'match'=>'=',
			'value'=>$peerId
		];
		$cond5 = [
			'joint'=>'AND',
			'close_brackets'=>2,
			'column'=>'followee_id',
			'match'=>'=',
			'value'=>$this->getUserId()
		];
		// $cond1 = [
		// 	'joint'=>'AND',
		// 	'column'=>'followed_back',
		// 	'match'=>'=',
		// 	'value'=>0
		// ];
		$cond6 = [
			'joint'=>'AND',
			'column'=>'unfollowed',
			'match'=>'=',
			'value'=>0
		];

		$relationship = $this->relationshipsTable->find([$cond2,$cond3,$cond4,$cond5,$cond6]);

		if($relationship[0])
		{
			return $relationship[0];
			
		}else{
			return false;
		}
	}

	public function getRelationChanger($peerId)
	{
		//dump_to_file("relationship_check");
		$cond1 = [
			'open_brackets'=> 1,
			'column'=>'follower_id',
			'match'=>'=',
			'value'=>$peerId
		];
		$cond2 = [
			'joint'=>'AND',
			'close_brackets'=>1,
			'column'=>'followee_id',
			'match'=>'=',
			'value'=>$this->getUserId()
		];
		$cond3 = [
			'joint'=>'OR',
			'open_brackets'=> 1,
			'column'=>'follower_id',
			'match'=>'=',
			'value'=>$this->getUserId()
		];
		$cond4 = [
			'joint'=>'AND',
			'close_brackets'=>1,
			'column'=>'followee_id',
			'match'=>'=',
			'value'=>$peerId
		];

		$changer = "follow";
		if($relationship_check = $this->relationshipsTable->find([$cond1,$cond2,$cond3,$cond4])[0])
		{
			if($relationship_check->getFollowerId() == $this->getUserId())
			{
				if($relationship_check->isFollowedBack())
				{
					$changer = "unrecip";
				}else{
					$changer = "recip";
				}

			}else{
				if($relationship_check->isUnfollowed())
				{
					$changer = "follow";
				}else{
					$changer = "unfollow";
				}
			}
			
		}
		//dump_to_file($relationship_check);
		return $changer;
	}

	/**
     * Calculate and return how old the user is
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

	public function hasPermission($permission) {
		return $this->permissions & $permission;  
	}
}