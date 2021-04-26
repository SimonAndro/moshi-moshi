<?php
    $notifSender = $notif->getNotifSender(); 
?>
<li id = "<?=$notifSender->getUserId()?>" class="notification-info row" >
    <a href="profile?who=<?=$notifSender->getUserId()?>">
        <div class="img-cropper">
            <img src="<?=!($notifSender->getProfilePicture())?loadAsset('media/imgs/user-avatar.png'):loadAsset($notifSender->getProfilePicture(),true)?>" class="avatar-small" alt="50x50">
        </div>
        <div class="msg-content" >
            <h5><?=$notifSender->getName()?> <small>@<?=$notifSender->getName()?> 
            - 
            <?php 
                $howOld = howOld($notif->getCreatedAt());
                echo $howOld['num'].$howOld['mag'] 
            ?> ago
            </small></h5>
            <p><?=$notif->getNotifMsg()?></p>
        </div>
    </a>
</li>
<hr style="padding=0; margin=0; height:1px; background-color:#ccc;">

