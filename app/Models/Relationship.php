<?php
namespace app\Models;

class Relationship {

	public $id;
	public $created_at;
	public $follower_id;
	public $followee_id;
	public $followed_back;
	public $unfollowed;

	private $usersTable;
	public function __construct(\Ninja\DatabaseTable $usersTable) {
		$this->usersTable = $usersTable;
	}

	public function getRelationShipId(){
		return $this->id;
	}
	
	public function getPeer($myId){
		$id = $myId == $this->follower_id?$this->followee_id:$this->follower_id;
		return $this->usersTable->findById($id);
	}

	public function getFolloweeId()
	{
		return $this->followee_id;
	}

	public function getFollowerId()
	{
		return $this->follower_id;
	}

	public function isFollowedBack()
	{
		return ($this->followed_back == 1)?true:false;
	}

	public function isUnfollowed()
	{
		return ($this->unfollowed == 1)?true:false;
	}

}