<?php
namespace app\Models;

class PostShare{
    public $id;
    public $post_id;
    public $reactor_id;
    public $reacted_at;
    public $react_status;

    public function getPostShareId()
    {
        return $this->id;
    }

    public function getPostId(){
        return $this->post_id;
    }

    public function getReactStatus(){
        return $this->react_status;
    }
}