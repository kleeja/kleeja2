<?php if(!defined('IN_KLEEJA')) { exit; } ?>
<!-- login template -->


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

<!-- form login  -->
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
		<label for="lname" class="col-sm-2 control-label"><?=$lang['REMME']?></label>
		<div class="checkbox col-sm-10">
			<input type="checkbox" name="remme" value="31536000" checked="checked" />
			 <span id="helpBlock" class="help-block"><?=$lang['REMME_EXP']?></span>
		</div>
	</div>

	<div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" name="submit" class="btn btn-default"><?=$lang['LOGIN']?></button>
        <?=kleeja_add_form_key('login')?>
      </div>
    </div>

</form>
<!-- @end-form-login -->

<hr>
<div class="well well-sm text-center">
	<div class="btn-group" role="group" aria-label="">
		<a class="btn btn-default" href="<?=$forget_pass_link?>"><?=$lang['LOSS_PASSWORD']?></a>
		<a class="btn btn-default"  href="<?=$register_link?>"><?=$lang['REGISTER']?></a>
	</div>
</div>



<!-- @end-login-template -->
