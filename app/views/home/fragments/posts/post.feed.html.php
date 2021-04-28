<li id="<?=$post->getPostId()?>">
<img src="<?=!($post->getCreator()->getProfilePicture())?loadAsset('media/imgs/user-avatar.png'):loadAsset($post->getCreator()->getProfilePicture(),true)?>" class="feed-avatar img-circle">
<div class="feed-post">
    <a href="profile?who=<?=$post->getCreator()->getUserId()?>"><h5><?=$post->getCreator()->getName()?> <small>@<?=$post->getCreator()->getName()?> 
    - 
    <?php 
        $howOld = howOld($post->getCreatedAt());
        echo $howOld['num'].$howOld['mag'] 
    ?> ago
    </small></h5></a>
    <p><?=$post->getMsg()?></p>
</div>

<div class="action-list">
    <a href="javascript:void(0);" >
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

    <a href="javascript:void(0);"  class="rep-show" data-postId="<?=$post->getPostId()?>">
        <i class="glyphicon glyphicon-menu-down"></i>
        Reply
        <span class="retweet-count replies">(<?php $replies = $post->getReplies();echo count($replies);?>)</span>
    </a>
    
    <a href="javascript:void(0);" >
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
<div class="reply-tab">
    <hr style="padding:0px; margin:10px 0 10px 0;">
    <div class="post-reply">    
        <form action="post_reaction" method="post" class="common-form" >
            <input type="text" name="val[reply]" class="form-control reply-input"
                placeholder="Type a reply...">
            <button type=submit>
                <i class="glyphicon glyphicon-send"></i>
            </button>
            <input type="hidden" name="val[caller]" value="post_reaction" />
            <input type="hidden" name="val[target]" value="post_reply" />
            <input type="hidden" name="val[post]" value="<?=$post->getPostId()?>"/>
        </form>
    </div>
    <div class="rep-list">
        <?=loadTemplate("home/fragments/posts/post.reply.html.php",["replies"=>$replies]);?>
    </div>
</div>

</li>