<?php if(!defined('IN_KLEEJA')) { exit; } ?>
<!-- Report template -->

<!--  title -->
<div class="page-header">
  <h1><?=$current_title?></h1>
</div>


<!-- msg, Infos & Alerts & Errors -->
<?php if($ERRORS):?>
	<div class="alert alert-danger">
		<ul>
			<?php foreach($ERRORS as $n=>$error):?>
			<li> <strong><?=$lang['INFORMATION']?> </strong> <?=$error?></li>
			<?php endforeach;?>
		</ul>
	</div>
<?php endif;?>
<!-- @end-msg -->

<!-- Report Forom -->
<form action="<?=$action?>" method="post" class="form-horizontal">

	<?php if(!$user->is_user()):?>
		<div class="form-group<?=(isset($ERRORS['rname']) ? ' has-error':'')?>">
			<label for="rname" class="col-sm-2 control-label"><?=$lang['YOURNAME']?></label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="rname" id="rname" placeholder="<?=$lang['YOURNAME']?>" value="<?=$t_rname?>">
			</div>
		</div>

		<div class="form-group<?=(isset($ERRORS['rmail']) ? ' has-error':'')?>">
			<label for="rmail" class="col-sm-2 control-label"><?=$lang['EMAIL']?></label>
			<div class="col-sm-10">
				<input type="text" class="form-control" name="rmail" id="rmail" placeholder="<?=$lang['EMAIL']?>" value="<?=$t_rmail?>">
			</div>
		</div>

	<?php endif?>

		<?php if($id_d):?>
			<div class="form-group<?=(isset($ERRORS['rurl']) ? ' has-error':'')?>">
				<label for="rurl" class="col-sm-2 control-label"><?=$lang['FILENAME']?></label>
				<div class="col-sm-10">
					<input type="text"  style="direction:ltr"  readonly="readonly" class="form-control" name="rurl" id="rurl" placeholder="<?=$lang['FILENAME']?>" value="<?=$filename_for_show?>">
				</div>
			</div>
		<?php else:?>
			<div class="form-group<?=(isset($ERRORS['surl']) ? ' has-error':'')?>">
				<label for="surl" class="col-sm-2 control-label"><?=$lang['URL_F_FILE']?></label>
				<div class="col-sm-10">
					<input type="text"  style="direction:ltr"  class="form-control" name="surl" id="surl" placeholder="<?=$lang['URL_F_FILE']?>" value="<?=isset($t_surl) ? $t_surl : ''?>">
				</div>
			</div>
		<?php endif?>

		<div class="form-group<?=(isset($ERRORS['rtext']) ? ' has-error':'')?>">
	        <label for="rtext" class="col-sm-2 control-label"><?=$lang['REASON']?></label>
	        <div class="col-sm-10">
				<textarea name="rtext" class="text_area" rows="3" cols="42" class="form-control"><?=$t_rtext?></textarea>
	        </div>
	    </div>


	<!-- verification code -->
	<?php if($config['enable_captcha']):?>
	<div class="form-group<?=(isset($ERRORS['captcha']) ? ' has-error':'')?>">
	    <label for="kleeja_code_answer" class="col-sm-2 control-label"><?=$lang['VERTY_CODE']?></label>
	    <div class="col-sm-10">
			<img style="vertical-align:middle;" id="kleeja_img_captcha" src="<?=$captcha_file_path?>" alt="<?=$lang['REFRESH_CAPTCHA']?>" title="<?=$lang['REFRESH_CAPTCHA']?>" onclick="javascript:update_kleeja_captcha('<?=$captcha_file_path?>', 'kleeja_code_answer');" />
			<input type="text" name="kleeja_code_answer" id="kleeja_code_answer" />
			<span id="helpBlock" class="help-block"><?=$lang['NOTE_CODE']?></span>
         </div>
     </div>
	<?php endif;?>
	<!-- @end-verification-code -->

	<div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" name="submit" class="btn btn-default"><?=$lang['REPORT']?></button>
        <?=kleeja_add_form_key('report')?>
		<input name="rid" value="<?=$id_d?>" type="hidden" />
      </div>
    </div>

</form>
<!-- @end-Report-Forom -->


<!-- @end-Report-template -->
