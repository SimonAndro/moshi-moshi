<li class="thumbnail-piece">
    <div class="card-header folder-title">
        <?=mb_strimwidth($folder->getFolderName(), 0, 7, " ~")."(".$folder->getMediaCount().")"?>
    </div>
    <form  action="gallery" method="get" class="common-form">
        <a href="javascript:void(0);" class="thumbnail">
        <button type="submit" style="padding:0; margin:0;">
            <img class="img-responsive"
            src="<?=$folder->getMediaCount()>0?loadAsset($folder->getLastMediaCover(),true):loadAsset('media/imgs/default-cover.png');?>">
        
        </button>   
        </a>
        <input type="hidden" name="val[folder]" value="<?=$folder->getFolderId()?>"/>
        <input type="hidden" name="val[caller]" value="load_gallery"/>
        <input type="hidden" name="val[user]" value="<?=$user->getUserId()?>"/>
    </form>
</li>