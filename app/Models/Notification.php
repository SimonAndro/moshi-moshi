<?php
namespace app\Models;

class Notification{
    const FOLLOWED = 1;
    const RECIPED = 2;
    
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

     /**
     * Calculate and return how old the notification is
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
    
}
?>