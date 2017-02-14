<!-- general -->

<?php if($current_smt == 'general'):?>

<!-- start general -->
<div class="page-header">
  <h1><?=$lang['WELCOME']?>, <?=$user->data['name']?></h1>


<!-- last visists -->
<?php if($last_visit):?>

		<div class="btn-group">
		    <div class="btn-group">
		      <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
		        <span class="glyphicon glyphicon-time"></span> <?=$lang['IMG_LST_VST_SEARCH']?>: <?=$lang['TIME']?>
		        <span class="caret"></span>
		      </button>
		      <ul class="dropdown-menu">
		        <?php if($image_last_visit):?><li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_imgs?><?=$image_last_visit?>';"><?=$lang['LAST_VISIT']?></a></li><?php endif;?>
		        <li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_imgs?>' + (Math.round((new Date()).getTime() / 1000) - 3600);"><?=$lang['HOUR']?></a></li>
		        <li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_imgs?>' + (Math.round((new Date()).getTime() / 1000) - 18000);"><?=$lang['5HOURS']?></a></li>
		        <li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_imgs?>' + (Math.round((new Date()).getTime() / 1000) - 86400);"><?=$lang['DAY']?></a></li>
		        <li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_imgs?>' + (Math.round((new Date()).getTime() / 1000) - 604800);"><?=$lang['WEEK']?></a></li>
		        <li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_imgs?>' + (Math.round((new Date()).getTime() / 1000) - 2592000);"><?=$lang['MONTH']?></a></li>
		        <li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_imgs?>' + (Math.round((new Date()).getTime() / 1000) - 31536000);"><?=$lang['YEAR']?></a></li>
		      </ul>
		    </div>
	  </div>

      <b><?=$lang['OR'];?></b>

		<div class="btn-group">
		    <div class="btn-group">
		      <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
		        <span class="glyphicon glyphicon-time"></span> <?=$lang['FLS_LST_VST_SEARCH']?>: <?=$lang['TIME']?>...
		        <span class="caret"></span>
		      </button>
		      <ul class="dropdown-menu">
                  <?php if($files_last_visit):?><li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_files?><?=$files_last_visit?>';"><?=$lang['LAST_VISIT']?></a></li><?php endif;?>
		        <li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_files?>' + (Math.round((new Date()).getTime() / 1000) - 3600);"><?=$lang['HOUR']?></a></li>
		        <li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_files?>' + (Math.round((new Date()).getTime() / 1000) - 18000);"><?=$lang['5HOURS']?></a></li>
		        <li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_files?>' + (Math.round((new Date()).getTime() / 1000) - 86400);"><?=$lang['DAY']?></a></li>
		        <li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_files?>' + (Math.round((new Date()).getTime() / 1000) - 604800);"><?=$lang['WEEK']?></a></li>
		        <li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_files?>' + (Math.round((new Date()).getTime() / 1000) - 2592000);"><?=$lang['MONTH']?></a></li>
		        <li><a href="javascript:void(0)" onclick="javascript:location.href='<?=$h_lst_files?>' + (Math.round((new Date()).getTime() / 1000) - 31536000);"><?=$lang['YEAR']?></a></li>
		      </ul>
		    </div>
	  </div>


<?php endif; ?>
<!-- end last visits -->

</div>
<!-- /header -->

<!-- notes general -->
<?php if($ADM_NOTIFICATIONS):?>
<?php foreach($ADM_NOTIFICATIONS as $n=>$note):?>
    <dl id="system-message-<?=$note['old_files']?>">
		<dd class="alert alert-<?=$note['msg_type']?>">
			<ul>
				<li><strong><?=$note['title']?></strong>: <?=$note['msg']?></li>
			</ul>
		</dd>
    </dl>
<?php endforeach;?>
<?php endif;?>




<!-- hurry-hurry -->
<hr>
<h2><?=$lang['HURRY_HURRY']?></h2>

<div class="row">
  <div class="col-md-4">
  	<p>
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			<?=$lang['CHANGE']?>: <?=$lang['STYLE']?> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
            <?php foreach($hurry_styles_list as $name):?>
			<li><a href="<?=$hurry_style_link?><?=$name?>"><?=$name?></a></li>
            <?php endforeach;?>
			</ul>
		</div>
  	</p>

  </div>
  <div class="col-md-4">
  	<p>
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
			<span class="glyphicon glyphicon-globe"></span> <?=$lang['CHANGE']?>: <?=$lang['LANGUAGE']?> / <?=$lang['ALL']?> <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
            <?php foreach($hurry_langs_list as $name):?>
			<li><a href="<?=$hurry_lang_link?><?=$name?>"><?=$name?></a></li>
            <?php endforeach;?>
			</ul>
		</div>
 	</p>
  </div>
  <div class="col-md-4">
  	<p>
		<div class="btn-group">
  			<button class="btn btn-default" onclick="javascript:location.href='<?=$del_cache_link?>';"><span class="glyphicon glyphicon-trash"></span> <?=$lang['DEL_CACHE']?></button>
		</div>
	</p>
  </div>
</div>


<!-- the big box begin general2 -->
<hr>
<h2><?=$lang['GENERAL_STAT']?></h2>

<div class="table-responsive">
<table class="table text- table-bordered">
	<thead>
		<tr>
			<th><?=$lang['AFILES_NUM']?></th>
			<th><?=$lang['AUSERS_NUM']?></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?=$files_number?> <?=$lang['FILE']?></td>
			<td><?=$users_number?></td>
		</tr>
	</tbody>
</table>
</div>

<div class="row">
	<div class="col-md-12">
		<strong><?=$lang['AFILES_SIZE_SPACE']?></strong>
		<div class="progress">
		  <div class="progress-bar" role="progressbar" aria-valuenow="<?=$per1?>" aria-valuemin="0" aria-valuemax="100" style="width: <?=$per1?>%;">
		    <span class="sr-only"><?=$per1?>/100%</span>
		  </div>
		</div>
	</div>
</div>
<!-- admin start general section2 -->

<?php if($stats_chart):?>
<hr>
<h2><?=$lang['STATS']?></h2>
<div class="row">
	<div class="col-md-12" id="chart_stats">
		<script type="text/javascript">
		<?=$stats_chart?>
		</script>
</div>
<?php else:?>
    	<script type="text/javascript">
    	arrayOfDataMulti = new Array();
    	</script>
<?php endif;?>

<!-- end first section general -->

<?php elseif($current_smt == 'other'):?>
<!-- current_smt other -->

<div class="row">
  <div class="col-md-4">
    <h3><?=$lang['KLEEJA_VERSION']?></h3>
  	<span class="glyphicon glyphicon-link"></span> <?=$kleeja_version?>
  </div>
  <div class="col-md-4">
  	<h3><?=$lang['PHP_VER']?></h3>
	<span class="glyphicon glyphicon-link"></span> <?=$php_version?>
  </div>
  <div class="col-md-4">
  	<h3><?=$lang['MYSQL_VER']?></h3>
	<?=$mysql_version?>
  </div>
</div>
<hr>

<h3><?=$lang['OTHER_INFO']?></h3>

<div class="table-responsive">
<table class="table table-striped table-bordered">
	<thead>
		<tr>
			<th>php.ini/setting</th>
			<th>Value</th>
			<th>#</th>
		</tr>
	</thead>
	<tbody>
	<tr>
		<td><abbr title="Whether or not to allow HTTP file uploads">file_uploads</abbr></td>
		<td><?=$file_uploads_ini?></td>
		<td><a href="http://www.php.net/manual/en/ini.core.php#ini.file-uploads" class="btn btn-default btn-md" target="_tab"><span class="glyphicon glyphicon-new-window"></span> <?=$lang['OTHER_INFO']?></a></td>
	</tr>
		<tr>
			<td><abbr title="The maximum number of files allowed to be uploaded simultaneously">max_file_uploads</abbr></td>
			<td><?=$max_file_uploads_ini?> <?=$lang['FILE']?></td>
			<td><a href="http://www.php.net/manual/en/ini.core.php#ini.max-file-uploads" class="btn btn-default btn-md" target="_tab"><span class="glyphicon glyphicon-new-window"></span> <?=$lang['OTHER_INFO']?></a></td>
		</tr>
		<tr>
			<td><abbr title="The maximum size of an uploaded file">upload_max_filesize</abbr></td>
			<td><?=$upload_max_filesize?></td>
			<td><a href="http://www.php.net/manual/en/ini.core.php#ini.upload-max-filesize" class="btn btn-default btn-md" target="_tab"><span class="glyphicon glyphicon-new-window"></span> <?=$lang['OTHER_INFO']?></a></td>
		</tr>
		<tr>
			<td><abbr title="Sets max size of post data allowed. This setting also affects file upload. To upload large files, this value must be larger than upload_max_filesize. If memory limit is enabled by your configure script, memory_limit also affects file uploading. Generally speaking, memory_limit should be larger than post_max_size">post_max_size</abbr></td>
			<td><?=$post_max_size?></td>
			<td><a href="http://www.php.net/manual/en/ini.core.php#ini.post-max-size" class="btn btn-default btn-md" target="_tab"><span class="glyphicon glyphicon-new-window"></span> <?=$lang['OTHER_INFO']?></a></td>
		</tr>
		<tr>
			<td><abbr title="Maximum time in seconds a script is allowed to run before it is terminated by the parser">max_execution_time</abbr></td>
			<td><?=$max_execution_time?> <?=$lang['W_PERIODS_P'][0]?></td>
			<td><a href="http://www.php.net/manual/en/info.configuration.php#ini.max-execution-time" class="btn btn-default btn-md" target="_tab"><span class="glyphicon glyphicon-new-window"></span> <?=$lang['OTHER_INFO']?></a></td>
		</tr>
		<tr>
			<td><abbr title="Maximum amount of memory in bytes that a script is allowed to allocate">memory_limit</abbr></td>
			<td><?=$memory_limit?></td>
			<td><a href="http://www.php.net/manual/en/ini.core.php#ini.memory-limit" class="btn btn-default btn-md" target="_tab"><span class="glyphicon glyphicon-new-window"></span> <?=$lang['OTHER_INFO']?></a></td>
		</tr>
	</tbody>
</table>
</div>
<hr>


<h3><?=$lang['SEARCH_STAT']?></h3>

<div class="row">
  <div class="col-xs-6">
  	<h4>Google</h4>
	<?=$lang['LAST_GOOGLE']?>: <?=$s_last_google?><br />
	<?=$lang['GOOGLE_NUM']?>: <?=$s_google_num?> <br />
  </div>
  <div class="col-xs-6">
  	<h4>Bing</h4>
  		<?=$lang['LAST_BING']?>:	<?=$s_last_bing?><br />
  		<?=$lang['BING_NUM']?>: <?=$s_bing_num?><br />
 	</div>
</div>
<hr>

<?php elseif($current_smt == 'team'):?>
<!-- current_smt team -->

<br>

<div class="panel panel-primary">
    <div class="panel-heading">Abdullrahman M. Al-Shawaiee</div>
    <div class="panel-body">
      <p>
   		م. عبدالرحمن الشويعي<br>
		<a href="http://twitter.com/abdu1m" target="_tab">@abdu1m</a><br/>
		2007-<?=date('Y')?><br>
      </p>
    </div>
</div>


<div class="panel panel-primary">
    <div class="panel-heading">Bader N. Al-Mutairi</div>
    <div class="panel-body">
      <p>
		  بدر المطيري<br>
		2009-<?=date('Y')?><br>
      </p>
	  <p >
    </div>
</div>



<hr>



<!-- the big box end team -->

<?php if($translator_copyrights):?>
<!-- the big box begin trns -->
<hr>
<div class="panel panel-default">
	 <div class="panel-body">
		 	<?=$translator_copyrights?>
	</div>
</div>
<!-- the big box end trns -->
<?php endif;?>

<br>
<hr>
<div class="text-muted text-center">Kleeja proudly made in Buraydah City <br> Trusted since 2007</div>

<?php endif; #current_smt ?>

<!-- admin start endoffile -->
