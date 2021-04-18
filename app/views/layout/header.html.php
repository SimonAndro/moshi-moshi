<?php if($user && !$reg):?>
<nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="home">
                    <img src="<?=loadAsset('media/imgs/logo.png')?>" class="img-responsive">
                </a>

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#nav-menu"
                    aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- modal launch button -->
                <button id="tweet" class="btn btn-default pull-right visible-xs-block" data-toggle="modal"
                    data-target="#tweet-modal">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    Post
                </button>
            </div>

            <div id="nav-menu" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="home">
                            <span class="glyphicon glyphicon-home" aria-hidden="true"></span>
                            Home
                        </a>
                    </li>
                    <li id="notify-btn">
                        <a href="javascript:void(0);" role="button" >
                            <span class="badge"><?= $user->getNewNotificationCount()==0?"":$user->getNewNotificationCount()?></span>
                            <span class="glyphicon glyphicon-bell" aria-hidden="true"></span>
                            Notifications
                        </a>
                    </li>
                    <li id="msg-flag-btn">
                        <a href="javascript:void(0);" role="button">
                            <span class="badge"><?= $user->getNewNotificationCount()==0?"":$user->getNewNotificationCount()?></span>
                            <span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>
                            Messages
                        </a>
                    </li>
                    <li class="visible-xs-inline">
                        <a href="profile?who=<?=$user->getUserId()?>">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            Profile
                        </a>
                    </li>
                    <li class="visible-xs-inline">
                        <a href="logout">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                            Logout
                        </a>
                    </li>
                </ul>

                <button id="tweet" class="btn btn-default pull-right hidden-xs" data-toggle="modal"
                    data-target="#tweet-modal">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    Post
                </button>

                <!-- Options button dropdown -->
                <div id="nav-options" class="btn-group pull-right hidden-xs">
                    <button type="button" class="btn btn-default dropdown-toggle thumbnail" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <img src="<?=!($user->getProfilePicture())?loadAsset('media/imgs/user-avatar.png'):loadAsset($user->getProfilePicture(),true)?>">
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="profile?who=<?=$user->getUserId()?>">Profile</a></li>
                        <li><a href="settings.html">Setting</a></li>
                        <li role="separator" class="divider"></li>
                        <li><a href="logout">Logout</a></li>
                    </ul>
                </div>

                <form id="search" role="search" class="col-xs-12 col-sm-3 col-md-3" onsubmit="return startSearchMain();">
                    <div class="input-group col-xs-12">
                        <input type="text" id="main-seach-home-input" class="form-control" placeholder="Search...">
                        <span id="main-seach-home-icon" class="glyphicon glyphicon-search" aria-hidden="true"></span>
                    </div>
                    <div class="chat-search-result card list-wrapper col-xs-12" style="top: 40px; right: 0px; background-color:#fff;  z-index: 999; display:none;">
                        <ul class="px-5  list-unstyled" >
                           
                        </ul>
                    </div>
                </form>
                
            </div>
          
        </div>
    </nav> 
    
<?php endif?>  