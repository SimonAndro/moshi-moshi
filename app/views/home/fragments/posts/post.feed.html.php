<li id="<?=$post->getPostId()?>">
<img src="<?=!($post->getCreator()->getProfilePicture())?loadAsset('media/imgs/user-avatar.png'):loadAsset($post->getCreator()->getProfilePicture(),true)?>" class="feed-avatar img-circle">
<div class="feed-post">
    <a href="profile?who=<?=$post->getCreator()->getUserId()?>"><h5><?=$post->getCreator()->getName()?> <small>@<?=$post->getCreator()->getName()?> - <?=$post->howOld()['num']?><?=$post->howOld()['mag']?></small></h5></a>
    <p><?=$post->getMsg()?></p>
</div>
<div class="action-list">
    
    <!-- <a href="post-reply" data-toggle="tooltip" data-placement="bottom" title="Reply">
    <span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span>
    </a> -->
    
    <a href="javascript:void(0);"  style="width:40%;">
    <form action="post_reaction" method="post" class="common-form" >
    <button type="submit" style="padding:0; margin:0; border-style:none; background-color:transparent; outline: inherit;" name="repost">
    <span style="color:<?=$post->getUserShareStatus($user)?'#2F92CA':'#ccc'?>" class="glyphicon glyphicon-refresh" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Repost" ></span>
    <span class="retweet-count reposts"><?=$post->getShareCount()?></span>
    <input type="hidden" name="val[target]" value="post_share" />
    </button>
    <input type="hidden" name="val[caller]" value="post_reaction" />
    <input type="hidden" name="val[post]" value="<?=$post->getPostId()?>"/>
    </form>
    </a>
    
    
    <a href="javascript:void(0);"  style="width:40%;">
    <form action="post_reaction" method="post" class="common-form">
    <button type="submit" style="padding:0; margin:0; border-style:none; background-color:transparent;outline: inherit;" name="like">
    <span style="color:<?=$post->getUserLikeStatus($user)?'#2F92CA':'#ccc'?>" class="glyphicon glyphicon-star" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Star"></span>
    <span class="retweet-count likes"><?=$post->getLikeCount()?></span>
    <input type="hidden" name="val[target]" value="post_star" />
    </button>
    <input type="hidden" name="val[caller]" value="post_reaction" />
    <input type="hidden" name="val[post]" value="<?=$post->getPostId()?>"/>
    </form>
    </a>
    
</div>
</li>