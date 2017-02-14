<?php if(!defined('IN_KLEEJA')) { exit; } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?=$title?> <?php echo $title ? '-' :'';?> <?=$config['sitename']?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=<?=$charset?>" />
    <meta http-equiv="Content-Language" content="<?=$lang['LANG_SMALL_NAME']?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="copyrights" content="Powered by Kleeja 2" />
    <!-- metatags.info/all_meta_tags -->
    <link rel="shortcut icon" href="images/favicon.ico" />
    <link rel="icon" type="image/gif" href="images/favicon.gif" />
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png" />
    <link rel="apple-touch-startup-image" href="images/iPhone.png" />

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" media="all" href="<?=STYLE_PATH?>css/main.css?<?=time()?>" />
    <?php if($lang['DIR']=='rtl'):?>
    <link rel="stylesheet" type="text/css" media="all" href="<?=STYLE_PATH?>css/rtl.css?<?=time()?>" />
    <?php endif;?>
    <?php if(is_browser('ie')):?>
    <link rel="stylesheet" type="text/css" media="all" href="<?=STYLE_PATH?>css/ie.css?<?=time()?>" />
    <?php endif;?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <?php
      /* use this hook to add more in the head tag */
      ($hook = $plugin->run_hook('header_template_head_tag')) ? eval($hook) : null;?>
    <!-- Extra code -->
    <?=$extra_head_code?>
  </head>
  <body>
  <!-- Fixed navbar -->
  <nav class="navbar navbar-default">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
          <span class="sr-only">...</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?=get_url_of('index')?>"><?=$config['sitename']?></a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav">
            <?php
              /* use this hook to add more to the top menu */
              ($hook = $plugin->run_hook('header_template_menu_start')) ? eval($hook) : null;?>

            <!-- public links  -->
              <li<?php if($current_page == 'index'):?> class="active"<?php endif;?>><a href="<?=get_url_of('index')?>"><?=$lang['HOME']?></a></li>
              <li<?php if($current_page == 'guide'):?> class="active"<?php endif;?>><a href="<?=get_url_of('guide')?>"><?=$lang['GUIDE']?></a></li>
              <?php if($config['allow_stat_pg'] && $user->can('access_stats')):?>
              <li<?php if($current_page == 'stats'):?> class="active"<?php endif;?>><a href="<?=get_url_of('stats')?>"><?=$lang['STATS']?></a></li>
              <?php endif;?>
              <?php if($user->can('access_report')):?>
              <li<?php if($current_page == 'report'):?> class="active"<?php endif;?>><a href="<?=get_url_of('report')?>"><?=$lang['REPORT']?></a></li>
              <?php endif;?>
              <?php if($user->can('access_call')):?>
              <li<?php if($current_page == 'call'):?> class="active"<?php endif;?>><a href="<?=get_url_of('call')?>"><?=$lang['CALL']?></a></li>
              <?php endif;?>

              <?php if($user->is_user()):?>
                  <!--  user links -->
                  <li<?php if($current_page == 'profile'):?> class="active"<?php endif;?>><a href="<?=get_url_of('profile')?>"><?=$lang['PROFILE']?></a></li>
                  <?php if($config['enable_userfile'] && $user->can('access_fileuser')):?>
                  <li<?php if($current_page == 'fileuser'):?> class="active"<?php endif;?>><a href="<?=get_url_of('fileuser')?>"><?=$lang['YOUR_FILEUSER']?></a></li>
                  <?php endif;?>
                  <li<?php if($current_page == 'logout'):?> class="active"<?php endif;?>><a href="<?=get_url_of('logout')?>"><?=$lang['LOGOUT']?> <?=$user->data['name']?></a></li>
                  <?php else:?>
                  <li<?php if($current_page == 'login'):?> class="active"<?php endif;?>><a href="<?=get_url_of('login')?>"><?=$lang['LOGIN']?></a></li>
                  <?php if($config['register']):?>
                  <li<?php if($current_page == 'register'):?> class="active"<?php endif;?>><a href="<?=get_url_of('register')?>"><?=$lang['REGISTER']?></a></li>
                  <?php endif;?>
              <?php endif;?>

              <?php
                /* use this hook to add more to the top menu */
                ($hook = $plugin->run_hook('header_template_menu_end')) ? eval($hook) : null;?>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
</nav>


<!--start body section-->
<div class="container">
