<?php if(!defined('IN_KLEEJA')) { exit; } ?>
<!-- Users Files template -->

<!--  title -->
<div class="page-header">
  <h1><?=$current_title?> <?php if($user_himself):?><small><?=$lang['YOUR_FILEUSER']?></small><?php endif;?></h1>
</div>



<?php if($user_himself):?>
<!-- box user name and all files  -->
<div class="row">
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading"><?=$lang['PUBLIC_USER_FILES']?></div>
			  <div class="panel-body">
			    <?=$username?>
			  </div>
			</div>
		</div>
		<div class="col-md-6">
			<div class="panel panel-default">
			  <div class="panel-heading"><?=$lang['ALL_FILES']?></div>
			  <div class="panel-body">
			    <strong><?=$nums_rows?></strong>
			  </div>
			</div>
		</div>
</div>
<!-- @end-box-user-name-and-all-files -->
<?php endif;?>


<?php if($no_results):?>

<!-- no files user -->
<div class="alert alert-info">
	<?=$lang['NO_FILE_USER']?>
</div>
<!-- @end-no-files-user -->

<!-- fileuser_files -->
<?php else:?>

<?php if($user_himself):?>
<form name="c" action="<?=$action?>" method="post" onsubmit="javascript:return confirm_from();">
<?php endif;?>

<div class="row">

	<?php /* The loop */ $loop_number = 1;?>
	<?php while($file=$SQL->fetch($result)):?>

			<?php /* First row */ ?>
			<?php if($loop_number == 1):?>

			<?php endif;?>

			<?php /* Every 4 rows */ ?>
			<?php if($loop_number % 4 == 0):?>

			<?php endif;?>


			<div class="col-sm-3 col-md-3 col-xs-12">

				<!-- check box  -->
				<?php if($user_himself):?>
				<div class="kcheck input-group">
					<span class="input-group-addon">
					<input id="del_<?=$file['id']?>" name="del_<?=$file['id']?>" type="checkbox" value="<?=$file['id']?>" rel="_del" />
				</span>
				</div>
				<?php endif;?>

				<!-- file box -->
				<a  target="_blank" href="<?=kleeja_get_link(is_image($file['type']) ? 'image' : 'file', $file)?>" id="su[<?=$file['id']?>]" data-toggle="tooltip" title="<?=$lang['FILEUPS']?>: <?=$file['uploads']?>, <?=$lang['FILESIZE']?>: <?=readable_size($file['size'])?>, <?=$lang['FILEDATE']?>: <?=kleeja_date($file['time'])?>">
			<?php if(is_image($file['type'])):?>
				<div class="thumbnail-cover" style="background-image:url(<?=kleeja_get_link('thumb', $file)?>)">
			<?php else:?>
				<div class="thumbnail-cover" style="background-image:url(images/filetypes/file.png)">
			<?php endif?>
					<div class="capt"><h2><?=shorten_text($file['real_filename'])?></h2></div>
				</div>
				</a>
			</div>



			<?php /* every 4th  */ ?>
			<?php if($loop_number % 4 == 0):?>
			</div>
			<div class="row">
			<?php endif?>


			<?php /* Last Row */ ?>
			<?php if($loop_number == $perpage):?>

			<?php endif?>

	<?php $loop_number++; ?>
	<?php endwhile;?>
</div>




<!-- pagination -->
<?=$page_nums?>
<!-- @end-pagination -->
<div class="clearfix"></div>

<?php if($user_himself):?>


<button type="submit" name="submit_files" class="pull-left btn btn-default"><?=$lang['DEL_SELECTED']?></button>

<a href="javascript:void(0);" class="pull-right btn btn-primary" onclick="checkAll(document.c, '_del', 'su');"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> <?=$lang['CHECK_ALL']?></a>
<div class="clearfix"></div>

<?=kleeja_add_form_key('fileuser')?>

</form>
<?php endif;?>
<!-- end#fileuser_files-->


<!-- link user -->
<br>
<br>
<div class="panel panel-default">
  <div class="panel-heading copylink"><?=$lang['COPY_AND_GET_DUD']?></div>
  <div class="panel-body">
	<strong onclick="this.select();"><?=$your_fileuser_link?></strong>
  </div>
</div>
<!-- @end-link-user -->
<?php endif;?>



<?php if($user_himself):?>
<script type="text/javascript">
<!--
	function confirm_from()
	{
		if(confirm('<?=$lang['ARE_YOU_SURE_DO_THIS']?>'))
			return true;
		else
			return false;
	}

	function checkAll(form, id, _do_c_, c, c2) {
	    for (var i = 0; i < form.elements.length; i++) {
	        if (form.elements[i].getAttribute("rel") != id) continue;
	        if (form.elements[i].checked) {
	            uncheckAll(form, id, _do_c_, c, c2);
	            break
	        }
	        form.elements[i].checked = true;	    }
	}

	function uncheckAll(form, id, _do_c_, c, c2) {
	    for (var i = 0; i < form.elements.length; i++) {
	        if (form.elements[i].getAttribute("rel") != id) continue;
	        form.elements[i].checked = false;
	    }
	}
//-->
</script>
<?php endif; ?>

<!-- @end-Users-Files -->
