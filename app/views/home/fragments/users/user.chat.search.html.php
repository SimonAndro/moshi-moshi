<li id = <?=$peer->getUserId()?> class="search-result-user row">
    <div class="img-cropper">
        <img src="<?=!($peer->getProfilePicture())?loadAsset('media/imgs/user-avatar.png'):loadAsset($peer->getProfilePicture(),true)?>" class="avatar-small" alt="50x50">
    </div>
    <div class="msg-content">
    <h5><?=$peer->getName()?> <small>@<?=$peer->getName()?></small></h5>

    <p class="<?=($peer->getLastMessage($myId))?"":"start-conv"?>"><?=($peer->getLastMessage($myId))?$peer->getLastMessage($myId)[0]->getMsg():"Start conversation"?></p>
 
    </div>
</li>
<hr style="padding=0; margin=0; height:1px; background-color:#ccc;">