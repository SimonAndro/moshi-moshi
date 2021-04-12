<?php
namespace app\Models;

class Post {
    public $id;
    public $message_data;
	public $creator_id;
	public $created_at;
	private $usersTable;
	private $user;

	public function __construct(\Ninja\DatabaseTable $usersTable) {
		$this->usersTable = $usersTable;
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

}