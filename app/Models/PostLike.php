<?php
namespace app\Models;

class PostLike{
    public $id;
    public $post_id;
    public $reactor_id;
    public $reacted_at;
    public $react_status;

    public function getPostLikeId()
    {
        return $this->id;
    }

    public function getReactStatus(){
        return $this->react_status;
    }
}