
<div id="gallery-main-exp" class="card">
<?php if($profileOwner->getUserId() === $user->getUserId()):?>
<div class="card-header">
Gallery
<a class="gallery-new-folder" data-toggle="modal" data-target="#newFolder-modal">
    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    New Folder
</a>
</div>
<?php endif?>
    <ul class="list-unstyled">
    <?php
        foreach($myGalleryFolders as $folder)
        {
            echo $folder;
        }
        if(isset($galleryError))
        {
            echo '<p style="text-align:center;"> you need to follow user to view their gallery</p>';
        }
        elseif(empty($myGalleryFolders))
        {
            echo '<p style="text-align:center;">nothing to view in this gallery</p>';
        }
    ?>
    </ul>
</div>