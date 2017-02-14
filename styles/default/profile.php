<?php if(!defined('IN_KLEEJA')) { exit; } ?>
<!-- Profile template -->

<!--  title -->
<div class="page-header">
  <h1><?=$current_title?> <small><?=$lang['EDIT_U_DATA']?></small></h1>
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
<form action="<?=get_url_of('profile')?>" method="post" class="form-horizontal">
	<div class="panel panel-default">
	  <div class="panel-body">
			<div class="form-group">
				<label class="col-sm-2 control-label"><?=$lang['USERNAME']?></label>
				<div class="col-sm-10">
					<p class="form-control-static"><?=$user->data['name']?></p>
				</div>
			</div>

			<div class="form-group<?=(isset($ERRORS['show_my_filecp']) ? ' has-error':'')?>">
				<label for="lname" class="col-sm-2 control-label"><?=$lang['SHOW_MY_FILECP']?></label>
				<div class="col-sm-10">
					<select name="show_my_filecp" class="form-control">
						<option value="1"<?php if($user->data['show_my_filecp'] == 1):?> selected="selected"<?php endif;?>><?=$lang['YES']?></option>
						<option value="0"<?php if($user->data['show_my_filecp'] == 0):?> selected="selected"<?php endif;?>><?=$lang['NO']?></option>
					</select>
				</div>
			</div>
		</div>
	</div>

	<div class="panel panel-default">
	  <div class="panel-heading"><?=$lang['EMAIL']?></div>
	  <div class="panel-body">
			<div class="form-group<?=(isset($ERRORS['pmail']) ? ' has-error':'')?>">
				<label for="pmail" class="col-sm-2 control-label"><?=$lang['EMAIL']?></label>
				<div class="col-sm-10">
					<input type="text" class="form-control" name="pmail" id="pmail" placeholder="<?=$lang['EMAIL']?>" value="<?=$t_pmail?>">
				</div>
			</div>

			<div class="form-group<?=(isset($ERRORS['pppass_old']) ? ' has-error':'')?>">
		        <label for="pppass_old" class="col-sm-2 control-label"><?=$lang['PASSWORD']?></label>
		        <div class="col-sm-10">
		            <input type="password" class="form-control" name="pppass_old" id="pppass_old" placeholder="<?=$lang['PASSWORD']?>" value="<?=$t_pppass_old?>">
		        </div>
		    </div>
    </div>
	</div>

	<div class="panel panel-default">
	  <div class="panel-heading"><?=$lang['PASS_ON_CHANGE']?></div>
	  <div class="panel-body">
		  <div class="form-group<?=(isset($ERRORS['ppass_old']) ? ' has-error':'')?>">
			  <label for="ppass_old" class="col-sm-2 control-label"><?=$lang['OLD']?></label>
			  <div class="col-sm-10">
				  <input type="password" class="form-control" name="ppass_old" id="ppass_old" placeholder="<?=$lang['OLD']?>" value="<?=$t_ppass_old?>">
			  </div>
		  </div>
		  <div class="form-group<?=(isset($ERRORS['ppass_new']) ? ' has-error':'')?>">
			  <label for="pppass_old" class="col-sm-2 control-label"><?=$lang['NEW']?></label>
			  <div class="col-sm-10">
				  <input type="password" class="form-control" name="ppass_new" id="ppass_new" placeholder="<?=$lang['NEW']?>" value="<?=$t_ppass_new?>">
			  </div>
		  </div>
		  <div class="form-group<?=(isset($ERRORS['ppass_new']) ? ' has-error':'')?>">
			  <label for="ppass_new2" class="col-sm-2 control-label"><?=$lang['NEW_AGAIN']?></label>
			  <div class="col-sm-10">
				  <input type="password" class="form-control" name="ppass_new2" id="ppass_new2" placeholder="<?=$lang['NEW_AGAIN']?>" value="<?=$t_ppass_new2?>">
			  </div>
		  </div>
	  </div>
	</div>




	<div class="form-group">
	  <div class="col-sm-offset-2 col-sm-10">
		<button type="submit" name="submit_data" class="btn btn-default"><?=$lang['EDIT_U_DATA']?></button>
		<?=kleeja_add_form_key('profile')?>
	  </div>
	</div>


</form>
<!-- @end-form -->

<!-- @end-Profile-template -->
