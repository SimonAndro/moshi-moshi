
<?php foreach($mediaFiles as $mediaFile): ?>
    <?php if($mediaFile->getFileType() == "image"): ?>
        <div class="gallery-item" style="grid-row-end: span 1;">
            <div class="content">
                <img src="<?=loadAsset($mediaFile->getFileName(),true)?>" alt="">
            </div>
        </div>
    <?php elseif($mediaFile->getFileType() == "video"): ?>
        <div class="gallery-item" style="grid-row-end: span 1;">
        <div style="position: relative">
            <div class="content">
                <img src="<?=loadAsset($mediaFile->getThumbnail()->getThumbnailName(),true)?>" alt="">
            </div>
            <div class="play-video" data-vid-type="<?=$mediaFile->getThumbnail()->getMediaType()?>" data-vid-src="<?=loadAsset($mediaFile->getFileName(),true)?>" data-vid-ptr="<?=loadAsset($mediaFile->getThumbnail()->getThumbnailName(),true)?>"><span class="glyphicon glyphicon-play-circle"></div>
        </div>
        </div>
    <?php endif ?>
<?php endforeach ?>
