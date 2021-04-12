

$(document).ready(function() {

    $('[data-toggle="tooltip"]').tooltip();

    var popoverContentTemplate = '' +
        '<img src="imgs/breed.jpg" class="img-rounded">' +
        '<div class="info">' +
            '<strong>Dog Breeds</strong>' +
            '<a href="#" class="btn btn-default">' +
                '<span class="glyphicon glyphicon-plus" aria-hidden="true"></span>' +
                'Follow' +
            '</a>' +
        '</div>';

    $('[data-toggle="popover"]').popover({
        placement: 'bottom',
        html: true,
        content: function() {
            return popoverContentTemplate;
        }
    });

    $('[data-toggle="popover"]').on('show.bs.popover', function() {
        var $icon = $(this).find('span.glyphicon');

        $icon.removeClass('glyphicon-plus').addClass('glyphicon-ok');
        // $(this).append('ing');
    });

    // $('#profile').affix();

    $('#profile').on('affix.bs.affix', function() {
        $(this).width($(this).width() - 1);
        $('#main').addClass('col-md-offset-3');
    }).on('affix-top.bs.affix', function() {
        $(this).css('width', '');
        $('#main').removeClass('col-md-offset-3');
    });

    $('#login-form').on('submit', function(e){
        e.preventDefault();

        $('.error-msg-list-login').html('');
        $('.error-msg-list-login').addClass('invisible');
        var errors =[];
        var hasError = false;

        var $form = $(e.currentTarget),
            $email = $form.find('#login-email'),
            $password = $form.find('#login-password'),
            $button = $form.find('button[type=submit]');

        if(($email.val().length==0)||$password.val().length==0)
        {
            return;
        }

        if($email.val().indexOf('@') == -1) {  
            vaca = $email.closest('form-group')
            $email.closest('.form-group').addClass('has-error');  
            hasError = true;     
        }else if($password.val().length<8){
            vaca = $password.closest('form-group')
            $password.closest('.form-group').addClass('has-error');  
            hasError = true;  
        } 

        
        if(!hasError){            
            $form.find('.form-group').addClass('has-success'). removeClass('has-error');            
  
            $('.error-msg-list-login').removeClass('invisible'); 
            $('.error-msg-list-login').html(' ');
            $('.error-msg-list-login').append(
                '<div class="alert alert-info text-center"><strong>Verifying...</strong></div>'
            );  
            
            //submit form by ajax and wait for response
            var url = $form.attr('action');
            $.ajax({
                type: "POST",
                url: url, 
                data: $form.serialize(),
                success:function(result){
                    console.log(result.msg);
                    if(result['msg']=='success')
                    {
                        $('.error-msg-list').html(' ');
                        $('.error-msg-list').append(
                            '<div class="alert alert-success text-center"><strong>Success!</strong></div>'
                        );
                        var action = result['action'];
                        if(action=='url')
                        {
                            window.location.replace(result['value']);
                            
                        }
                    }else{
                        $('.error-msg-list-login').html('');
                        var error = result.errors;
                        error.forEach(function(v,i){
                            $('.error-msg-list-login').append(
                                '<div class="alert alert-warning "><strong>'+v+'</strong></div>'
                            );
                        });
                        $('.error-msg-list-login').removeClass('invisible');
                    }
            }});
        }else{
            $('.error-msg-list-login').html('');
            errors.push("Invalid login credentials");
            errors.forEach(function(v,i){
                $('.error-msg-list-login').append(
                    '<div class="alert alert-warning "><strong>'+v+'</strong></div>'
                );
            });
            $('.error-msg-list-login').removeClass('invisible');
        }  
        
    });

    $('#create-account-form').on('submit', function(e){
        e.preventDefault();

        $('.error-msg-list').html('');
        $('.error-msg-list').addClass('invisible');
        var errors =[];

        var $form = $(e.currentTarget),
            $email = $form.find('#create-email'),
            $password = $form.find('#create-password'),
            $passwordRepeat = $form.find('#confirm-create-password'),
            $sexFemale = $form.find('#sex-female'),
            $sexMale = $form.find('#sex-male'),
            $sexCust = $form.find('#sex-custom'),
            $button = $form.find('button[type=submit]');

            var hasError = false;

        if($email.val().indexOf('@') == -1) {  
            $email.closest('.form-group').addClass('has-error'); 
            hasError = true;    
            errors.push('invalid email address');   
        }else{
            $email.closest('.form-group').addClass('has-success'). removeClass('has-error');
        }

        if($password.val().length<8 ){
            $password.closest('.form-group').addClass('has-error');   
            hasError = true; 
            errors.push('password should be atleast 8 characters long');
        }else{
            $password.closest('.form-group').addClass('has-success'). removeClass('has-error');
            if($password.val() != $passwordRepeat.val()){
                $passwordRepeat.closest('.form-group').addClass('has-error'); 
                hasError = true; 
                errors.push('passwords don\'t match');
            }else{
                $password.closest('.form-group').addClass('has-success'). removeClass('has-error');
                $passwordRepeat.closest('.form-group').addClass('has-success'). removeClass('has-error');
            }
        }

       

        if(!$sexFemale.is(':checked') && !$sexMale.is(':checked') && !$sexCust.is(':checked'))
        {
            $sexFemale.closest('.form-group').closest('.form-group').addClass('has-error'); 
            hasError = true; 
            errors.push('The gender field not checked');
        }else{
            $sexFemale.closest('.form-group').addClass('has-success'). removeClass('has-error');
        }

        if(!hasError){            
            $form.find('.form-group').addClass('has-success'). removeClass('has-error');            
  
            $('.error-msg-list').removeClass('invisible'); 
            $('.error-msg-list').html(' ');
            $('.error-msg-list').append(
                '<div class="alert alert-info text-center"><strong>Processing...</strong></div>'
            );  
            
            //submit form by ajax and wait for response
            var form = $(this);
            var url = form.attr('action');
            $.ajax({
                type: "POST",
                url: url, 
                data: form.serialize(),
                success:function(result){
                    console.log(result.msg);
                    if(result['msg']=='success')
                    {
                        $('.error-msg-list').html(' ');
                        $('.error-msg-list').append(
                            '<div class="alert alert-success text-center"><strong>Success!</strong></div>'
                        );
                        var action = result['action'];
                        if(action=='url')
                        {
                            window.location.replace(result['value']);
                            
                        }
                    }else{
                        $('.error-msg-list').html(' ');
                        var error = result.errors;
                        error.forEach(function(v,i){
                            $('.error-msg-list').append(
                                '<div class="alert alert-warning "><strong>'+v+'</strong></div>'
                            );
                        });
                        $('.error-msg-list').removeClass('invisible');
                    }
            }});
        }else{
            $('.error-msg-list').html('');
            errors.forEach(function(v,i){
                $('.error-msg-list').append(
                    '<div class="alert alert-warning "><strong>'+v+'</strong></div>'
                );
            });
            $('.error-msg-list').removeClass('invisible');
        }    
    });

    $('body').on('click','.btn-follow-search', function(e){
        e.preventDefault();

        var that = $(this);
        var id = that.parent().attr('id');
        var goal = that.parent().attr('data-goal');
    
        $.ajax({
            type: "POST",
            url: "home", 
            data: {
                action: "follow-user",
                targetId: id,
                goal:goal
            },
            success:function(result){
                console.log(result);
                if(result.msg=='success')
                {
                    that.html(result.goalHtml); 
                    that.parent().attr('data-goal',result.goal);
                }else{
                   
                }
        }});

    })
   
    //chatting
    $('#chat-btn, #msg-flag-btn').on('click',function(e){
        $('.collapse').collapse('hide');
        $('.chat-float-btn').hide();
        $('.chat-popup').show('fast');
        $('#chat-select').show();

        $.ajax({
            type: "POST",
            url: "home",
            data: {   
                action:"chat-recent" ,     
            },
            success: function(res) {
                console.log(res);
                if(res.msg == "success")
                {
                    $('#chat-recent .recent-chat-list').html(res.html);
                }else{
                    //
                }
               
            }
        });
    });

    $('#select-close, #chat-close').on('click',function(e){
        $('.chat-float-btn').show()
        $('.chat-popup').hide();
        $('#chatting').addClass('hide');
        $('#chat-peer-title').attr('data-my-id',""); // reset ID
        
    });

    function sendMsg()
    {
        var msg = $('#chat-type').val();

        if(!(msg.trim().length==0))
        {
            if($('#first-message').length) // remove first message
            {
                $('#first-message').remove();
            }

            var d = new Date();
            var n = d.getTime();
            var name = $('#my-name small').html();
            var id = $('#chat-peer-title').attr('data-my-id');


            var encodedStr = msg.replace(/[\u00A0-\u9999<>\&]/g, function(i) {
                return '&#'+i.charCodeAt(0)+';';
             });

            $('#chat-type').val('');
            $('#chats').append(
                ` <div class="d-flex align-items-center text-right justify-content-end msg-right"><div class="pr-2"> <span class="name">`+name+`</span><p id="`+n+`" class="msg">`+encodedStr+`</p></div></div>`
            );
            $('#chats .msg').last().css('background-color','#ccc');
            
            $('#chats').animate({
                scrollTop: $("#chats").get(0).scrollHeight
            },100);

            $.ajax({  // send message
                type: "POST",
                url: "home", 
                data: {
                    msg: encodedStr,
                    action:'new-msg',
                    userId: id 
                },
                success:function(result){
                    console.log(result.msg);
                    if(result.msg == "success")
                    {
                        setTimeout(function(){
                            $('#'+n).css('background-color','#fff');
                        },1000);
                    }
                   
            }});

        }
    }

    $('#send-icon').on('click', function(e){
        sendMsg();
    })

    $('#chat-type').keyup(function(e){
         if(e.keyCode == 13)
         {
            sendMsg();
         }
     });

    $("#search-user").keyup(function() {

        
        var input = $('#search-user').val();
        if (input == "") {
            $(".chat-search-result ul").html("");
        }
        else if(input.length>2) {
            $.ajax({
                type: "POST",
                url: "home",
                data: {   
                    action:"search-user" ,     
                    query: input
                },
                success: function(res) {
                    $(".chat-search-result ul").html("");
                    res.html.forEach(function(v,i){
                        $(".chat-search-result ul").append(
                            v
                        );
                    });
                }
            });
        }
    });

    $('#chat-select').on('click', '.search-result-user', function(e){
        e.preventDefault();
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: "home",
            data: {   
                action:"get-chat" , 
                offset: 0,
                userId: id,    
            },
            success: function(res) {
                console.log(res)
                $("#chats").html("");
                $('#chat-peer-title').text(res.peerName);
                $('#chat-peer-title').attr('data-my-id',res.peerId);
                $('#chats').attr('data-offset',res.offset);
      
      
                $("#chats").append(
                    '<div id="loading-chat" style="display:none;"><div class="loadingio-spinner-bean-eater-2pdyvrj4d2h" ><div class="ldio-gwhc8j99wub"><div><div></div><div></div><div></div></div><div><div></div><div></div><div></div></div></div></div></div>'
                );
                res.html.forEach(function(v,i){
                    $("#chats").append(
                        v
                    );
                });

                $('#search-user').val('');
                
                $(".chat-search-result ul").html("");
                $('#chat-select').hide('slow');
                $('#chatting').removeClass('hide');
                $('#chats').animate({
                    scrollTop: $("#chats").get(0).scrollHeight
                },100);
            }
        });
      
    });

    /**
     * Scrolling through the chat
     */
    var handlingChatScroll = 0;
    $('#chats').scroll(function() {

        // top of the chats div reached?
        if ($('#chats').scrollTop() == 0 && handlingChatScroll==0 ) {
            handlingChatScroll = 1;
            $('#loading-chat').show();
            var id = $('#chat-peer-title').attr('data-my-id');
            var offset = $('#chats').attr('data-offset');
            var currScrollHeight = $("#chats").get(0).scrollHeight;
            $.ajax({
                type: "POST",
                url: "home",
                data: {   
                    action:"get-chat" , 
                    offset: offset,
                    userId: id,    
                },
                success: function(res) {
                    console.log(res)
                    $('#chats').attr('data-offset',res.offset);
                    $('#loading-chat').remove(); 

                    res.html.forEach(function(v,i){
                        $("#chats").prepend(
                            v
                        );
                    });

                    $("#chats").prepend(
                        '<div id="loading-chat" style="display:none;"><div class="loadingio-spinner-bean-eater-2pdyvrj4d2h" ><div class="ldio-gwhc8j99wub"><div><div></div><div></div><div></div></div><div><div></div><div></div><div></div></div></div></div></div>'
                    );

                    
                    $('#chats').get(0).scrollTop=$("#chats").get(0).scrollHeight - currScrollHeight ;
                },
                complete:function(res)
                {
                    handlingChatScroll = 0;
                }
            });
            
        }
    });


    (function pollMsgN(){  
        //get open chat peer id

        var id  = $('#chat-peer-title').attr('data-my-id');

        $.ajax({   // are there any new messages，notifications for me?
            type: "POST",
            url: "home",
            data: {   
                action:"check-msg" , 
                userId: id,    
            },
            success: function(res) {
                console.log(res)
                if((res.msg == true) && (res.peerId == $('#chat-peer-title').attr('data-my-id')) )
                { // load messages into chat box
                   
                    res.html.forEach(function(v,i){
                        $("#chats").append(
                            v
                        );
                    });

                    $('#chats').animate({
                        scrollTop: $("#chats").get(0).scrollHeight
                    },100);

                    updateNotifications(res);
                    
                }else{
                    // load messages in notifications
                    if(res.msg == false )
                    {
                        updateNotifications(res);
                    }
                }
    
                setTimeout(pollMsgN,5000);
            },
            fail: function(res)
            {
                alert("Lost Network Connection");
                setTimeout(pollMsgN,5000);
            }
        });
    })();

    $('#msg-flag-btn').on('click', function(){

    });

    $('#notify-btn').on('click', function(){

    });

    /**
     * Posting messages
     */
    var $charCount, maxCharCount;

    $charCount = $('#tweet-modal .char-count')
    maxCharCount = parseInt($charCount.data('max'), 10);

    $('#tweet-modal textarea').on('keyup', function(e) {
        var tweetLength = $(e.currentTarget).val().length;

        $charCount.html(maxCharCount - tweetLength);
    });


    $('#btn-post-now').on('click', function(){
        var post = $('#tweet-modal textarea').val();

        var errors = [];
        if(post.trim().length == 0)
        {
            errors.push("post can't be empty");

            $('.error-msg-list').html('');
            errors.forEach(function(v,i){
                $('.error-msg-list').append(
                    '<div class="alert alert-warning "><strong>'+v+'</strong></div>'
                );
            });
            $('.error-msg-list').removeClass('invisible');
            return false;
        }else{
            $('.error-msg-list-login').html('');
            $('.error-msg-list-login').addClass('invisible');
        }
        

       $('#tweet-modal textarea').val(" ");
       $charCount.html(maxCharCount);
        $('#tweet-modal').modal('toggle');

        $('#creating-post').show();

         $.ajax({ 
            type: "POST",
            url: "home",
            data: {   
                action:"post" , 
                msg: post,    
            },
            success: function(res) {
                console.log(res)
                if(res.msg == "success")
                { 
       
                    $("#feed").prepend(
                        res.post
                    );
            
                    $('#feed').animate({
                        scrollTop: $("#chats").get(0).scrollHeight
                    },100);
                    
                }else{
                    var errors = res.errors;
                    $('.error-msg-list-login').html('');
                    errors.forEach(function(v,i){
                        $('.error-msg-list-login').append(
                            '<div class="alert alert-warning "><strong>'+v+'</strong></div>'
                        );
                    });
                    $('.error-msg-list-login').removeClass('invisible');
                }
    
            },
            fail: function(res)
            {
                //
            }
        });
       
    });

    /**
     * Retrieving post messages
     */
    $('.feed-page').on('click', function(e){
        var that = $(this);
        var pages =  that.parent().attr('data-page-count');
        that.parent().find('.active').removeClass('active');
        var page = that.find('a').html();
        //get posts on this page
        $.ajax({ 
            type: "GET",
            url: "home",
            data: {   
                action:"feed-page", 
                currPage: page,    
            },
            success: function(res) {
                console.log(res);
                if(res.msg == "success")
                { 
       
                    that.addClass('active');
                    if(page == pages)
                    {
                        if(that.parent().find('.feed-page-next')[0])
                        {
                            that.parent().find('.feed-page-next').hide();
                            that.parent().find('.feed-page-prev').show();
                        }
                    }
                    else if(page < pages)
                    {
                        that.parent().find('.feed-page-next').show();
                        if(page > 1)
                        {
                            that.parent().find('.feed-page-prev').show();
                        }else if(page == 1)
                        {
                            that.parent().find('.feed-page-prev').hide();
                        }
                    }
                    $("#feed").html("");

                    that.parent().attr('data-page-count', res.feedData.pages);

                    res.feedData.feeds.forEach(function(v,i){
                        $('#feed').append(
                           v
                        );
                    });
            
                    $('#feed').animate({
                        scrollTop: $("#chats").get(0).scrollHeight
                    },100);
                    
                }else{
                   // error
                }
    
            },
            fail: function(res)
            {
                //fail
            }
        });
        
    });

    $('.feed-page-prev').on('click', function(){
        var that = $(this);
        var pages =  that.parent().attr('data-page-count');
        var page  =  Number(that.parent().find('.active a').html());

        $.ajax({ 
            type: "GET",
            url: "home",
            data: {   
                action:"feed-page", 
                currPage: page-1,    
            },
            success: function(res) {
                console.log(res)
                if(res.msg == "success")
                { 
       
                    if(page>1)
                    {
            
                        if(that.parent().find('.active').prev().hasClass('continuity') && !(that.parent().find('.active').prev().css('display') == 'none'))
                        {
                            that.parent().find('.active').next().find('a').html(page);
                            that.parent().find('.active a').html(page-1);
            
                            if((page-2) == that.parent().find('.continuity').prev().find('a').html())
                            {
                                that.parent().find('.continuity').hide();
                            }
                        }else{
                            if((that.parent().find('.active').prev().css('display') == 'none'))
                            {
                                that.parent().find('.active').prev().prev().addClass('active');
                                that.parent().find('.active').eq(1).removeClass('active');
                            }else{
                                if(that.parent().find('.active').prev().hasClass('feed-page-prev'))
                                {
                                    that.parent().find('li a').eq(5).html(page+2);
                                    that.parent().find('li a').eq(4).html(page+1);
                                    that.parent().find('.active').next().find('a').html(page);
                                    that.parent().find('.active a').html(page-1);
                                }else{
                                    that.parent().find('.active').prev().addClass('active');
                                    that.parent().find('.active').eq(1).removeClass('active');
                                }
                            }
                            
                        }
            
                        if(page < pages)
                        {
                            that.parent().find('.feed-page-next').show();
                        }
                    }
                    
                    if(page == 2){
                        that.parent().find('.feed-page-prev').hide();
                    }

                    $("#feed").html("");

                    that.parent().attr('data-page-count', res.feedData.pages);

                    res.feedData.feeds.forEach(function(v,i){
                        $('#feed').append(
                           v
                        );
                    });
            
                    $('#feed').animate({
                        scrollTop: $("#chats").get(0).scrollHeight
                    },100);
                    
                }else{
                    // errors
                }
    
            },
            fail: function(res)
            {
                // fail
            }
        });
       
    });

    $('.feed-page-next').on('click', function(){
        var that = $(this);
        var pages =  that.parent().attr('data-page-count');
        var page  =  Number(that.parent().find('.active a').html());


        $.ajax({ 
            type: "GET",
            url: "home",
            data: {   
                action:"feed-page", 
                currPage: page+1,    
            },
            success: function(res) {
                console.log(res)
                if(res.msg == "success")
                { 
       
                    if(page<pages)
                    {
                        if(that.parent().find('.active').next().hasClass('continuity') && !(that.parent().find('.active').next().css('display') == 'none'))
                        {
                            that.parent().find('.active').prev().find('a').html(page);
                            that.parent().find('.active a').html(page+1);
            
                            if((page+2) == that.parent().find('.continuity').next().find('a').html())
                            {
                                that.parent().find('.continuity').hide();
                            }
                        }else{
                            if((that.parent().find('.active').next().css('display') == 'none'))
                            {
                                that.parent().find('.active').next().next().addClass('active');
                                that.parent().find('.active').eq(0).removeClass('active');
                            }else{
                                if(that.parent().find('.active').next().hasClass('feed-page-next'))
                                {
                                    that.parent().find('li a').eq(1).html(page-2);
                                    that.parent().find('li a').eq(2).html(page-1);
                                    that.parent().find('.active').prev().find('a').html(page);
                                    that.parent().find('.active a').html(page+1);
                                }else{
                                    that.parent().find('.active').next().addClass('active');
                                    that.parent().find('.active').eq(0).removeClass('active');
                                }
                        
                            }
                            
                        }
            
                        that.parent().find('.feed-page-prev').show();
                        
                    }
                    
                    if(page>pages-2){
                        that.parent().find('.feed-page-next').hide();
                    }

                    $("#feed").html("");

                    that.parent().attr('data-page-count', res.feedData.pages);

                    res.feedData.feeds.forEach(function(v,i){
                        $('#feed').append(
                           v
                        );
                    });
            
                    $('#feed').animate({
                        scrollTop: $("#chats").get(0).scrollHeight
                    },100);
                    
                }else{
                    // errors
                }
    
            },
            fail: function(res)
            {
                // fail
            }
        });

       
    });

    $('#close-modal-down').on('click',function(e){
        e.preventDefault();
        $('.error-msg-list').html('');
        $('.error-msg-list').addClass('invisible');
        $('#tweet-modal').modal('toggle');
    });

    $('#close-modal-up').on('click',function(e){
        e.preventDefault();
        $('.error-msg-list').html('');
         $('.error-msg-list').addClass('invisible');
        $('#tweet-modal').modal('toggle');
    });


    /**
     * Notifications retrival
     */
    $('#notify-btn').on('click', function(e){
        e.preventDefault();

        $('#notifications-modal').modal("hide");
        
        $.ajax({ 
            type: "GET",
            url: "home",
            data: {   
                action:"get-notifs", 
                currPage: 1,    
            },
            success: function(res) {
                console.log(res);
                if(res.msg == "success")
                { 
       
                    // $("#notifications-modal .modal-body ul").html("");

                    // $('#notifications-modal').attr('data-page-count', res.notifData.pages);

                    // res.notifData.notifications.forEach(function(v,i){
                    //     $('#notifications-modal .modal-body ul').append(
                    //        v
                    //     );
                    // });

                  
                    $("#notify-btn .badge").html("");
                    
                }else{
                   // error
                }
    
            },
            fail: function(res)
            {
                //fail
            },
            complete: function(res)
            {
                $('#notifications-modal').modal("toggle");
            }
        });

    });

    $(function() {
        $('.notification-info').css('cursor', 'pointer')
    
        .click(function() {
            window.location = $('a', this).attr('href');
            return false;
        });
    });

    /**
     * Searching main
     */
    
    $('#main-seach-home-icon').on('click', function(e){
        e.preventDefault();
        startSearchMain();
    });

    $(".see-more-search-main").on('click', function(e){
        e.preventDefault();
        
    });
   
    
    $('body').on('click','.m-search-option', function(e){
        e.preventDefault();
        $(this).parent().parent().find('.active').removeClass('active');
        $(this).addClass('active');
        var id = $(this).attr('id');
        var all =  $(this).parent().parent().find('#1 span').html();
        var users = $(this).parent().parent().find('#2 span').html();
        var posts = $(this).parent().parent().find('#3 span').html();
        updateSearchMain(id,all,users,posts);

    });

    // $('body').on('click', '.search-option a',function(e){
    //     e.preventDefault();
    //     $(this).parent().parent().find('.active').removeClass('active');
    //     $(this).parent().addClass('active');
    //     var id = $(this).parent().attr('id');
    //     alert(id);

    // });

    $('body').on('click','.search-option', function(e){
        e.preventDefault();
        $(this).parent().find('.active').removeClass('active');
        $(this).addClass('active');
        var id = $(this).attr('id');
        var all =  $(this).parent().find('#1 .profile-value').html();
        var users = $(this).parent().find('#2 .profile-value').html();
        var posts = $(this).parent().find('#3 .profile-value').html();
        updateSearchMain(id,all,users,posts);

    });

    /**
     * Uploading files
     */
    
    var gallery = document.querySelector('#gallery');
    var getVal = function (elem, style) { return parseInt(window.getComputedStyle(elem).getPropertyValue(style)); };
    var getHeight = function (item) { return item.querySelector('.content').getBoundingClientRect().height; };


    var resizeAll = function () {
        var altura = getVal(gallery, 'grid-auto-rows');
        var gap = getVal(gallery, 'grid-row-gap');
        gallery.querySelectorAll('.gallery-item').forEach(function (item) {
            var el = item;
            el.style.gridRowEnd = "span " + Math.ceil((getHeight(item) + gap) / (altura + gap));
        });
    };


    $(function(){ // let all dom elements are loaded
        $("#galleryFolderPreview").on('shown.bs.modal newMedia', function(e){

            gallery.querySelectorAll('img').forEach(function (item) {
                var altura = getVal(gallery, 'grid-auto-rows');
                var gap = getVal(gallery, 'grid-row-gap');
                var gitem = item.parentElement.parentElement;
                gitem.style.gridRowEnd = "span " + Math.ceil((getHeight(gitem) + gap) / (altura + gap));
                
            });
            
            resizeAll();
        });
    });

    $(document).on('click','.gallery-item', function(e){
        if($(this).children().find('.play-video').html() != undefined)
        {
           var poster = $(this).children().find('.play-video').attr("data-vid-ptr");
           var src = $(this).children().find('.play-video').attr("data-vid-src");
           var type = $(this).children().find('.play-video').attr("data-vid-type");
           $('#vid-playerHolder video').attr("poster",poster);
           $('#vid-playerHolder video').attr("src",src);
           $('#vid-playerHolder video').attr("type",type);

           $('#vid-playerHolder').show();
            console.log('has class');
        }else{
            $(this).toggleClass('full');
        }
        
    });

    $('.close-video').on('click', function(){

        $('#vid-playerHolder video').attr("poster","");
        $('#vid-playerHolder video').attr("src","");
        $('#vid-playerHolder video').attr("type","");
        $('#vid-playerHolder').hide();
    });

    $('.gallery-item .play-video').on('click', function(){
        alert('playing video0');
    });

    $(document).on('submit','.common-form',function(e) {
        e.preventDefault(); // <-- important
        var url = $(this).attr('action');
        var f = $(this);

        var progress = null; 
        if(f.hasClass('galleryUploader'))
        {
            progress = $('#gallery-upload-progress');
            $('#gallery-upload-progress').removeClass('active');
            $('#gallery-upload-progress').find('.progress-bar').css("width","0%");
            $('#gallery-upload-progress').addClass('active');
            $('#gallery-upload-progress').show();
        }

        // if (f.data('not-ready') !== undefined && f.data('not-ready')) return false;
        // if (f.data('no-loader') === undefined) pageLoader(true);

        f.ajaxSubmit({
            url : url,
            uploadProgress: function(event, position, total, percentComplete) {
                var percentVal = percentComplete;
                if (progress !== null) {
                    $('#gallery-upload-progress').removeClass('active');
                    $('#gallery-upload-progress').find('.progress-bar').css('width', percentVal + '%');
                    $('#gallery-upload-progress').addClass('active');
                }
            },
            success : function(res) {
                console.log(res);
                try{
        
                    if(res.caller == "gallery_folder")
                    {
                        if(res.msg == "success")
                        {

                            $('.error-gallery-folder').html('');
                            $('#newFolder-modal').modal('toggle');
                            $('.no-gallery-folder').remove();
                            $('.gallery-preview ul').prepend(res.html);

                        }else{
                            var errors = res.errors;
                            $('.error-gallery-folder').html('');
                            errors.forEach(function(v,i){
                                $('.error-gallery-folder').append(
                                    '<div class="alert alert-warning "><strong>'+v+'</strong></div>'
                                );
                            });
                            $('.error-gallery-folder').removeClass('invisible');
                        }
                    }else if(res.caller=="load_gallery")
                    {
                        console.log(res);
                        $('#galleryFolderPreview .modal-title').html(res.folderName);
                        $('.galleryUploader #i-g-f-id').val(res.folderId);
                        
                        $('#galleryFolderPreview .modal-body .gallery').html(res.folderMedia);
                        $('#galleryFolderPreview').modal('show');


                    }else if(res.caller=="gallery_media_upload")
                    {
                        if(res.msg == "success"){
                            $('.error-gallery-folder').html('');
                            var newMedia = jQuery(res.html);

                            $('#galleryFolderPreview .modal-body .gallery').prepend(newMedia);

                            $("#galleryFolderPreview").trigger("newMedia");

                        }else{
                            var errors = res.errors;
                            $('.error-gallery-folder').html('');
                            errors.forEach(function(v,i){
                                $('.error-gallery-folder').append(
                                    '<div class="alert alert-warning "><strong>'+v+'</strong></div>'
                                );
                            });
                            $('.error-gallery-folder').removeClass('invisible');
                        }
                        
                    }
                   
                } catch (e) {
                    console.log(e);
                    alert("an error occurred"+e);
                }

                if (progress !== null) {
                    $('#gallery-upload-progress').hide();
                }
                window.globalFormSubmitting = false;

            }
        });
        return false;
    });

});
function validate_file_size(input, type, func,param){
    var files = input.files;

    if(files.length==0)
    {
        return false;
    }

    console.log(files);
    if(func !== undefined) {
        eval(func)(param);
    }
}

function submit_file_upload() {
    $(".galleryUploader").submit();
}

var urlNewMsg = "http://localhost/moshi-moshi-app-redev-2021-1-7/app/assets/media/audio/nn.mp3"
function updateNotifications(res)
{
    var currN = Number($('#msg-flag-btn .badge').html());
    var currM = Number($('#notify-btn .badge').html());
    $('#msg-flag-btn .badge').html(badgeTruncate(res.unreadc));
    $('#notify-btn .badge').html(badgeTruncate(res.unreadc));

    if(Number(res.unreadc) > currN)
    {
        playSound(urlNewMsg);
    }
    // if(Number(res.unreadn) > currM)
    // {

    // }
}

function badgeTruncate(num)
{
    num = Number(num);
    return num > 9 ? "9+" : (num == 0)?"":num;
}

function playSound(url) {
    const audio = new Audio(url);
    audio.play();
}

function startSearchMain()
{
    var input =  $('#main-seach-home-input').val().trim();
    document.cookie = "query="+input;
   
    if(input.length>0) {
        $.ajax({
            type: "POST",
            url: "home",
            data: {   
                action: "search-main" ,     
                filter: "all",
                query: input
            },
            success: function(res) {
                //console.log(res);
                if(res.msg == "success")
                {
                    $('.collapse').collapse('hide');
                    $('#main-seach-home-input').val('');
                    $('#major-row-home #main').eq(0).replaceWith(res.searchResult);
                    $('#major-row-home #profile').eq(0).replaceWith(res.searchOptions);

                    console.log(res.searchResult);
                    console.log(res.searchOptions);
                    history.pushState({page: 2}, "title search", "?q="+input+"&f=all");
                }else{

                }
            }
        });
    }else{
        console.log('short');
    }

    return false;
}


function updateSearchMain(filter,all,users,posts)
{
    $.ajax({
        type: "POST",
        url: "home",
        data: {   
            action: "search-main" , 
            filter: filter,  
            query: getCookie("query")  
        },
        success: function(res) {
            if(res.msg == "success")
            {
                $('#major-row-home #main #main-card').eq(0).replaceWith(jQuery(res.searchResult).find('#main-card'));
                history.replaceState({page: 2}, "title search", "?q="+res.query+"&f="+res.filter);
            }else{

            }
        }
    });
}

function previewProPic(input){
        
    var file = $("input[type=file]").get(0).files[0];

    if(file){
        var reader = new FileReader();

        reader.onload = function(){
            $('.avatar').attr("src", reader.result);
        }
        reader.readAsDataURL(file);
    }
}


function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
}