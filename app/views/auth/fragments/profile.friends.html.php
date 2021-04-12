<ul id="feed" class="list-unstyled">
    <?php 
        foreach($friendsgts as $sgt)
        {
            echo $sgt;
        }
    ?>

    <?php if(count($friendsgts)<1):?>
        <div style="padding:20px; text-align:center;"> 
            Currently there are no friend suggestions for you.
        </div>
    <?php endif?>
</ul>