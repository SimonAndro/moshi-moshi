<?php
namespace app\Models;

class File {
    const PROFILE_PIC = 1;
	const POST_PIC    = 2;
	const POST_VID    = 4;
	const GALLERY_PIC = 5;
	const GALLERY_VID = 6;
    

	public $id;
	public $user_id;
	public $file_type;
	public $file_name;
	public $folder_id;
	public $role;
	public $thumbnailsTable;

    public function __construct(\Ninja\DatabaseTable $thumbnailsTable) {
		$this->thumbnailsTable = $thumbnailsTable;
	}

	public function getFileName()
	{
		return $this->file_name;
	}

	public function getFileId(){
		return $this->id;
	}

	public function getFileType(){
		return $this->file_type;
	}

	public function getThumbnail()
	{
		$cond1=[
			"column"=>"media_id",
			"match"=>"=",
			"value"=>$this->id
		];
		return $this->thumbnailsTable->find([$cond1])[0];
	}

}