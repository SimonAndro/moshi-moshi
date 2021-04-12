<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> <?php echo getAppConfig('site-title') ?> </title>

    <link rel="stylesheet" href="<?=loadAsset('css/bootstrap.css');?>">
    <link rel="stylesheet" href="<?=loadAsset('css/thirdparty.css');?>">
    <link rel="stylesheet" href="<?=loadAsset('css/settings.css');?>">
    <link rel="stylesheet" href="<?=loadAsset('css/base.css');?>">
    <link rel="stylesheet" href="<?=loadAsset('thirdparty/video/dist/css/ckin.css')?>">

    <!-- Bootstrap 4 CDN CSS -->
    <!--     <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha/css/bootstrap.min.css" rel="stylesheet" integrity="sha256-GHW2S7IZAQe8+YkyL99LyDj1zdWXSPOG+JZafCtKiSc= sha512-vxM32w6T7zJ83xOQ6FT4CEFnlasqmkcB0+ojgbI0N6ZtSxYvHmT7sX2icN07TqEqr5wdKwoLkmB8sAsGAjCJHg==" crossorigin="anonymous"> -->

    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body >
    <header>
        <?php echo loadTemplate('layout/header.html.php',['user'=>$user,'reg'=>$reg]);  ?>    
    </header>

    <main class="main-body">
         <?=$output?>
    </main>

    <?php if(!$user || $reg): ?>
    <footer class="footer">
        <div class="container">
            <p class="text-muted text-center"> &copy; Moshi Moshi <?php echo date('Y')?>. All rights Reserved</p>
        </div>
    </footer>
    <?php endif ?>

    <!-- <script src="<?=loadAsset('js/jquery-1.11.3.js');?>"></script> -->
    <script src="<?=loadAsset('js/jquery-3.5.1.js');?>"></script>
    <script src="<?=loadAsset('js/jquery.form.js');?>"></script>
    <script src="<?=loadAsset('js/bootstrap.js');?>"></script>

    <!-- Bootstrap 4 CDN JS -->
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha/js/bootstrap.min.js" integrity="sha256-+h0g0j7qusP72OZaLPCSZ5wjZLnoUUicoxbvrl14WxM= sha512-0z9zJIjxQaDVzlysxlaqkZ8L9jh8jZ2d54F3Dn36Y0a8C6eI/RFOME/tLCFJ42hfOxdclfa29lPSNCmX5ekxnw==" crossorigin="anonymous"></script> -->

    <script src="<?=loadAsset('js/main.js');?>"></script>
    <script src="<?=loadAsset('thirdparty/video/dist/js/ckin.js');?>"></script>
</body>
</body>

</html>