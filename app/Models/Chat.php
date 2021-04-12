<?php
namespace app\Models;

class Chat {

	public $id;
	public $sender_id;
	public $receiver_id;
	public $message_data;
	public $created_at;

	public function getCreationDate()
	{
		return $this->created_at;
	}

	public function getChatMsgId()
	{
		return $this->id;
	}

	public function getSenderId()
	{
		return $this->sender_id;
	}

	public function getReceiverId()
	{
		return $this->receiver_id;
	}

	public function getOtherId($myId)
	{
		$id = ($myId == $this->sender_id)?$this->receiver_id:$this->sender_id;
		return $id;
	}

	public function  getMsg()
	{
		return $this->message_data;
	}

	public function getMessageCount()
	{
		return $this->{"COUNT(message_data)"};
	}
}