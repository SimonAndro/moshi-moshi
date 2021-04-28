<ul>
    <?php foreach($replies as $reply):?>
    <li>
        <img src="<?=!($reply->getCreator()->getProfilePicture())?loadAsset('media/imgs/user-avatar.png'):loadAsset($reply->getCreator()->getProfilePicture(),true)?>" class="feed-avatar img-circle">
        <div class="feed-post">
            <a href="profile?who=<?=$reply->getCreator()->getUserId()?>"><h5><?=$reply->getCreator()->getName()?> <small>@<?=$reply->getCreator()->getName()?> 
            - 
            <?php 
                $howOld = howOld($reply->getCreatedAt());
                echo $howOld['num'].$howOld['mag'] 
            ?> ago
            </small></h5></a>
            <p><?=$reply->getMsg()?></p>
        </div>
    </li>
    <?php endforeach ?>
</ul>