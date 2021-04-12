<div class="container">
    <div class="row">
        <div id="register-done" class="card col-xs-offset-1 col-xs-10 col-md-offset-3 col-md-6">
           <div class="head-register-done">
             <div id="head"> You are almost done</div>
             <div id="title"> <?=$successtitle?></div>
             <div id="step">Step <?=$stepcount?> of 2</div> 
            </div>
            <hr>
            <div class="register-done-body">
                <form id="register-setup-form" class="form-horizontal" method="post" action="register-success"
                 <?php echo $stepcount==1?'enctype="multipart/form-data"':'';?>>
                    <?=$fragment?>
                    <input type="text" class="invisible" name="val[step]" value=<?=$stepcount?>>
                    <div class="row">
                        <?php if($stepcount!=2):?>
                        <span class="col-xs-6  col-md-6 text-left skip-button">
                            <button class="btn btn-info btn-lg" type="submit" >
                                Skip
                            </button>
                        </span>
                        <?php endif?>
                        <span class="<?=$stepcount!=2?"col-xs-6 col-md-6":"col-xs-12 col-md-12"?> text-right next-button">
                            <button class="btn btn-info btn-lg" type="submit" >
                                <?=$buttonTitle?>
                            </button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>