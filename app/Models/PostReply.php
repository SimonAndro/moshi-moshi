<?php
namespace app\Models;

class PostReply{
    public $id;
    public $post_id;
    public $creator_id;
    public $created_at;
    public $message_data;
    private $usersTable;
	private $user;

    public function __construct(\Ninja\DatabaseTable $usersTable) {
		$this->usersTable = $usersTable;
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

    public function getMsg()
    {
        return $this->message_data;
    }

    public function getPostReplyId()
    {
        return $this->id;
    }

    public function getPostId(){
        return $this->post_id;
    }
}