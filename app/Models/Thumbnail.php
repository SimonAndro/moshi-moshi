<?php
namespace app\Models;

class Thumbnail {
    public $id;
    public $media_id;
    public $thumbnail_name;
    public $media_type;

    public function getThumbnailName()
    {
        return $this->thumbnail_name;
    }

    public function getMediaType()
    {
        return $this->media_type;
    }
}