<div id="main" class="col-sm-12 col-md-6">
    <div class="card search-aim-header">
        Showing search results for <span>
            <?=$query?>
        </span>
    </div>

    <ol class="breadcrumb card visible-xs-block visible-sm-block m-search-options">
        <li><a href="javascript:void(0)" class="m-search-option" id="news">News (0)
            </a></li>
        <li><a href="javascript:void(0)" class="m-search-option" id="posts">Posts (<span><?=isset($posts)?$posts:count($searchedPosts)?></span>)
            </a></li>
        <li><a href="javascript:void(0)" class="m-search-option" id="users">Users (<span><?=isset($users)?$users:count($searchedUsers)?></span>)
            </a></li>
        <li><a href="javascript:void(0)" class="m-search-option active" id="all">All (<span><?=isset($all)?$all:(count($searchedUsers)+count($searchedPosts))?></span>)
            </a></li>
    </ol>


    <div id="main-card" class="search-results-all-main"
        style="margin-top: 0; margin-bottom: 20px; padding-bottom: 20px;">
        <?php if(count($searchedUsers)>0):?>
        <div id="result-group-users" class="card result-group">
            <div class="search-header">
                <p>Users</p>
            </div>
            <ul id="feed" class="list-unstyled">
                <?php
                        foreach($searchedUsers as $user)
                        {
                            echo loadTemplate("home/fragments/users/user.main.search.html.php",
                            [
                                'user'=>$user,
                                'me'=>$me
                            ]);
                        }
                ?>
            </ul>
            <hr style="padding: 0 0 20px 0; margin: 0;">
            <?php if($limitUsers < count($searchedUsers)):?>
            <div id="1" class="see-more-search-main" data-current-count="<?=count($searchedUsers)?>">
                <a>See more</a>
            </div>
            <?php else: ?>
            <div class="results-end-main">
                <h6><span>end of search results</span></h6>
            </div>
            <?php endif ?>
        </div>
        <?php endif ?>

        <?php if(count($searchedPosts)>0):?>
        <div id="result-group-posts" class="card result-group" style="margin-top: 10px;">
            <div class="search-header">
                <hr class="search-result-break">
                <p>Posts</p>
            </div>
            <ul id="feed" class="list-unstyled">
                <?php
                    foreach($searchedPosts as $post)
                    {
                    echo loadTemplate('home/fragments/posts/post.feed.html.php',['post'=>$post,'user'=>$me]);
                    }        
                ?>
            </ul>
            <hr style="padding: 0 0 20px 0; margin: 0;">
            <?php if($limitPosts < count($searchedPosts)):?>
            <div id="2" class="see-more-search-main" data-current-count="<?=count($searchedPosts)?>">
                <a>See more</a>
            </div>
            <?php else: ?>
            <div class="results-end-main">
                <h6><span>end of search results</span></h6>
            </div>
            <?php endif ?>
        </div>
        <?php endif ?>
        <?php  if((count($searchedUsers) + count($searchedPosts))==0):?>
        <p style="padding:10px;" class="text-center card">no results found</p>;
        <?php endif?>
    </div>
</div>