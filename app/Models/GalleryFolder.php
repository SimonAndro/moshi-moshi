<?php
namespace app\Models;

class GalleryFolder {
    public $id;
    public $folder_name;
    public $owner_id;
    public $created_at;
    public $filesTable;

    public function __construct(\Ninja\DatabaseTable $filesTable) {
		$this->filesTable = $filesTable;
	}

    public function getFolderId()
    {
        return $this->id;
    }
    public function getFolderName()
    {
        return $this->folder_name;
    }
    public function getOwnerId()
    {
        return $this->owner_id;
    }
    public function creationDate()
    {
        return $this->created_at;
    }

    public function getMediaCount(){

        $cond1 = [
            "column" => "folder_id",
            "match" => "=",
            "value" => $this->id
        ];

        $cond2 = [
            "joint" => "AND",
            "column" => "user_id",
            "match" => "=",
            "value" => $this->owner_id
        ];

        return count($this->filesTable->find([$cond1,$cond2]));
    }

    public function getLastMediaCover(){
        $cond1 = [
            "column" => "folder_id",
            "match" => "=",
            "value" => $this->id
        ];

        $cond2 = [
            "joint" => "AND",
            "column" => "user_id",
            "match" => "=",
            "value" => $this->owner_id
        ];

        if($file = $this->filesTable->find([$cond1,$cond2],"id DESC",1)[0])
        {
            if($file->getFileType() == "video")
            {
               return $file->getThumbnail()->getThumbnailName();
            }

            return $file->getFileName();
        }else{
            return false;
        }
    }

}