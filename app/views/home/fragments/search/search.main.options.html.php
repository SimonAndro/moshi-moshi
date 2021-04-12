<div id="profile" class="col-md-3 hidden-sm hidden-xs" data-spy="affix" data-offset-top="0">
    <div id="profile-resume " class="card select-search-main">
        <div class="card-header">
            Search Options
        </div>
        <div class="card-block" style="padding: 0%;">
            <ul class="list-inline list-unstyled text-center  search-options" style="margin: 0%;">
                <li id="all" class="search-option active">
                    <a href="javascript:void(0)">
                        <span class="profile-stats">All</span>
                        <span class="profile-value"><?=isset($all)?$all:(count($searchedUsers)+count($searchedPosts))?></span>
                    </a>
                </li>
                <hr class="search-option-break">
                <li id="users" class="search-option">
                    <a href="javascript:void(0)">
                        <span class="profile-stats">Users</span>
                        <span class="profile-value"><?=isset($users)?$users:count($searchedUsers)?></span>
                    </a>
                </li>
                <hr class="search-option-break">
                <li id="posts" class="search-option">
                    <a href="javascript:void(0)">
                        <span class="profile-stats">Posts</span>
                        <span class="profile-value"><?=isset($posts)?$posts:count($searchedPosts)?></span>
                    </a>
                </li>
                <hr class="search-option-break">
                <li id="news" class="search-option">
                    <a href="javascript:void(0)">
                        <span class="profile-stats">News</span>
                        <span class="profile-value">0</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>