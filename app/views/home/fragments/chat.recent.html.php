<ul px-5  list-unstyled>
    <?php 
        foreach($peers as $p){
    
            echo loadTemplate("home/fragments/users/user.chat.search.html.php",[
                "peer"=>$p,
                "myId"=>$user->getUserId()
                ]
            );
                    
        } 
        if(count($peers) == 0)
        {
            echo '<p>You have no recent chats</p>';
        }
    ?>
 <ul>