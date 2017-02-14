<?php if(!defined('IN_KLEEJA')) { exit; } ?>
<!-- uploading template -->

<!-- Erros -->
<?php if($ERRORS):?>
	<div class="alert alert-danger">
		<ul>
		<?php foreach($ERRORS as $error):?>
			<li><?=$error?></li>
		<?php endforeach;?>
		</ul>
	</div>
<?php endif;?>



<!-- Results -->
<?php foreach($RESULTS as $result):?>

<div class="panel panel-info">
  <div class="panel-heading"><?=$result['name']?></div>
  <div class="panel-body">

  <?php if(isset($result['thumb'])):?>

	  <div class="text-center"><a onclick="window.open(this.href,'_blank');return false;" href="<?=$result['image']?>"><img src="<?=$result['thumb']?>" alt="" class="img-rounded" /></a></div>

	  <!--  URL_F_THMB -->
	  <div class="list-group-item">
		  <h4 class="list-group-item-heading"><?=$lang['URL_F_THMB']?></h4>
		  <p class="list-group-item-text"><textarea rows="2" cols="40" class="up_input" tabindex="1" onclick="this.select();">[url=<?=$result['image']?>][img]<?=$result['thumb']?>[/img][/url]</textarea></p>
	  </div>

  <?php endif?>

 <div class="list-group">

	<?php if(isset($result['image'])):?>

			<!--  URL_F_IMG -->
			<div class="list-group-item">
				<h4 class="list-group-item-heading"><?=$lang['URL_F_IMG']?></h4>
				<p class="list-group-item-text"><textarea rows="1" cols="40" class="up_input" tabindex="2" onclick="this.select();"><?=$result['image']?></textarea></p>
			</div>

			<!--  URL_F_BBC -->
			<div class="list-group-item">
				<h4 class="list-group-item-heading"><?=$lang['URL_F_BBC']?></h4>
				<p class="list-group-item-text"><textarea rows="2" cols="40" class="up_input" tabindex="3" onclick="this.select();">[url=<?=$config['siteurl']?>][img]<?=$result['image']?>[/img][/url]</textarea></p>
			</div>

			<!--  HTML -->
			<div class="list-group-item">
				<h4 class="list-group-item-heading">HTML</h4>
				<p class="list-group-item-text"><textarea rows="4" cols="40" class="up_input" tabindex="4" onclick="this.select();">&lt;a href=&quot;<?=$config['siteurl']?>&quot; target=&quot;_blank&quot;&gt;&lt;img src=&quot;<?=$result['image']?>&quot; border=&quot;0&quot; /&gt;&lt;/a&gt;</textarea></p>
			</div>

	<?php endif?>

	<?php if(isset($result['file'])):?>

		<!--  URL_F_FILE -->
		<div class="list-group-item">
			<h4 class="list-group-item-heading"><?=$lang['URL_F_FILE']?></h4>
			<p class="list-group-item-text"><textarea rows="2" cols="40" class="up_input" tabindex="5" onclick="this.select();"><?=$result['file']?></textarea></p>
		</div>

		<!--  URL_F_BBC -->
		<div class="list-group-item">
			<h4 class="list-group-item-heading"><?=$lang['URL_F_BBC']?></h4>
			<p class="list-group-item-text"><textarea rows="2" cols="40" class="up_input" tabindex="6" onclick="this.select();">[url=<?=$result['file']?>]<?=$result['file']?>[/url]</textarea></p>
		</div>

	<?php endif?>

	<?php if(isset($result['delete_code'])):?>

		<!--  URL_F_DEL -->
		<div class="list-group-item">
			<h4 class="list-group-item-heading"><?=$lang['URL_F_DEL']?></h4>
			<p class="list-group-item-text"><textarea rows="2" cols="40" class="up_input" tabindex="7" onclick="this.select();"><?=$result['delete_code']?></textarea></p>
		</div>

	<?php endif?>
</div>
</div>
</div>

<?php endforeach?>

<br>
<br>
<hr>
<div class="text-center">
	<a href="<?=get_url_of('index')?>" class="btn btn-lg btn-primary"><?=$lang['DOWNLOAD_F']?></a>
</div>

<!-- @end-uploading-template -->
