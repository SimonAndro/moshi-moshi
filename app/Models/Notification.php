<?php
namespace app\Models;

class Notification{
    const FOLLOWED = 1;
    const RECIPED = 2;
    const REPOSTED = 3;
    const LIKED = 4;
    
    public $id;
    public $receiver_id;
    public $sender_id;
    public $notify_message_data;
    public $notify_type;
    public $is_seen;
    public $created_at;

    private $NotifReceiver;
    private $NotifSender;
    private $usersTable;

    public function __construct(\Ninja\DatabaseTable $usersTable) {
		$this->usersTable = $usersTable;
	}

    public function getNotifId()
    {
        return $this->id;
    }

    public function getNotifReceiver()
    {
        if (empty($this->NotifReceiver)) {
			$this->NotifReceiver = $this->usersTable->findById($this->receiver_id);
		}
		return $this->NotifReceiver;
    }

    public function getNotifSender()
    {
        if (empty($this->NotifSender)) {
			$this->NotifSender = $this->usersTable->findById($this->sender_id);
		}
		return $this->NotifSender;
    }

    public function getNotifMsg()
    {
        return $this->notify_message_data;
    }

    public function getNotifType()
    {
        return $this->notify_type;
    }

    public function isNotifSeen()
    {
        return $this->is_seen;
    }

    public function getNotifDate()
    {
        return $this->created_at;
    }

    public function getCreatedAt()
	{
		return $this->created_at;
	}
    
}
?>