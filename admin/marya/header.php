<!DOCTYPE html>
<html lang="<?=$lang['LANG_SMALL_NAME']?>">
<head>
<title><?=$lang['KLEEJA_CP']?> - <?=isset($config['sitename'])? $config['sitename'] : ''?></title>
<!-- top-head -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link rel="shortcut icon" href="<?=ADMIN_STYLE_PATH?>images/favicon.ico" />
<link rel="icon" type="image/gif" href="<?=ADMIN_STYLE_PATH?>images/favicon.gif" />

<link rel="stylesheet" type="text/css" media="screen" href="<?=ADMIN_STYLE_PATH?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?=ADMIN_STYLE_PATH?>css/bootstrap-theme.min.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?=ADMIN_STYLE_PATH?>css/stylesheet.css" />


<?php if($lang['DIR'] == 'rtl'):?>
<style type="text/css">
	body{font-family: "Tahoma",Arial,sans-serif;}
	.container{
		direction: rtl; /* placing it here fixes the issue with jquery white page bug */
	}
</style>
<?php endif?>
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="<?=ADMIN_STYLE_PATH?>js//html5shiv.js"></script>
  <script src="<?=ADMIN_STYLE_PATH?>js/respond.min.js"></script>
<![endif]-->
</head>
<body>

    <!-- Static navbar -->
    <div class="navbar navbar-default navbar-static-top navbar-inverse">
      <div class="container">
        <div class="navbar-header <?php if($lang['DIR'] == 'rtl'):?>>navbar-right<?php endif?>">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="./"><img src="<?=ADMIN_STYLE_PATH?>images/logo.png" style="height:25px" title="kleeja"> <span class="label label-default"><?=KLEEJA_VERSION?></span></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav <?php if($lang['DIR'] == 'rtl'):?>>navbar-right<?php endif?>">
            <li><a href="<?=isset($config['siteurl']) ? $config['siteurl'] : '/'?>"><?=$lang['RETURN_HOME']?> <span class="glyphicon glyphicon-arrow-left"></span></a></li>
            <li><a href="./?cp=b_lgoutcp&amp;<?=$GET_FORM_KEY_GLOBAL?>" onclick="javascript:return confirm_from();" ><?=$lang['R_LGOUTCP']?> [ <?=$user->data['name']?> ]</a></li>
            <li><a href="http://www.kleeja.com/support/" target="_blank"><?=$lang['SUPPORT_MOFFED']?></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>


 <div class="container">
	<!--  menu -->
	<?php if($show_side_menu):?>
   <div class="col-md-3 col-xs-12 col-sm-12 sidebar-offcanvas <?php if($lang['DIR'] == 'rtl'):?>pull-right<?php endif?>" id="sidebar" role="navigation">
      <div class="well sidebar-nav">
        <ul class="nav nav-pills nav-stacked">
			<li class="<?php echo $go_to == 'start' ? 'active' : '';?>">
				<a title="<?=$lang['R_CPINDEX']?>" class="cpindex" href="./"><?=$lang['R_CPINDEX']?></a>
			</li>

			<?php foreach($adm_extensions_menu as $i=>$item):?>
			<li class="<?php if($item['current']):?>?active<?php endif;?>">
				<a title="<?=$item['title']?>" class="side_anchor" href="<?=$item['link']?>" <?php if($item['confirm']):?>onclick="javascript:confirm_from();"<?php endif;?>>
					<?=$item['title']?> <?=$item['kbubble']?>
				</a>
			</li>
			<?php endforeach;?>
        </ul>
      </div><!--/.well -->
  </div><!--/menu-->
	<?php endif;?>

 <!-- content -->
<div class="col-md-<?php if($show_side_menu):?>9<?php else:?>12<?php endif;?>" id="content">
<?php if(isset($go_menu_html)):?>
<?php if($go_to == 'options'):?><div class="panel panel-default"><div class="panel-body"><?php endif;?>
  <ul class="nav nav-<?php echo $go_to == 'options' ? 'pills' : 'tabs';?>">
	  <?=$go_menu_html?>
  </ul>
<?php if($go_to == 'options'):?></div></div><?php endif;?>
<?php endif;?>
