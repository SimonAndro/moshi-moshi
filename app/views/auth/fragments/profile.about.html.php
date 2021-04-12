    <div class="form-group">
        <div class="col-sm-12">
            <label class=" control-label " for="profile-picture">Profile Picture</label>
            <img class="avatar" src="<?=loadAsset('media/imgs/user-avatar.png');?>"/>
            <input id="profile-picture" type="file" class="form-control-file" 
             name="file" onchange="previewProPic(this);">
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <label class=" control-label " for="profile-picture">About</label>
            <textarea class="form-control" id="about-text" rows="5" name="val[about]"></textarea>
        </div>
    </div>
