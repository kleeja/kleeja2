
<!-- imgs begins -->
<div class="page-header">
  <h1>
     <?=$lang['R_IMAGES']?>
     <?php if($total_pages > 1):?><small>[<?=$current_page?> / <?=$total_pages?>]</small><?php endif;?>
 </h1>
</div>


<form method="post" name="imgform" action="<?=$action?>" id="imgs_form" role="form">

<?php if($no_results): ?>
	<div class="alert alert-info">
		<p><?=$lang['NO_RESULT_USE_SYNC']?></p>
	</div>
<?php else: ?>

<!-- start data div -->
<div class="row" id="thumbnails">

	<?php foreach($images_list as $id=>$img):?>
		<?php if($img['tdnum']):?>
		<div class="col-md-12">
		<?php endif; ?>
		<div class="img-thumbnail max-thumbnail-size" id="su[<?=$img['id']?>]" rel="popover" data-title="<?=$img['name']?>">
			<a href="<?=$img['href']?>" target="_blank">
				<img src="<?=$img['thumb_link']?>"  alt="<?=$lang['FILENAME']?> : <?=$img['name']?>">
			</a>
			<div class="checkbox kcheck"><label>
				<input id="del_<?=$img['id']?>" name="del_<?=$img['id']?>" type="checkbox" value="<?=$img['id']?>" rel="_del" class="delete"> <small><?=$lang['DELETE']?></small>
				<div class="ktip" style="display: none;">
					 <span id="user_<?=$img['id']?>"><?=$img['user']?></span>
					  <span id="ip_<?=$img['id']?>"><?=$img['ip']?></span>
				</div>
			</div>
		</div>

		<div style="display: none;" class="extra_info">
			<div class="img-info-box<?php if($lang['DIR'] == 'rtl'):?> text-right<?php endif; ?>">
			<span class="text-muted"><?=$lang['FILENAME']?> : </span><?=$img['name']?><br>
			<span class="text-muted"><?=$lang['FILEUPS']?> : </span> <?=$img['ups']?><br>
			<span class="text-muted"><?=$lang['FILESIZE']?> : </span> <?=$img['size']?><br>
			<span class="text-muted"><?=$lang['FILEDATE']?> : </span> <?=$img['time']?><br>
			<span class="text-muted"><?=$lang['BY']?> : </span> <?=$img['user']?><br>
			<span class="text-muted"><?=$lang['IP']?> : </span> <?=$img['ip']?>
			</div>
		</div>

		<?php if($img['tdnum2']):?>
		</div>
		<?php endif; ?>

	<?php endforeach;?>
</div>
<!-- end data div -->

<!-- pagination -->
<?=$page_nums?>
<hr>

<!-- button -->
<p class="submit <?php if($lang['DIR'] == 'rtl'):?>pull-left<?php endif; ?>">

	<select class="form-control"  id="search-one-item" style="display:none">
		<option value="0"><?=$lang['SEARCH_SUBMIT']?> : </option>
		<option value="1"><?=$lang['SEARCH4FILES_BYIP']?></option>
		<option value="2"><?=$lang['SEARCH4FILES_BYUSER']?></option>
	</select>
	<input type="hidden" name="submit" value="1" />
	<button type="button" class="btn btn-default" onclick="checkAll(document.imgform, '_del', 'su', 'img-thumbnail max-thumbnail-size thumbnail-selected', 'img-thumbnail max-thumbnail-size');"><span class="glyphicon glyphicon-th-list"></span> <?=$lang['CHECK_ALL']?></button>
	<button type="submit" class="btn btn-primary" name="submit"><span><?=$lang['DEL_SELECTED']?></span></button>
</p>
<?php endif; ?>

<?=$H_FORM_KEYS?>
</form>

<!-- imgs end -->

<!--div class="note-info">
	<h3>Keyboards Keys /</h3>
	->	: <?=$lang['NEXT']?> <br />
	<-	: <?=$lang['PREV']?> <br />
</div-->
