<?php if(!defined('IN_KLEEJA')) { exit; } ?>
<!-- register template -->


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

<!-- form register -->
<form action="<?=$action?>" method="post" class="form-horizontal">

	<div class="form-group<?=(isset($ERRORS['lname']) ? ' has-error':'')?>">
		<label for="lname" class="col-sm-2 control-label"><?=$lang['USERNAME']?></label>
		<div class="col-sm-10">
			<input type="text" class="form-control" name="lname" id="lname" placeholder="<?=$lang['USERNAME']?>" value="<?=$t_lname?>">
		</div>
	</div>


	<div class="form-group<?=(isset($ERRORS['lpass']) ? ' has-error':'')?>">
        <label for="lname" class="col-sm-2 control-label"><?=$lang['PASSWORD']?></label>
        <div class="col-sm-10">
            <input type="password" class="form-control" name="lpass" id="lpass" placeholder="<?=$lang['PASSWORD']?>" value="<?=$t_lpass?>">
        </div>
    </div>
	<div class="form-group<?=(isset($ERRORS['lpass']) ? ' has-error':'')?>">
        <label for="lname" class="col-sm-2 control-label"><?=$lang['REPEAT_PASS']?></label>
        <div class="col-sm-10">
            <input type="password" class="form-control" name="lpass" id="lpass" placeholder="<?=$lang['REPEAT_PASS']?>" value="<?=$t_lpass2?>">
        </div>
    </div>

	<div class="form-group<?=(isset($ERRORS['lmail']) ? ' has-error':'')?>">
        <label for="lmail" class="col-sm-2 control-label"><?=$lang['EMAIL']?></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="lmail" id="lmail" placeholder="<?=$lang['EMAIL']?>" value="<?=$t_lmail?>">
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
        <button type="submit" name="submit" class="btn btn-default"><?=$lang['REGISTER']?></button>
        <?=kleeja_add_form_key('register')?>
      </div>
    </div>

</form>
<!-- @end-form-register -->



<!-- @end-register-template -->
