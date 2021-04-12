<div id="gallery-main-exp" class="card">
    <ul class="list-unstyled">
    <?php
        foreach($myGalleryFolders as $folder)
        {
            echo $folder;
        }
        if(isset($galleryError))
        {
            echo '<p style="text-align:center;">you need to follow user to view their gallery</p>';
        } 
    ?>
    </ul>
</div>