<li id="<?=$user->getUserId()?>" class="search-result-user-main row"
    data-goal="<?=$user->getRelationChanger($me->getUserId())?>">
    <div class="img-cropper">
        <img src="<?=!($user->getProfilePicture())?loadAsset('media/imgs/user-avatar.png'):loadAsset($user->getProfilePicture(),true)?>"
            class="avatar-small" alt="50x50">
    </div>
    <div class="msg-content">
        <h5>
            <?=$user->getName()?> <small>@
                <?=$user->getName()?>
            </small>
        </h5>
        <p class="card-text text-about">
            <?=$user->getAbout()==""?"No about information":$user->getAbout()?>
        </p>
    </div>
    <?php if($me->getUserId() != $user->getUserId()):?>
    <a href="javascript:void(0);" role="button" tabindex="-1" class="btn btn-default  btn-follow-search">
        <?php if($user->getRelationChanger($me->getUserId()) === "unfollow"):?>
        <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
        Unfollow
        <?php elseif($user->getRelationChanger($me->getUserId()) === "unrecip"):?>
        <span class="glyphicon glyphicon-resize-full" aria-hidden="true"></span>
        Unrecip 
        <?php elseif($user->getRelationChanger($me->getUserId()) === "follow"):?>
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        Follow  
        <?php elseif($user->getRelationChanger($me->getUserId()) === "recip"):?>
        <span class="glyphicon glyphicon-resize-small" aria-hidden="true"></span>
        Recip
        <?php endif?>
    </a>
    <?php endif ?>
</li>

