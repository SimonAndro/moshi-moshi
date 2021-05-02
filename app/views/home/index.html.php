<div class="container">
    <div id="major-row-home" class="row">

        <?php if(isset($searchData)):?>

        <?=$searchData['searchOptions']; ?>
        <?=$searchData['searchResult']; ?>
        <?php else: ?>

        <?php 
            if(isset($profileOwner))
            {
                $storedUser = $user;
                $user = $profileOwner;
            }           
        ?>
        <?php if(isset($profileOwner)):?>
        <div id="profile" class="col-md-3 col-sm-12 col-xm-12">
            <?php else :?>
            <div id="profile" class="col-md-3 hidden-sm hidden-xs" data-spy="affix" data-offset-top="0">
                <?php endif?>
                <div id="profile-resume" class="card">
                    <a href="#"><img class="card-img-top img-responsive img-prof-cover"
                            src="<?=loadAsset('media/imgs/default-cover.png');?>"></a>
                    <div class="card-block">
                        <img src="<?=!($user->getProfilePicture())?loadAsset('media/imgs/user-avatar.png'):loadAsset($user->getProfilePicture(),true)?>"
                            class="card-img" style="background:#F5F8FA;">
                        <h4 id="my-name" class="card-title ">
                            <a href="profile?who=<?=$user->getUserId()?>">
                            <?=$user->getName()?> <small>@
                                <?=$user->getName()?>
                            </small>
                            </a>
                        </h4>
                        <p class="card-text text-about">
                            <?=$user->getAbout()==""?"No about information No about information No about information No about information No about information No about information No about informationNo about informationNo about informationNo about informationNo about informationNo about informationNo about informationNo about informationNo about informationNo about informationNo about informationNo about informationNo about information ":$user->getAbout()?>
                        </p>
                        <ul data-targetId=<?=$user->getUserId()?> class="list-inline list-unstyled user-stats-all">
                            <li class="card-tweets">
                                <a href="profile?who=<?=$user->getUserId()?>">
                                    <span class="profile-stats">Posts</span>
                                    <span class="profile-value">
                                        <?=$stats['posts']['num'].$stats['posts']['mag']?>
                                    </span>
                                </a>
                            </li>
                            <li class="card-following">
                                <a href="javascript:void(0);">
                                    <span class="profile-stats">Following</span>
                                    <span class="profile-value">
                                        <?=$stats['followees']['num'].$stats['followees']['mag']?>
                                    </span>
                                </a>
                            </li>
                            <li class="card-followers">
                                <a href="javascript:void(0);">
                                    <span class="profile-stats">Followers</span>
                                    <span class="profile-value">
                                        <?=$stats['followers']['num'].$stats['followers']['mag']?>
                                    </span>
                                </a>
                            </li>
                            <?php if(isset($profileOwner)): ?>
                            <li class="goto-gallery">
                                <a href="profile?who=<?=$profileOwner->getUserId()?>&gal=1&req=<?=$storedUser->getUserId()?>" >
                                    <div>
                                    <span class="profile-stats">Gallery</span>
                                    <span class="profile-value">
                                        <?=$stats['galleryFiles']['num'].$stats['galleryFiles']['mag']?>
                                    </span>
                                    </div>
                                </a>
                            </li>
                            <?php endif ?>
                        </ul>
                    </div>
                </div>
                <?php 
             if(isset($storedUser))
             {
                $user = $storedUser;
             }           
             ?>

                <?php if(!isset($profileOwner)): ?>
                <div id="profile-photo" class="card">
                    <div class="card-header">
                        Gallery
                        <a class="gallery-new-folder" data-toggle="modal" data-target="#newFolder-modal">
                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            New Folder
                        </a>
                    </div>

                    <div class="card-block gallery-preview">
                        <?php if(count($myGallery)==0):?>
                        <p class="no-gallery-folder"> your gallery folders will be listed here</p>
                        <?php endif?>
                        <ul class="list-inline list-unstyled">
                            <?php 
                            foreach($myGallery as $folder){
                                echo $folder;
                            }
                       ?>
                        </ul>
                    </div>
                </div>
                <?php elseif($profileOwner->getUserId() !== $user->getUserId()):?>

                <div id="profile-photo" class="card" style="margin-top:5px;margin-bottom:10px;">
                    <div class="card-block profile-actions">
                        
                <div id="profile-photo" class="card" style="margin-top:5px;margin-bottom:10px;">
                    <div class="card-block profile-actions" id="<?=$profileOwner->getUserId()?>"
                            data-goal="<?=$profileOwner->getRelationChanger($user->getUserId())?>">
                            <a href="javascript:void(0);" role="button" class="btn btn-default  btn-follow-search item-actions">
                                <?php if($profileOwner->getRelationChanger($user->getUserId()) === "unfollow"):?>
                                <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                                Unfollow
                                <?php elseif($profileOwner->getRelationChanger($user->getUserId()) === "unrecip"):?>
                                <span class="glyphicon glyphicon-resize-full" aria-hidden="true"></span>
                                Unrecip 
                                <?php elseif($profileOwner->getRelationChanger($user->getUserId()) === "follow"):?>
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                Follow  
                                <?php elseif($profileOwner->getRelationChanger($user->getUserId()) === "recip"):?>
                                <span class="glyphicon glyphicon-resize-small" aria-hidden="true"></span>
                                Recip
                                <?php endif?>
                            </a>
                    </div>
                </div>

                        
                        
                    </div>
                </div>

                <?php endif ?>

            </div>

            <div id="main" class="col-sm-12 col-md-6">

                <!-- Draggable DIV -->
                <div id="vid-playerHolder" style="display:none;">
                    <!-- Include a header DIV with the same name as the draggable DIV, followed by "header" -->
                    <div id="mydivheader">
                        Video Player
                        <button type="button" class="close-video" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>

                    <div id='player'>
                        <video autoplay id='video-element' poster="">
                            <source src='' type=''>
                        </video>
                        <div id='controls'>
                            <progress id='progress-bar' min='0' max='100' value='0'>0% played</progress>
                            <a id='btnReplay' class='replay' title='replay' accesskey="R" onclick='replayVideo();'><span
                                    class="glyphicon glyphicon-repeat" aria-hidden="true"></a>
                            <a id='btnPlayPause' class='play' title='play' accesskey="P"
                                onclick='playPauseVideo();'><span class="glyphicon glyphicon-play"
                                    aria-hidden="true"></a>
                            <a id='btnStop' class='stop' title='stop' accesskey="X" onclick='stopVideo();'><span
                                    class="glyphicon glyphicon-stop" aria-hidden="true"></a>
                            <a><input type="range" id="volume-bar" title="volume" min="0" max="1" step="0.1" value="1">
                            <a id='btnMute' class='mute' title='mute' onclick='muteVolume();'> <span
                                    class="glyphicon glyphicon-volume-off" aria-hidden="true"></a>
                            <a id='btnFullScreen' class='fullscreen' title='toggle full screen' accesskey="T"
                                onclick='toggleFullScreen();'>[&nbsp;&nbsp;]</a>
                        </div>
                    </div>
                </div>

                <?php if(isset($galleryExplorer)): ?>
                <?=$galleryExplorer?>
                <?php else : ?>

                <div id="creating-post" class="alert alert-info hide" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h3>Creating Post...</h3>
                    <div class="progress">
                        <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar"
                            aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>

                <div id="main-card" class="card">

                    <form id="new-message">
                        <div class="input-group">
                            <input id="post-main-input" type="text" class="form-control"
                                placeholder="What is happening, <?=mb_strimwidth($user->getName(), 0, 20, " ...")?> ?"
                            readonly>
                            <span class="input-group-addon">
                                <!-- <span class="glyphicon glyphicon-camera" aria-hidden="true"></span> -->
                                <button id="btn-post-now-main" type="button" class=" btn-primary" data-toggle="modal"
                                    data-target="#tweet-modal">
                                    Create Post
                                </button>
                            </span>
                        </div>
                    </form>




                    <ul id="feed" class="list-unstyled">
                        <?php 
                            foreach($feedData['feeds'] as $feed){
                                echo $feed;
                            }
                        ?>
                    </ul>

                </div>

                <nav class="text-center feed-pagination">
                    <ul class="pagination pagination-lg" data-page-count="<?= $feedData['pages'] ?>">
                        <li class="feed-page-prev" style="display: none;"><a aria-label="Previous"><span
                                    aria-hidden="true">&laquo;</span></a></li>
                        <?php if($feedData['pages'] > 1): ?>
                        <li class="active feed-page"><a>1</a></li>
                        <?php if($feedData['pages'] > 5):?>
                        <li class="feed-page"><a>2</a></li>
                        <li class="continuity"><a>...</a></li>
                        <li class="feed-page"><a>
                                <?=($feedData['pages']-1)?>
                            </a></li>
                        <li class="feed-page"><a>
                                <?=$feedData['pages']?>
                            </a></li>
                        <?php else:?>
                        <?php
                for($p = 2; $p<$feedData['pages']+1; $p++)
                {
                  echo '<li class="feed-page" ><a>'.$p.'</a></li>';
                }
              ?>
                        <?php endif?>
                        <li class="feed-page-next"><a aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>
                        <?php else:?>
                        <li class="active"><a style="border-radius:10px;">1</a></li>
                        <?php endif ?>
                    </ul>
                </nav>
                <?php endif ?>
            </div>


            <?php endif?>

            <?php if(isset($profileOwner)): ?>
            <div id="right-content" class="col-md-3 col-sm-12 col-xs-12" style="margin-bottom:100px;">
                <?php if($profileOwner->getUserId() === $user->getUserId()):?>
                <ul class="list-group">
                    <li class="list-group-item list-group-item-info">
                        Your stats
                    </li>
                    <li class="list-group-item">
                        Your posts were reposted
                        <span class="label label-success"> <?=$stats['reposts']['num'].$stats['reposts']['mag']?> X</span>
                    </li>
                    <li class="list-group-item">
                        Your posts were liked
                        <span class="label label-danger"> <?=$stats['likes']['num'].$stats['likes']['mag']?> X</span>
                    </li>
                    <li class="list-group-item">
                        Influence
                        <span class="label label-default">100%</span>
                    </li>
                    <li class="list-group-item">
                        Always alert badge
                        <span class="badge glyphicon glyphicon-star" aria-hidden="true">
                        </span>
                    </li>
                </ul>
                <?php endif ?>
                <p class="card" style="text-align:center; height:50px; padding-top:10px; font-size:18px;">joined
                    <?php 
                        $howOld = howOld($profileOwner->getCreatedAt());
                       echo $howOld['num'].$howOld['mag'] 
                    ?> ago
                </p>

            </div>
            <?php else: ?>
            <div id="right-content" class="col-md-3 hidden-sm hidden-xs">
                <div id="who-follow" class="card">
                    <div class="card-header">
                        Did you Know?
                    </div>
                    <div class="card-block">
                        <p> Did you know 40 percent of human jobs could be replaced by AI in the future?</p>
                    </div>
                </div>

                <div id="app-info" class="card">
                    <div class="card-block">
                        ©
                        <?=date("Y")?> moshi moshi
                        <ul class="list-unstyled list-inline">
                            <li><a href="#">About</a></li>
                            <li><a href="#">Terms and Privacy</a></li>
                            <li><a href="#">Help</a></li>
                            <li><a href="#">Status</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                    <div class="card-footer">
                        <a href="#" style="text-decoration:none; text-align:center; color:#ccc">Make it Happen</a>
                    </div>
                </div>
            </div>
            <?php endif ?>


            <div class="chat-float-btn" data-toggle="modal" data-target="#chat-modal">
                <div id="chat-btn">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    Chat
                </div>
            </div>

            <div class="chat-popup col-xs-12">
                <div id="chat-bar">
                    <div id="chat-select" class="">
                        <div id="chat-header">
                            <button id="select-close" type="button" class="close" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title">Begin Chat</h4>
                            <hr>

                            <div id="search-chat">
                                <form id="search-user-form" role="search" class="">
                                    <div class="input-group col-md-12 col-xs-12">
                                        <input id="search-user" type="text" class="form-control"
                                            placeholder="Search User...">
                                        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                    </div>
                                </form>
                                <div class="chat-search-result card list-wrapper">
                                    <ul class="px-5  list-unstyled">
                                    </ul>
                                </div>
                            </div>

                        </div>
                        <!-- <div id="chat-recent">
                        <h5>Recent Chats</h2>
                            <div class="recent-chat-list">
                               loading...
                            </div>
                    </div> -->
                        <div id="chat-recent">
                            <h5 id="chat-recent-title">Recent Chats</h2>
                                <div class="recent-chat-list">

                                </div>
                        </div>
                    </div>
                    <div id="chatting" class="hide">
                        <div id="chat-header">
                            <button id="chat-close" type="button" class="close" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 id="chat-peer-title" class="modal-title" data-my-id="">Username</h4>
                            <span class="glyphicon glyphicon-earphone"></span> 
                            <hr>
                        </div>

                        <div id="search-chat">
                            <form id="search-chat-form" role="search" class="">
                                <div class="input-group col-md-12 col-xs-12">
                                    <input type="text" class="form-control" placeholder="Search chat...">
                                    <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
                                </div>
                            </form>
                        </div>

                        <div id="chats" class="px-2 scroll" data-offset="">

                        </div>

                        <div class="chat-send col-md-11 col-xs-11">
                            <nav class="navbar bg-white navbar-expand-sm d-flex justify-content-between">
                                <input id="chat-type" type="text number" name="text" class="form-control chat-type-c"
                                    placeholder="Type a message...">
                                <div id="send-icon"
                                    class="icondiv d-flex justify-content-end align-content-center ml-2">
                                    <i class="glyphicon glyphicon-send icon2"></i>
                                </div>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>

        </div>




        <!-- modals -->
        <div class="modal fade" id="tweet-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button id="close-modal-up" type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Create a Post</h4>
                    </div>
                    <div class="modal-body">
                        <div class="error-msg-list invisible">
                        </div>
                        <textarea class="form-control" rows="4"
                            placeholder="A number of people are waiting to hear from you..." maxlength="140"></textarea>
                    </div>
                    <div class="modal-footer">
                        <span class="char-count pull-left" data-max="140">140</span>
                        <button id="close-modal-down" type="button" class="btn btn-default" data-dismiss="modal">
                            Close
                        </button>
                        <button id="btn-post-now" type="button" class="btn btn-primary">
                            Post Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="following-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body">
                        <ul class="px-5  list-unstyled">
                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="notifications-modal" tabindex="-1" role="dialog"
            data-page-count="<?=$notifData['pages']?>">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Notifications</h4>
                    </div>
                    <div class="modal-body">
                        <ul class="px-5  list-unstyled">
                            <?php 
                            foreach( $notifData['notifications'] as $n)
                            {
                                echo $n;
                            }
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="newFolder-modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Create a Gallery folder</h4>
                    </div>
                    <form action="gallery" method="post" class="common-form">
                        <div class="error-gallery-folder invisible">
                        </div>
                        <input type="hidden" name="val[caller]" value="gallery_folder" />
                        <input type="hidden" name="val[folder]" value="1" />
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Folder name</label>
                                <input type="text" class="form-control" name="val[name]" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">
                                Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade " id="galleryFolderPreview" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">folder name</h4>
                    </div>
                    <form action="gallery" method="post" class="common-form galleryUploader"
                        enctype='multipart/form-data'>
                        <input type="hidden" name="val[caller]" value="gallery_media_upload" />
                        <input id="i-g-f-id" type="hidden" name="val[folder]" value="1" />
                        <div class="modal-body">
                            <div class="error-gallery-folder invisible">
                            </div>
                            <div id="gallery-upload-progress" class="progress" style="display:none;">
                                <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar"
                                    aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                </div>
                            </div>


                            <div id="gallery" class="gallery px-5">

                                
                            </div>
                        </div>
                        <?php if(isset($profileOwner) and ($user->getUserId() !== $profileOwner->getUserId())): ?>
                        
                        <?php else :?>
                        <div class="modal-footer">
                            <a class="btn btn-primary" style="overflow: hidden; position: relative;">
                                <input id="galleryInput" multiple type="file" name="file[]"
                                    onchange="validate_file_size(this, 'image-video','submit_file_upload')">
                                <span class="glyphicon glyphicon-cloud-upload"></span>
                                Upload
                            </a>
                        </div>
                        <?php endif ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
