
  
<li class="">
    <div id="user-info">
        <img src="<?=!($user->getProfilePicture())?loadAsset('media/imgs/user-avatar.png'):loadAsset($user->getProfilePicture(),true)?>" class="feed-avatar img-circle avatar">
        <div class="feed-post">
            <h5>
                <?=$user->getName()?> <small>@<?=$user->getName()?></small>
                <input class="form-check-input form-group" type="checkbox"  id="make-friends"
                    value="<?=$user->getUserId()?>">
            </h5>
        </div>
    </div>
</li>


