<li data-id='<?=$post->getPostId()?>'>
    <img src="<?=!($post->getCreator()->getProfilePicture())?loadAsset('media/imgs/user-avatar.png'):loadAsset($post->getCreator()->getProfilePicture(),true)?>"
        class="feed-avatar img-circle">
    <div class="feed-post">
        <h5>
            <?=$post->getCreator()->getName()?> <small>@
                <?=$post->getCreator()->getName()?> -
                <?=$post->howOld()['num']?>
                <?=$post->howOld()['mag']?>
            </small>
        </h5>
        <p>
            <?=$post->getMsg()?>
        </p>
    </div>
    <div class="action-list">
        <a href="post-reply" data-toggle="tooltip" data-placement="bottom" title="Reply">
            <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
        </a>
        <a href="post-share" data-toggle="tooltip" data-placement="bottom" title="Repost">
            <span class="glyphicon glyphicon-refresh" aria-hidden="true"></span>
            <span class="retweet-count">6</span>
        </a>
        <a href="post-star" data-toggle="tooltip" data-placement="bottom" title="Start">
            <span class="glyphicon glyphicon-star" aria-hidden="true"></span>
        </a>
    </div>
</li>