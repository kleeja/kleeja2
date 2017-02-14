<?php if(!defined('IN_KLEEJA')) { exit; } ?>
<!-- Password Recovery Template -->


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


<!-- form -->
<form action="<?=$action?>" method="post" class="form-horizontal">

	<div class="form-group<?=(isset($ERRORS['rmail']) ? ' has-error':'')?>">
        <label for="rmail" class="col-sm-2 control-label"><?=$lang['EMAIL']?></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="rmail" id="rmail" placeholder="<?=$lang['EMAIL']?>" value="<?=$t_rmail?>">
			<span id="helpBlock" class="help-block"><?=$lang['E_GET_LOSTPASS']?></span>

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
        <button type="submit" name="submit" class="btn btn-default"><?=$lang['GET_LOSTPASS']?></button>
        <?=kleeja_add_form_key('get_pass')?>
      </div>
    </div>

</form>
<!-- @end-form -->

<!-- @end-Password-Recovery-Template -->
