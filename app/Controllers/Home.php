<?php
namespace app\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;
use \Ninja\Uploader;

class Home {
	private $usersTable;
	private $relationshipsTable;
	private $chatsTable;
	private $postsTable;
	private $notificationsTable;
	private $authentication;
	private $galleryFoldersTable;
	private $filesTable;
	private $thumbnailsTable;
	private $postLikesTable;
	private $postSharesTable;

	private $mySelf;

	public function __construct(Authentication $authentication, DatabaseTable $usersTable, DatabaseTable $relationshipsTable, DatabaseTable $chatsTable,DatabaseTable $postsTable, DatabaseTable $notificationsTable, DatabaseTable $galleryFoldersTable,DatabaseTable $filesTable,DatabaseTable $thumbnailsTable,DatabaseTable $postLikesTable,DatabaseTable $postSharesTable) {
		$this->authentication = $authentication;
		$this->usersTable = $usersTable;
		$this->relationshipsTable = $relationshipsTable;
		$this->chatsTable = $chatsTable;
		$this->postsTable = $postsTable;
		$this->notificationsTable = $notificationsTable;
		$this->galleryFoldersTable = $galleryFoldersTable;
		$this->filesTable = $filesTable;  
		$this->thumbnailsTable = $thumbnailsTable;
		$this->postLikesTable = $postLikesTable;
		$this->postSharesTable = $postSharesTable;
	}

	/***
	 * default home page on get request
	 */
	public function home() {
		$user = $this->authentication->getUser();
		if(!$user)	
		{
			header('Location: login');
		}else{
			$this->mySelf = $user;
		}

		$action = $_GET['action'];
		if($action == 'feed-page')
		{
			$currPage = $_GET['currPage'];
			$feedData = $this->recommendPosts($user,$currPage);
			return[
				'msg'=>'success',
				'feedData'=>$feedData ,
				'wrapper'=>false
			];
		}else if($action == 'get-notifs')
		{
			$currPage = $_GET['currPage'];
			$notifData  = $this->getNotifications($user,$currPage);
			return[
				'msg' => 'success',
				'notifData' => $notifData,
				'wrapper' => false
			];
		}else if( $action == 'get-unreadMsg')
		{
			return $this->getRecentChats($user);
		}

		//handling search queries
		if($q = $_GET['q']) // reload search
		{
			$searchData = $this->searchMain($user,$q,"all");
		}

		$feedData = $this->recommendPosts($user);	
		$notifData  = $this->getNotifications($user,1);
		$stats  = $this->getStatistics($user);
		$myGallery = $this->loadMyGallery($user);
		return 
			[
				'template' => 'home/index.html.php',
				 'variables' => 
				 [
				 	'user'=>$user,
				 	'feedData'=>$feedData,
					'notifData' => $notifData,
					'searchData'=>$searchData,
					'stats' => $stats,
					'myGallery'=>$myGallery
				 ]
			];
	}

	public function postReaction()
	{
		//dump_to_file("form received");
		//dump_to_file($_POST);

		$user = $this->authentication->getUser();
		if(!$user)	
		{
			header('Location: login');
		}else{
			$this->mySelf = $user;
		}

		$target = $shareCount = $likeCount = $statusColor = null;
		$msg = "fail";
		if($val = $_POST['val'] and $postId = $val['post'] and $post = $this->postsTable->findById($postId))
		{
			if(isset($val['target']))
			{
				$target = $val['target'];
				
				$postCreator = $post->getCreator();

				if($target === "post_share")
				{
					$cond1=[
						'column'=>"post_id",
						'match'=>"=",
						'value'=>$post->getPostId()
					];
	
					$cond2=[
						'joint'=>"AND",
						'column'=>"reactor_id",
						'match'=>"=",
						'value'=>$user->getUserId()
					];
	
					if($postShareCheck = $this->postSharesTable->find([$cond1, $cond2])[0])
					{
						$postShareStatus["id"] = $postShareCheck->getPostShareId();
						$postShareStatus["react_status"] = !$postShareCheck->getReactStatus();
					}else{
						$postShareStatus["react_status"] = 1;
						$postShareStatus["reacted_at"] = time();
						$postShareStatus["post_id"] = $post->getPostId();
						$postShareStatus["reactor_id"] = $user->getUserId();
						
						$notif["notify_message_data"] = "post repost";
						// "your post: ".mb_strimwidth($post->getMsg(), 0, 30, " ...").
						// "was reposted by ".mb_strimwidth($user->getName(), 0, 20, " ...").
						// " ~".date("Y-m-d H:i:s");
						$notif["notify_type"] = \app\Models\Notification::REPOSTED;
						//$this->triggerNotification($notif,$user,$postCreator);
					}
	
					if($this->postSharesTable->save($postShareStatus))
					{
						
						$shareCount = $post->getShareCount();
						$statusColor = $post->getUserShareStatus($user)?"#2F92CA":"#ccc";
						$postId = $post->getPostId();
						$msg = "success";
					}
				}elseif($target == "post_star")
				{
					$cond1=[
						'column'=>"post_id",
						'match'=>"=",
						'value'=>$post->getPostId()
					];
	
					$cond2=[
						'joint'=>"AND",
						'column'=>"reactor_id",
						'match'=>"=",
						'value'=>$user->getUserId()
					];
	
					if($postLikeCheck = $this->postLikesTable->find([$cond1, $cond2])[0])
					{
						$postLikeStatus["id"] = $postLikeCheck->getPostLikeId();
						$postLikeStatus["react_status"] = !$postLikeCheck->getReactStatus();
					}else{
						$postLikeStatus["react_status"] = 1;
						$postLikeStatus["reacted_at"] = time();
						$postLikeStatus["post_id"] = $post->getPostId();
						$postLikeStatus["reactor_id"] = $user->getUserId();

						$notif["notify_message_data"] = "Post star";
						// "your post: ".mb_strimwidth($post->getMsg(), 0, 30, " ...").
						// "got a star from ".mb_strimwidth($user->getName(), 0, 20, " ...").
						// " ~".date("Y-m-d H:i:s");
						$notif["notify_type"] = \app\Models\Notification::LIKED;
						//$this->triggerNotification($notif,$user,$postCreator);
					}
	
					if($this->postLikesTable->save($postLikeStatus))
					{
						
						$likeCount = $post->getLikeCount();
						$statusColor = $post->getUserLikeStatus($user)?"#2F92CA":"#ccc";
						$postId = $post->getPostId();
						$msg = "success";
					}
				}
				
			}
			
		}

		return[
			'msg'=>$msg,
			'shareCount'=>$shareCount,
			'likeCount'=>$likeCount,
			'statusColor'=>$statusColor,
			'post'=>$postId,
			'target'=>$target,
			'caller'=>$val['caller'],
			'wrapper'=>false
		];
	}

	public function gallery(){

		$user = $this->authentication->getUser();
		if(!$user)	
		{
			header('Location: login');
		}else{
			$this->mySelf = $user;
		}

		$errors = [];
		if($val = $_POST['val'])
		{
			if(isset($val["caller"]) and $val["caller"] === "gallery_media_upload")
			{
				//dump_to_file($_POST);
				//dump_to_file($_FILES);

				if($files = inputFile('file'))
				{
					$userId = $this->mySelf->getUserId();
					$folderId = $val['folder'];

					//check if folder exists for this user
					$cond1 =[
						"column"=>"owner_id",
						"match"=>"=",
						"value"=>$userId
					];
					$cond2=[
						"joint"=>"AND",
						"column"=>"id",
						"match"=>"=",
						"value"=>$folderId
					];
					
					if($galleryFolder = $this->galleryFoldersTable->find([$cond1,$cond2])[0])
					{
						foreach($files as $file)
						{
							$upload = new Uploader($file, (Uploader::isImage($file)) ? 'image' : 'video'); 
							(Uploader::isImage($file)) ? $upload->setPath("/files/images/".$userId .'/'.time()."/") : $upload->setPath("/files/videos/".$userId .'/'.time()."/");
							if($upload->passed()) {
								if (Uploader::isImage($file)) {
									$result = $upload->uploadFile()->result();
									$fileSave['role'] = \app\Models\File::GALLERY_PIC;
									$fileSave['file_type'] = 'image';
								} else {
									$result = $upload->uploadFile()->result();
									$fileSave['role'] = \app\Models\File::GALLERY_VID;
									$fileSave['file_type'] = 'video';
								}
								$fileSave['user_id'] = $userId;
								$fileSave['file_name'] = $result;
								$fileSave['folder_id'] = $galleryFolder->getFolderId();
								$savedFile = $this->filesTable->save($fileSave);
			
								$id = $savedFile->getFileId();
								$uploadedFile = $this->filesTable->findById($id);
								if($uploadedFile->getFileType() == "video")
								{
									//dump_to_file("creating thumbnail");
									$thumbnailName = createThumbnail(loadAsset($uploadedFile->getFileName(),true),$upload->getDestination());
									$thumbnail['media_id'] = $uploadedFile->getFileId();
									//$thumbnail['thumbnail_name'] = $upload->getDestinationFolder().$thumbnailName;
									$thumbnail['thumbnail_name'] = $thumbnailName;
									$thumbnail['media_type'] = strtolower($file['type']);
									$savedThumbail = $this->thumbnailsTable->save($thumbnail);
								}
								$uploadedFiles[] = $uploadedFile;

							} else {
								$errors[] = "file upload failed";
								$errors[] = $upload->getError();
								break;
							}
						}
					}else{
						$errors[] = "gallery folder doesn't exist";
					}
					
				}else{
					$errors[] = "no file to upload";
				}

				if(empty($errors))
				{
					$newMedia = loadTemplate("gallery/gallery.media.html.php",["mediaFiles"=>$uploadedFiles]);
					return[
						'html'=> $newMedia,
						'msg'=>'success',
						'caller'=>$val['caller'],
						'wrapper'=>false
					];
				}
			
			}
			elseif(isset($val["folder"]) and $val["caller"] === "gallery_folder")
			{
				if($folderName = $val['name'] and $folderName !== "")
				{
					// create new folder in this users gallery
					$galleryFolder['folder_name'] = htmlentities($folderName);					
					$galleryFolder['owner_id'] = $this->mySelf->getUserId();

					$cond1 = [
						'column' => 'folder_name',
						'match' => '=',
						'value'=>$galleryFolder['folder_name'],
					];

					$cond2 = [
						'joint' => 'AND',
						'column' =>'owner_id',
						'match' => '=',
						'value'=>$galleryFolder['owner_id'],
					];

					if(count($this->galleryFoldersTable->find([$cond1, $cond2]))>0){
						$errors[] = "folder with this name exists";
					}else{
						$galleryFolder['created_at'] = time();
						$createdFolder = $this->galleryFoldersTable->save($galleryFolder);
						if($createdFolder->getFolderId())
						{
							$html = loadTemplate("home/fragments/gallery/gallery.thumbnail.html.php",["folder" => $createdFolder,'user'=>$user]);
							return[
								'html'=> $html,
								'msg'=>'success',
								'caller'=>$val['caller'],
								'wrapper'=>false
							];
						}else{
							$errors[] = "folder creation failed";
						}
					}
				}else{
					$errors[] = "folder name can't be empty";
				}
				
			}
		}

		return[
			'msg'=>'fail',
			'errors'=>$errors,
			'caller'=>$_POST['val']['caller'],
			'wrapper'=>false
		];
	}

	public function loadGalleryMedia(){
		//dump_to_file($_GET);
		$user = $this->authentication->getUser();
		if(!$user)	
		{
			header('Location: login');
		}else{
			$this->mySelf = $user;
		}

		$errors = [];
		$folderName = "sample folder";
		if($val = $_GET['val'])
		{
			$folderId = $val['folder'];

			$folderOwnerId = $this->mySelf->getUserId();
			if(isset($val['user']))
			{
				$folderOwnerId = $val['user'];
			}

			//check if folder exists for this user
			$cond1 =[
				"column"=>"owner_id",
				"match"=>"=",
				"value"=>$folderOwnerId 
			];
			$cond2=[
				"joint"=>"AND",
				"column"=>"id",
				"match"=>"=",
				"value"=>$folderId
			];
			
			if($galleryFolder = $this->galleryFoldersTable->find([$cond1,$cond2])[0])
			{
				$cond1 =[
					"column"=>"folder_id",
					"match"=>"=",
					"value"=>$galleryFolder->getFolderId()
				];
				$cond2 =[
					"joint"=>"AND",
					"column"=>"user_id",
					"match"=>"=",
					"value"=>$folderOwnerId
				];

				$files = $this->filesTable->find([$cond1, $cond2],"id DESC");
				$folderMedia = loadTemplate("gallery/gallery.media.html.php",["mediaFiles"=>$files]);
				$folderName  = $galleryFolder->getFolderName();
			}else{
				$errors[] = "folder doesn't exist";
			}


		}else{
			$errors[] = "unknown request";
		}
		

		if(empty($errors))
		{
			return[
				"caller"=>"load_gallery",
				"folderName"=>$folderName,
				"folderId"=>$folderId,
				"folderMedia"=>$folderMedia,
				"errors"=>$errors,
				'wrapper' => false, 
			];
		}
		return[
			"caller"=>"load_gallery",
			"folderName"=>"sample name",
			"folderId"=>$folderId,
			'wrapper' => false, 
		];

	}
	private function loadMyGallery($user, $currentPage=1){

		$page = 10;
		$limit = $page;
		$offset = ($currentPage-1)*$page;

		$myGalleryFolders = array();
	
		$cond1 = [
			'column' => 'owner_id',
			'match' => '=',
			'value'=> $user->getUserId()
		];

		$galleryFolders = $this->galleryFoldersTable->find([$cond1],"id DESC",$offset);
		foreach($galleryFolders as $galleryFolder){
			$row = 	$html = loadTemplate("home/fragments/gallery/gallery.thumbnail.html.php",["folder" => $galleryFolder,'user'=>$user]);
			array_push($myGalleryFolders,$row);
		}
		return $myGalleryFolders;
	}

	public function profile($currentPage = 1){
        $who = $_GET['who'];

        $user = $this->authentication->getUser();
		if(!$user)	
		{
			header('Location: login');
            
		}else{
			$this->mySelf = $user;
		}

        if(!isset($who) or empty($profileOwner = $this->usersTable->findById($who)))
        {
            return
            [
                loadTemplate("error/404.html"),
                'wrapper' => false, 
                'noAjax' => true
            ];
        }
		$feedData = null; $galleryExplorer = null;

		$feeds = Array();
		$page = 10;
		$limit = $page;
		$offset = ($currentPage-1)*$page;
	

		if(isset( $_GET['gal']) and $reqId = $_GET['req'])
		{
			if(($reqId === $profileOwner->getUserId()) or $profileOwner->isFriendsWith($reqId))
			{

				$myGalleryFolders = $this->loadMyGallery($profileOwner);
				$galleryExplorer = loadTemplate("gallery/gallery.explorer.html.php", 
						[
							"myGalleryFolders"=>$myGalleryFolders,
							"profileOwner"=>$profileOwner,
							"user"=>$user
						]);	
				
			}else{
				$galleryExplorer = loadTemplate("gallery/gallery.explorer.html.php", 
						[
							"myGalleryFolders"=>[],
							"galleryError"=>true,
							"profileOwner"=>$profileOwner,
							"user"=>$user
						]);	
			}
		}else{

			$cond1 = [
				'column'=>'creator_id ',
				'match'=>'=',
				'value'=>$profileOwner->getUserId()
			];
			$ownerPosts = $this->postsTable->find([$cond1]);
	
			$cond1 = [
				'column'=>'reactor_id',
				'match'=>'=',
				'value'=>$profileOwner->getUserId()
			];
			$cond2 = [
				'joint'=>"AND",
				'column'=>'react_status',
				'match'=>'=',
				'value'=>1
			];
			$resharePosts = $this->postSharesTable->find([$cond1, $cond2]);
	
			$postCount = count($resharePosts) + count($ownerPosts) ;

			$resharePostIds = [];
			foreach($resharePosts as $repost)
			{
				$resharePostIds[] = $repost->getPostId();
			}


		
			$conds[] = [
				'column'=>'creator_id ',
				'match'=>'=',
				'value'=>$profileOwner->getUserId()
			];

			

			if(!empty($resharePostIds))
			{
				$conds[] = [
					'joint'=>"OR",
					'column'=>'id ',
					'match'=>'IN',
					'value'=>$resharePostIds
				];
			}

			$posts = $this->postsTable->find($conds,"created_at DESC",$limit,$offset);

			foreach($posts as $post)
			{
				$row = loadTemplate('home/fragments/posts/post.feed.html.php',['post'=>$post, 'user'=>$user]);
				array_push($feeds,$row);
			}
			
			if (empty($feeds)) {
				if($profileOwner->getUserId()== $user->getUserId())$feeds[] = '<p id="feed-no-post" style="padding-top:10px;" class="text-center">your posts will appear here when you post or reshare a post </p>';
				else $feeds[] = '<p id="feed-no-post" style="padding-top:10px;" class="text-center">User has no posts yet </p>';
			} 

			$feedData = [ 
					'feeds'=>$feeds,
					'pages'=>ceil($postCount/$page)
				];
		}
		
		$notifData  = $this->getNotifications($user,1);
		$stats  = $this->getStatistics($profileOwner,true);

        return 
        [
            'template' => 'home/index.html.php',
            'variables' => 
            [
                'user'=>$user,
				'profileOwner'=>$profileOwner,
                'feedData'=>$feedData,
                'notifData' => $notifData,
				'stats' => $stats,
				'galleryExplorer'=>$galleryExplorer
            ]
        ];
    }
	

	/**
	 * Get user statistics
	 */
	public function getStatistics($user, $isProfile = false)
	{
		// count posts
		$cond1 = [
			'column'=>'creator_id',
			'match'=>'=',
			'value'=>$user->getUserId()
		];
		$createdPosts = $this->postsTable->find([$cond1]);
		$posts = count($createdPosts);

		// count followers
		$cond1 = [
			'column'=>'followee_id',
			'match'=>'=',
			'value'=>$user->getUserId()
		];
		$cond2 = [
			'joint'=>"AND",
			'column'=>'unfollowed',
			'match'=>'=',
			'value'=> 0
		];
		$followers = count($this->relationshipsTable->find([$cond1,$cond2]));

		// count followees
		$cond1 = [
			'column'=>'follower_id',
			'match'=>'=',
			'value'=>$user->getUserId()
		];
		$cond2 = [
			'joint'=>"AND",
			'column'=>'unfollowed',
			'match'=>'=',
			'value'=> 0
		];
		$followees = count($this->relationshipsTable->find([$cond1,$cond2]));

		// count gallery files
		$cond1 = [
			'column'=>'user_id',
			'match'=>'=',
			'value'=>$user->getUserId()
		];
		$galleryFiles = count($this->filesTable->find([$cond1]));

		$likes = 0;
		$reposts = 0;
		if($isProfile)
		{
			// count gunnered likes and gunnered reposts
			foreach($createdPosts as $cp)
			{
				$likes += $cp->getLikeCount();
				$reposts += $cp->getShareCount();
			}
		}

		return[
			"posts" => countMagnitude($posts),
			"likes"=> countMagnitude($likes),
			"reposts"=> countMagnitude($reposts),
			"followers" => countMagnitude($followers),
			"followees" => countMagnitude($followees),
			"galleryFiles"=> countMagnitude($galleryFiles)
		];
	}


	/**
	 * endless scroll Notifications
	 */
	public function getNotifications($user,$currentPage=1)
	{
		
		$notifications = Array();
		$page = 10;
		$limit = $page;
		$offset = ($currentPage-1)*$page;
		$cond1 = [
			'column'=>'receiver_id',
			'match'=>'=',
			'value'=>$user->getUserId()
		];
		$cond2 = [
			'joint'=>'AND',
			'column'=>'is_seen',
			'match'=>'=',
			'value'=>0
		];
		
		$notifCount = count($this->notificationsTable->find([$cond1,$cond2]));
		$notifs = $this->notificationsTable->find([$cond1,$cond2],"created_at DESC",$limit,$offset);
		if(!empty($notifs))
		{
			foreach($notifs as $notif)
			{
				$row = loadTemplate('home/fragments/notifications/notify.custom.html.php',['notif'=>$notif]);
				array_push($notifications,$row);
				
			}

		}
		
		if (empty($notifications) && $currentPage == 1) {
			$notifications[] = '<p id="feed-no-post" style="padding-top:10px;" class="text-center">your Notifications will appear here</p>';
		} 

        return [ 
			'notifications'=>$notifications,
			'pages'=>ceil($notifCount/$page)
		];
	}


	/**
	 * recommends posts to users
	 */
	public function recommendPosts($user, $currentPage=1)
	{
		/**
		 * Chronological order
		 * ->reciprocated relationships
		 * ->order by most recent
		 */

		/**
		 * Get relationships
		*/
		$feeds = Array();
		$page = 5;
		$limit = $page;
		$offset = ($currentPage-1)*$page;
		$cond1 = [
			'column'=>'followee_id',
			'match'=>'=',
			'value'=>$user->getUserId()
		];
		$cond2 = [
			'joint'=>'OR',
			'column'=>'follower_id',
			'match'=>'=',
			'value'=>$user->getUserId()
		];
		$relationships = $this->relationshipsTable->find([$cond1,$cond2]);
		$followees = array();
	
		if(!empty($relationships) or !empty($user))
		{
			foreach($relationships as $relationship)
			{
				$followees[] = $relationship->getOtherId($user->getUserId());
			}
			$followees[] = $user->getUserId();

			$cond1 = [
				'column'=>'creator_id ',
				'match'=>'IN',
				'value'=>$followees
			];
			$postCount = count($this->postsTable->find([$cond1]));
			$posts = $this->postsTable->find([$cond1],"created_at DESC",$limit,$offset);
			foreach($posts as $post)
			{
				$row = loadTemplate('home/fragments/posts/post.feed.html.php',['post'=>$post,'user'=>$user]);
				array_push($feeds,$row);
			}

		}
		
		if (empty($feeds)) {
			$feeds[] = '<p id="feed-no-post" style="padding-top:10px;" class="text-center">your feeds will appear here when you post or the people you follow post </p>';
		} 

        return [ 
			'feeds'=>$feeds,
			'pages'=>ceil($postCount/$page)
		];
	}

	/***
	 * handles post requests
	 */
	public function homePost()					
	{
		$user = $this->authentication->getUser();
		if(!$user)								/**Is this user logged in */
		{
			header('Location: login');
		}else{
			$this->mySelf = $user;
		}

		$action = $_POST['action'];
		if($action == 'search-user')            /**Search for users in my chat list */
		{
			return $this->searchUser($user);

		}elseif($action == 'chat-recent')		/**load recent chats */
		{
			return $this->getRecentChats($user);
		}
		elseif($action == 'get-chat')			/**load chat messages */
		{
			return $this->loadChat($user);
		}elseif($action == 'new-msg')			/**Save a new message */	
		{
			return $this->newMessage($user);
		}elseif($action == 'check-msg') 		/**Check whether there is a new message */
		{
			return $this->checkMessage($user);
		}elseif($action == 'post') 				/**Creating a new post */
		{
			return $this->newPost($user);
		}elseif($action == 'search-main')
		{
			return $this->searchMain($user);
		}elseif($action == 'follow-user')
		{
			return $this->following($user);
		}
	}

	/**
	 * Following other users
	 */
	private function following($user){
		
		$errors = array();
		$targetId = $_POST['targetId'];
		$goal =  $_POST['goal']; 
		
		if($target = $this->usersTable->findById($targetId))
		{

			$cond1 = [
				'open_brackets'=> 1,
				'column'=>'follower_id',
				'match'=>'=',
				'value'=>$user->getUserId()
			];
			$cond2 = [
				'joint'=>'AND',
				'close_brackets'=>1,
				'column'=>'followee_id',
				'match'=>'=',
				'value'=>$target->getUserId()
			];
			$cond3 = [
				'joint'=>'OR',
				'open_brackets'=> 1,
				'column'=>'follower_id',
				'match'=>'=',
				'value'=>$target->getUserId()
			];
			$cond4 = [
				'joint'=>'AND',
				'close_brackets'=>1,
				'column'=>'followee_id',
				'match'=>'=',
				'value'=>$user->getUserId()
			];
			
			if($relationship_check = $this->relationshipsTable->find([$cond1,$cond2,$cond3,$cond4])[0])
			{
				$relationship['id'] = $relationship_check->getRelationShipId();
				$followerUser = $this->usersTable->findById($relationship_check->getFollowerId());
				$followeeUser = $relationship_check->getPeer($followerUser->getUserId());
			}

			$sendNotification = false;
			if($goal === "follow" ){
				if(isset($relationship_check) and ($followeeUser->getUserId() === $target->getUserId()))
				{
					$relationship['unfollowed'] = 0;
				}else{ // creation of a relationship
					$relationship['created_at'] = time();
					$relationship['follower_id'] = $user->getUserId();
					$relationship['followee_id'] = $target->getUserId();
					$relationship['followed_back'] = 0;
					$relationship['unfollowed'] = 0;

					$notif["notify_type"] = \app\Models\Notification::FOLLOWED;
					$notif["notify_message_data"] = "Followed you";
					$sendNotification = true;

				}
				
				$nextGoal = "unfollow";
				$goalHtml = '<span class="glyphicon glyphicon-minus" aria-hidden="true"></span> Unfollow';
			
			}
			elseif($goal === "unfollow")
			{
				if(isset($relationship_check) and ($followeeUser->getUserId() === $target->getUserId()))
				{
					$relationship['unfollowed'] = 1;

					$nextGoal = "follow";
					$goalHtml = '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Follow';
				
				}else{
					$errors[] = "action not allowed";
				}
		
			}
			elseif($goal === "recip")
			{
				if(isset($relationship_check) and ($followeeUser->getUserId() === $user->getUserId()))
				{
					$relationship['followed_back'] = 1;

					$notif["notify_type"] = \app\Models\Notification::RECIPED;
					$notif["notify_message_data"] = "reciprocated your follow";
					$sendNotification = true;

					$nextGoal = "unrecip";
					$goalHtml = '<span class="glyphicon glyphicon-resize-full" aria-hidden="true"></span> Unrecip';
				}else{
					$errors[] = "action not allowed";
				}
			}
			elseif($goal === "unrecip" and ($followeeUser->getUserId() === $user->getUserId()))
			{
				if(isset($relationship_check))
				{
					$relationship['followed_back'] = 0;

					$nextGoal = "recip";
					$goalHtml = '<span class="glyphicon glyphicon-resize-small" aria-hidden="true"></span> Recip';
				}else{
					$errors[] = "action not allowed";
				}
				
			}
			else{
				$errors[] = "unknown action";
				
				$nextGoal = $goal;
			}

			if($this->relationshipsTable->save($relationship))
			{

				if($sendNotification)
				{
					$this->triggerNotification($notif,$user,$target);
				}

				return[
					'msg'=>'success',
					'goal'=> $nextGoal,
					'goalHtml'=>$goalHtml,
					'wrapper'=>false
				];
			}else{
				$errors[] = "save fail";
			}
			

		}else{
			$errors[] = "user not found";
		}
		
		
		return[
			'msg'=>'fail',
			'errors'=>$errors,
			'wrapper'=>false
		];
	
	}

	private function triggerNotification($notif, $sender, $target)
	{
		// create notification
		$notif["sender_id"] = $sender->getUserId();
		$notif["receiver_id"] = $target->getUserId();
		$cond1 =[
			'column'=>'sender_id',
			'match'=>'=',
			'value'=>$notif["sender_id"]
		];
		$cond2 = [
			'joint'=>'AND',
			'column'=>'receiver_id',
			'match'=>'=',
			'value'=>$notif["receiver_id"]
		];
		$cond3 = [
			'joint'=>'AND',
			'column'=>'notify_type',
			'match'=>'=',
			'value'=>$notif["notify_type"] 
		];
		if(empty($this->notificationsTable->find([$cond1,$cond2, $cond3])))
		{
			$notif["is_seen"] = 0;
			$notif["created_at"] = time();
			$this->notificationsTable->save($notif);
		}
	}
	// private function following($user){
		
	// 	$errors = array();
	// 	$followeeId = $_POST['followeeId'];
	// 	$targetId = $_POST['followeeId'];
	// 	$goal =  $_POST['goal']; //goal can be 1,2,3
		
	// 	if($followee = $this->usersTable->findById($followeeId) )
	// 	{
		
	// 		$cond1 = [
	// 			'column'=>'follower_id',
	// 			'match'=>'=',
	// 			'value'=>$user->getUserId()
	// 		];
	// 		$cond2 = [
	// 			'joint'=>'AND',
	// 			'column'=>'followee_id',
	// 			'match'=>'=',
	// 			'value'=>$followee->getUserId()
	// 		];
			
	// 		if($relationship_check = $this->relationshipsTable->find([$cond1,$cond2])[0])
	// 		{
	// 			$relationship['id'] = $relationship_check->getRelationShipId();
	// 		}

	// 		if($goal == 1)
	// 		{
	// 			$relationship['unfollowed'] = 0;
	// 		}else{
	// 			$relationship['unfollowed'] = 1;
	// 		}

	// 		$sendNotification = false;
	// 		if(!isset($relationship['id']))
	// 		{
	// 			$relationship['created_at'] = time();
	// 			$relationship['follower_id'] = $user->getUserId();
	// 			$relationship['followee_id'] = $followee->getUserId();
	// 			$relationship['followed_back'] = 0;

	// 			$sendNotification = true;
	// 		}
			
	// 		if($this->relationshipsTable->save($relationship))
	// 		{

	// 			if($sendNotification)
	// 			{
	// 				// create notification
	// 				$notif["sender_id"] = $user->getUserId();
	// 				$notif["receiver_id"] = $followee->getUserId();
	// 				$notif["notify_type"] = \app\Models\Notification::FOLLOWED;
	// 				$cond1 =[
	// 					'column'=>'sender_id',
	// 					'match'=>'=',
	// 					'value'=>$notif["sender_id"]
	// 				];
	// 				$cond2 = [
	// 					'joint'=>'AND',
	// 					'column'=>'receiver_id',
	// 					'match'=>'=',
	// 					'value'=>$notif["receiver_id"]
	// 				];
	// 				$cond3 = [
	// 					'joint'=>'AND',
	// 					'column'=>'notify_type',
	// 					'match'=>'=',
	// 					'value'=>$notif["notify_type"] 
	// 				];
	// 				if(empty($this->notificationsTable->find([$cond1,$cond2, $cond3])))
	// 				{
	// 					$notif["notify_message_data"] = "Followed you";
	// 					$notif["is_seen"] = 0;
	// 					$notif["created_at"] = time();
	// 					$this->notificationsTable->save($notif);
	// 				}
	// 			}

	// 			return[
	// 				'msg'=>'success',
	// 				'goal'=> $relationship['unfollowed'],
	// 				'wrapper'=>false
	// 			];
	// 		}else{
	// 			$errors[] = "save fail";
	// 		}
			

	// 	}else{
	// 		$errors[] = "user not found";
	// 	}
		
		
	// 	return[
	// 		'msg'=>'fail',
	// 		'errors'=>$errors,
	// 		'wrapper'=>false
	// 	];
	
	// }
	/**
	 * Searching globally from the home page
	 */
	private function searchMain($user,$q="",$filter="")
	{
		//dump_to_file($_POST);
		$me = $user;

		if(empty($q))
		{
			$q = $_POST['query'];
		}
		
		$q = htmlentities(trim($q));


		$result = Array();

		$searchedUsers  = [];
		$searchedPosts  = [];
		if( $filter !== "" or ($filter = $_POST['filter']))
		{
			$filter = htmlentities(trim($filter));
			if($filter === 'all') // all selection
			{ 
				$searchedUsers = $this->searchUsersMain($q);
				$searchedPosts = $this->searchPostsMain($q);
			}
			elseif($filter === 'users') // users
			{
				$searchedUsers = $this->searchUsersMain($q);
			}
			elseif($filter === 'posts') // posts
			{
				$searchedPosts = $this->searchPostsMain($q);
			}
		}
	
		$searchOptions = loadTemplate("home/fragments/search/search.main.options.html.php",
			[
				'searchedUsers' => $searchedUsers,
				'searchedPosts' => $searchedPosts,	
			]
		);

		$searchResult = loadTemplate("home/fragments/search/search.main.content.html.php",
			[
				'searchedUsers' => $searchedUsers,
				'searchedPosts' => $searchedPosts,
				'limitUsers' => $limitUsers,
				'offsetUsers' => $offsetUsers,
				'limitPosts' => $limitPosts,
				'offsetPosts' => $offsetPosts,
				'query' => $q,
				'me'=> $me,
				'filter'=> $filter
			]
		);

		return[
			'msg'=>'success',
			'searchOptions' => $searchOptions,
			'searchResult' => $searchResult,
			'filter' => $filter,
			'query'=> $q,
			'wrapper'=> false
		];
	}

	private function searchPostsMain($q)
	{
		/**
		 * Search Posts, priority is given to:
		*1. those of this user,
		*2. those of users followered by this user,
		*3. those of followees of users followed this user
		*4. those of followees of *3 ...
		*/
		$cond1 =[
			'column'=>'message_data',
			'match'=>' LIKE ',
			'value'=>'%'.$q.'%'
		];
		$limitPosts = 10;
		$offsetPosts = 0;
		$searchedPosts = $this->postsTable->find([$cond1],'id DESC',$limitPosts,$offsetPosts);
		return 	$searchedPosts;
	}
	private function searchUsersMain($q)
	{
		/**
		 * Search users, priority is given to:
		 * 1. those I follow
		 * 2. those who follow me
		 * 3. those followed by who I follow 
		 * 4. those followed by *3 ...
		 */
		$cond1 = [
			'column'=>'name',
			'match'=>' LIKE ',
			'value'=>'%'.$q.'%'
		];
		$limitUsers = 10;
		$offsetUsers = 0;
		$searchedUsers = $this->usersTable->find([$cond1],'id DESC',$limitUsers,$offsetUsers);
		return $searchedUsers;
	}

	/**
	 * Save new post for this user
	 */
	public function newPost($user)
	{
		$msg = htmlentities($_POST['msg']);
	
		$post['message_data'] = $msg;
		$post['creator_id'] = $user->getUserId();
		$post['created_at'] = time();
		
		$errors = [];
		if((strlen($msg) >140) )
		{
			$errors[] = "Post too long";
		}else if (strlen(trim($msg)) == 0)
		{
			$errors[] = "Post can't be empty";			
		}

		if(empty($errors))
		{
			if($postId = $this->postsTable->save($post)->getPostId())
			{
				$post = $this->postsTable->findById($postId);
				$row = loadTemplate('home/fragments/posts/post.feed.html.php',['post'=>$post,'user'=>$user]);
				return[
					'msg'=>'success',
					'post'=>$row ,
					'wrapper'=>false
				];

			}else{
				$errors[] = "Post creation failed";
			}
		}
		return[
			'msg'=>'fail',
			'errors'=>$errors,
			'wrapper'=>false
		];
	}

	/**
	 * Checks for new messages in the database for this 
	 * user
	 */
	public function checkMessage($user)
	{
		$peerId = $_POST['userId'];
		$resMsg = false;
		if(!empty($peerId) and $peerId != "")
		{
			if(!$user->isFriendsWith($peerId)) // want to get messages of people you are not friends with
			{
				$this->authentication->logout();
				die();
			}

			$cond0 = [
				'column'=>'seen',
				'match'=>'=',
				'value'=>0
			];

			$cond1 = [
				'joint'=>'AND',
				'open_brackets'=> 1,
				'column'=>'sender_id',
				'match'=>'=',
				'value'=>$peerId
			];
	
			$cond2 = [
				'joint'=>'AND',
				'close_brackets'=>1,
				'column'=>'receiver_id',
				'match'=>'=',
				'value'=>$user->getUserId()
			];
	
			$chatMsgs = $this->chatsTable->find([$cond0,$cond1,$cond2],"id DESC",$limit,$offset);
			$chatMsgs = array_reverse($chatMsgs); 
			$chat = Array();
			foreach($chatMsgs as $chatMsg)
			{
				$msg['id'] = $chatMsg->getChatMsgId();
				$msg['seen'] = 1;

				$this->chatsTable->save($msg);

				$peer = $this->usersTable->findById($peerId);
				$row = loadTemplate('home/fragments/chat.message.html.php',['chatMsg'=>$chatMsg,'me'=>$user,'peer'=>$peer]);
				array_push($chat,$row);
			}
	
			if (empty($chat)) {
				$resMsg = false;
			} else {
				$resMsg = true;
				$response = $chat;
			}

			
		}
		/**
		 * notifications and other messages
		 * */

		//get most recent of unread message count
		$sql = "
		SELECT 
			COUNT(message_data)
				FROM chat 
				WHERE receiver_id=:receiver_id AND seen=0
		";

		$parameters[':receiver_id'] = $user->getUserId();
		if($result = $this->chatsTable->customQuery($sql,$parameters)[0])
		{
			$unreadc = $result->getMessageCount();
		}else{
			$unreadc = 0;
			$unreadHtmlC[] =  '<p id="feed-no-post" style="padding-top:10px;" class="text-center">you have no unread messages</p>';
		}

		return[
			'msg' => $resMsg,
			'unreadc' => $unreadc,
			'peerId'=> $peerId,
			'html'=>$response,
			'unreadn' => $unreadn,
			'wrapper' => false
		];
		
	}

	/**
	 * Save new message
	 */
	public function newMessage($user)
	{
		$peerId = $_POST['userId'];
		$msg = htmlentities($_POST['msg']);
		if(!$user->isFriendsWith($peerId)) // want to send messages to people you are not friends with
		{
			$this->authentication->logout();
			die();
		}

	

		$chatMsg['created_at'] = time();
		$chatMsg['sender_id'] = $user->getUserId();
		$chatMsg['receiver_id'] = $peerId;
		$chatMsg['message_data'] = $msg;
		
		if(!empty($this->chatsTable->save($chatMsg)->getChatMsgId()))
		{
			return[
				'msg'=>'success',
				'wrapper'=>false
			];

		}else{
			return[
				'msg'=>'fail',
				'wrapper'=>false
			];
		}
	}

	/**
	 * Load this users chat
	 */
	public function loadChat($user)
	{
		$offset = $_POST['offset'];
		$peerId = $_POST['userId'];
		$limit = 10;
		
		if(!$user->isFriendsWith($peerId)) // want to access messages for other users
		{
			$this->authentication->logout();
			die();
		}

		$cond1 = [
			'open_brackets'=> 1,
			'column'=>'sender_id',
			'match'=>'=',
			'value'=>$user->getUserId()
		];

		$cond2 = [
			'joint'=>'AND',
			'close_brackets'=>1,
			'column'=>'receiver_id',
			'match'=>'=',
			'value'=>$peerId
		];

		$cond3 = [
			'joint'=>'OR',
			'open_brackets'=> 1,
			'column'=>'sender_id',
			'match'=>'=',
			'value'=>$peerId
		];

		$cond4 = [
			'joint'=>'AND',
			'close_brackets'=>1,
			'column'=>'receiver_id',
			'match'=>'=',
			'value'=>$user->getUserId()
		];

		$chatMsgs = $this->chatsTable->find([$cond1,$cond2,$cond3,$cond4],"id DESC",$limit,$offset);
		$chatMsgs = array_reverse($chatMsgs); 
		$chat = Array();
		foreach($chatMsgs as $chatMsg)
		{
			$peer = $this->usersTable->findById($peerId);
			$row = loadTemplate('home/fragments/chat.message.html.php',['chatMsg'=>$chatMsg,'me'=>$user,'peer'=>$peer]);
			array_push($chat,$row);

			$msg['id'] = $chatMsg->getChatMsgId();
			$msg['seen'] = 1;
			$this->chatsTable->save($msg);
		}

		if (empty($chat) && $offset ==0) {
			$response[] = '<p id ="first-message" style="padding-top:10px; margin-top:50px;" class="text-center">You already reciped this user, Please say hello</p>';
		} else {
			$response = $chat;
		}
		$peer = $this->usersTable->findById($peerId);
		return[
			'peerName'=>$peer->getName(),
			'peerId'=>$peer->getUserId(),
			'html'=>$response,
			'offset'=>$offset+$limit,
			'wrapper'=>false
		];
	}
	/**
	 * Return recent chat
	 */
	private function getRecentChats($user)
	{
		$relationships = $this->getAllMyRelationShips(1,0);
		$peers = array();
		$count = 0;
		foreach($relationships as $relationship)
		{
			$peer = $relationship->getPeer($user->getUserId());;
			if($chat = $peer->getLastMessage($peer->getUserId())[0])
			{
				$createdAt =  $chat->getCreationDate();
				$peers[$createdAt] = $peer;
			}else{
				$peers[$count] = $peer;
				$count++;
			}
			
		}
		krsort($peers);
		$response = loadTemplate("home/fragments/chat.recent.html.php",['peers' =>$peers,"user"=>$user]);
		
		return[
			'msg'=>"success",
			'html'=>$response,
			'wrapper'=>false
		];
	}

	/**
	 * Search users on my chat list
	 */
	public function searchUser ($user)
	{
		$q = $_POST['query'];
		$q = htmlentities(trim($q));

		if(strlen($q)>2){
			$result = Array();

			$relationships = $this->getAllMyRelationShips(1,0);
			if(!empty($relationships))
			{
				foreach($relationships as $relationship)
				{
					
					$peer = $relationship->getPeer($user->getUserId());
					if(strpos($peer->getName(), $q) !== false )
					{
						$row = loadTemplate('home/fragments/users/user.chat.search.html.php',['peer'=>$peer,'myId'=>$user->getUserId()]);
						array_push($result,$row);
					}

				}
			}



			if (empty($result)) {
				$response[] = '<p style="padding-top:10px;" class="text-center">no result found</p>';
			} else {
				$response = $result;
			}
		}else{
			$response[] = '<p style="padding-top:10px;" class="text-center">query too short</p>';
		}
		
		return[
			'html'=>$response,
			'wrapper'=>false
		];
	}

	private function getAllMyRelationShips($followedBack = 0, $unfollowed=0)
	{


		$cond1 = [
			'column'=>'followed_back',
			'match'=>'=',
			'value'=>$followedBack
		];
		$cond2 = [
			'joint'=>'AND',
			'open_brackets'=> 1,
			'column'=>'follower_id',
			'match'=>'=',
			'value'=>$this->mySelf->getUserId()
		];
		$cond3 = [
			'joint'=>'OR',
			'close_brackets'=>1,
			'column'=>'followee_id',
			'match'=>'=',
			'value'=>$this->mySelf->getUserId()
		];
		$cond4 = [
			'joint'=>'AND',
			'column'=>'unfollowed',
			'match'=>'=',
			'value'=>$unfollowed
		];

		$relationships = $this->relationshipsTable->find([$cond1,$cond2,$cond3]);
		return $relationships;
	}
	
}