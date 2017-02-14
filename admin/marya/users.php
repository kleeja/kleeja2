
<?php if($current_smt == 'general'):?>

<!-- groups begin -->
<div class="page-header">
	<h1><?=$lang['R_GROUPS']?></h1>
</div>



<!-- if not normal user system, tell him -->
<?php if($user_not_normal): ?>
	<div class="alert alert-info"><?=$lang['USERS_NOT_NORMAL_SYS']?></div>
	<hr>
<?php endif; ?>


<!-- start data table -->
<?php if($no_results): ?>
...
<?php else: ?>
<h3><?=$lang['ESSENTIAL_GROUPS']?></h3>
    <div class="row groups-list">
	<?php foreach($e_groups as $id=>$group): ?>
        <div class="col-xs-12 col-md-4">
            <div class="panel adm-groups <?php if($group['id']==1):?>panel-danger<?php elseif($group['id']==2):?>panel-warning<?php else:?>panel-info<?php endif;?>">
                <div class="panel-heading">
                    <h1 class="panel-title text-center"><?=$group['name']?></h1>
                </div>
                <ul class="list-group">
    			    <li class="list-group-item alist-link" onclick="javascript:location.href='<?=$action_start?>&smt=group_data&qg=<?=$group['id']?>';"><span class="glyphicon glyphicon-cog"></span> <?=$lang['EDIT_DATA']?></li>
				    <li class="list-group-item alist-link" onclick="javascript:location.href='<?=$action_start?>&smt=group_exts&qg=<?=$group['id']?>';"><span class="glyphicon glyphicon-list-alt"></span> <?=$lang['R_EXTENSIONS']?></li>
				    <li class="list-group-item alist-link" onclick="javascript:location.href='<?=$action_start?>&smt=group_acl&qg=<?=$group['id']?>';"><span class="glyphicon glyphicon-lock"></span> <?=$lang['EDIT_ACL']?></li>
					<?php if(!$user_not_normal && $id != 1): ?>
				    <li class="list-group-item alist-link" onclick="javascript:location.href='<?=$action_start?>&smt=show_group&qg=<?=$group['id']?>';"><span class="glyphicon glyphicon-user"></span> <?=$lang['USERS']?></li>
				 	<?php endif; ?>
					<?php if(!$user_not_normal && $group['is_default']): ?><li class="list-group-item text-muted"><small>(<?=$lang['GROUP_IS_DEFAULT']?>)</small></li><?php endif; ?>
				</ul>
            </div>
        </div>
	<?php endforeach;?>
</div>

<br>
<?php if($c_groups): ?>
<h3><?=$lang['CUSTOM_GROUPS']?></h3>
    <div class="row groups-list">
	<?php foreach($c_groups as $id=>$group): ?>
        <div class="col-xs-12 col-md-4">
            <div class="panel adm-groups panel-default">
                <div class="panel-heading">
                    <h1 class="panel-title text-center"><?=$group['name']?></h1>
                </div>
                <ul class="list-group">
    			    <li class="list-group-item alist-link" onclick="javascript:location.href='<?=$action_start?>&smt=group_data&qg=<?=$group['id']?>';"><span class="glyphicon glyphicon-cog"></span> <?=$lang['EDIT_DATA']?></li>
				    <li class="list-group-item alist-link" onclick="javascript:location.href='<?=$action_start?>&smt=group_exts&qg=<?=$group['id']?>';"><span class="glyphicon glyphicon-list-alt"></span> <?=$lang['R_EXTENSIONS']?></li>
				    <li class="list-group-item alist-link" onclick="javascript:location.href='<?=$action_start?>&smt=group_acl&qg=<?=$group['id']?>';"><span class="glyphicon glyphicon-lock"></span> <?=$lang['EDIT_ACL']?></li>
				    <li class="list-group-item alist-link" onclick="javascript:location.href='<?=$action_start?>&smt=show_group&qg=<?=$group['id']?>';"><span class="glyphicon glyphicon-user"></span> <?=$lang['USERS']?></li>
				    <li class="list-group-item alist-link del-usergroup" data-toggle="popover" data-title="<?=$lang['DELETE']?>: <?=$group['name']?>" data-id2del="<?=$group['id']?>"><span class="glyphicon glyphicon-trash"></span> <?=$lang['DELETE']?></li>
					<!-- #delete_group_<?=$group['id']?> -->
					<?php if(!$user_not_normal && $group['is_default']): ?><li class="list-group-item text-muted"><small>(<?=$lang['GROUP_IS_DEFAULT']?>)</small></li><?php endif; ?>
				</ul>
            </div>
        </div>
	<?php endforeach;?>
</div>

<?php endif; #/if c_groups ?>

<?php endif; #/if results ?>
<!-- end data table -->



<!-- addnew g begin -->
<hr>
<div id="group_add_new">
<h3><?=$lang['ADD_NEW_GROUP']?></h3>
<form method="post" action="<?=$action?>" id="add_group_form" class="form-inline" role="form">
<div class="form-group">
	<label class="" for="groupe_name"><?=$lang['GROUP_NAME']?></label>
	<input type="text" class="form-control" name="gname" id="groupe_name" placeholder="<?=$lang['GROUP_NAME']?>">
</div>
<div class="form-group">
	<label for="cfrom" class=""><?=$lang['COPY_FROM']?></label>
	<select name="cfrom" id="cfrom" class="form-control">
		<option value="-1"><?=$lang['DEFAULT_GROUP']?></option>
		<?php foreach($e_groups as $id=>$group): ?><?php if($id != 2): ?><option value="<?=$group['id']?>"><?=$group['name']?></option><?php endif; ?><?php endforeach;?>
		<?php foreach($c_groups as $id=>$group): ?><option value="<?=$group['id']?>"><?=$group['name']?></option><?php endforeach;?>
	</select>
</div>
<div class="form-group">
	<input type="hidden" name="newgroup" value="1">
	<button type="submit" id="submit" name="newgroup" class="btn btn-primary" onclick="javascript:submit_kleeja_data('#add_group_form', '#content', 0);" ><?=$lang['SUBMIT']?></button>
</div>
<?=$H_FORM_KEYS3?>
</form>
<!-- addnew g end -->
</div>



<!--  delete g begin -->
<?php foreach($c_groups as $id=>$group): ?>
<div id="delete_group_<?=$group['id']?>" style="display:none">
<form method="post" action="<?=$action?>" id="del_group_form" role="form">
<input type="hidden" name="dgroup" id="dgroup" value="<?=$group['id']?>"  />
<div class="form-group">
	<label for="tgroup"><?=$lang['G_USERS_MOVE_TO']?></label>
		<select name="tgroup" id="tgroup" class="form-control">
			<?php if($group['is_default']): ?><?php else: ?><option value="-1"><?=$lang['DEFAULT_GROUP']?></option><?php endif; ?>
			<?php foreach($e_groups as $id=>$group): ?><?php if($id != 1): ?><option value="<?=$group['id']?>"><?=$group['name']?></option><?php endif; ?><?php endforeach;?>
			<?php foreach($c_groups as $id=>$group): ?><option value="<?=$group['id']?>"><?=$group['name']?></option><?php endforeach;?>
		</select>
</div>
<div class="clear"></div>
<div class="br"></div>
<!-- button -->
<p>
	<input type="hidden" name="delgroup" value="1" />
	<button type="submit" id="submit" name="delgroup" class="btn btn-primary"  onclick="javascript:$.facybox.close(); submit_kleeja_data('#del_group_form', '#content', 0); return false;"><span><?=$lang['SUBMIT']?></span></button>
</p>
<?=$H_FORM_KEYS4?>
</form>
</div>
<?php endforeach;?>
<!-- delete g end -->





<?php elseif($show_results): ?>

<!-- show results -->

<div class="page-header">
 <h1><a href="<?=$action_start?>&amp;smt=general" onclick="javascript:location.href=this.href;"><?=$lang['R_GROUPS']?></a> <small>
	 <?php if($is_search): ?> &raquo; <?php if($current_smt == 'show_group'):?><?=$group_name?><?php else: ?><?=$lang['SEARCH_USERS']?><?php endif; ?> ( <?=$nums_rows?> )<?php endif; ?></small></h1>
</div>

<!-- if not normal user system, notify the user -->
<?php if($user_not_normal): ?>
<div class="alert alert-info"><?=$lang['USERS_NOT_NORMAL_SYS']?></div>
<?php endif; ?>

<?php if($GE_INFO): ?>
	<script type="text/javascript">
		setTimeout(function() {
			$('.infoexts').fadeOut('fast');
		}, 5000);
	</script>
	<div class="infoexts">
	<div class="alert alert-info"><?=$GE_INFO?></div>
	</div>
<?php endif; ?>

<?php if($no_results):?>
<div class="alert alert-warning">...</div>
<?php endif;?>

<?php if($arr): ?>
<div class="table-responsive">
<table class="table table-striped table-bordered table-hover">
	<tr>
		<th><?=$lang['USERNAME']?></th>
		<th><?=$lang['INFORMATION']?></th>
		<th>~</th>
		<!-- userresult-th -->
	</tr>
<?php foreach($arr as $id=>$group): ?>
<tr>
	<td><?=$group['name']?><?php if($group['founder']==1):?> <span title="<?=$lang['FOUNDER']?>" class="glyphicon glyphicon-ok"></span><?php endif; ?></td>
	<td><span class="text-muted"><?=$lang['GROUP']?>:</span> <?=$group['group']?><br> <span class="text-muted"><?=$lang['LAST_VISIT']?>:</span> <?=$group['last_visit']?></td>
	<td>
		<div class="btn-group " role="group" aria-label="">
			<a href="<?=$group['editusr_link']?>" onclick="javascript:location.href=this.href;" title="<?=$lang['EDIT']?>" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></a>
			<a target="_blank" href="<?=$group['userfile_link']?>" title="<?=$lang['BROSWERF']?>" class="btn btn-info"><span class="glyphicon glyphicon-folder-open"></span></a>


		  <div class="btn-group" role="group">
		    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		      <?=$lang['DELETE']?>
		      <span class="caret"></span>
		    </button>
		    <ul class="dropdown-menu">
		      <li><a href="<?=$group['delusrfile_link']?>&amp;<?=$GET_FORM_KEY?>" onclick="javascript:return confirm_from();" title="<?=$lang['ADMIN_DELETE_FILES']?>"><span class="glyphicon glyphicon-floppy-remove"></span> <?=$lang['ADMIN_DELETE_FILES']?></a></li>
		      <?php if($group['delusr_link']): ?><li><a href="<?=$group['delusr_link']?>&amp;<?=$GET_FORM_KEY?>" onclick="javascript:return confirm_from();" title=""><span class="glyphicon glyphicon-trash"></span> <?=$lang['DELETE']?></a></li><?php endif; ?>
		    </ul>
		  </div>
		</div>

	</td>
	<!-- userresult-td -->
</tr>
<?php endforeach;?>
</table>
</div>
<?php endif; ?>

<!-- end data table -->

<!-- pagination -->
<?=$page_nums?>

<!-- show results end -->

<!-- add new user -->
<?php elseif($current_smt == 'new_u'):?>

<div class="page-header">
<h1><a href="<?=$action?>&amp;smt=general" onclick="javascript:location.href=this.href;"><?=$lang['R_GROUPS']?></a>
	<small> &raquo; <?=$lang['NEW_USER']?></small></h1>
</div>


<?php if($errs): ?>
<div class="alert alert-danger"><?=$errs?></div>
<?php endif; ?>

<form method="post" action="<?=$action?>" id="add_user_form" role="form">
<div class="form-group">
	<label for="lname"><?=$lang['USERNAME']?></label>
	<input type="text" name="lname" id="lname" value="<?=$uname?>" size="30" class="form-control" placeholder="<?=$lang['USERNAME']?>">
</div>
<div class="form-group">
	<label for="lmail"><?=$lang['EMAIL']?></label>
	<input type="text" name="lmail"  id="lmail" value="<?=$umail?>"size="30" class="form-control" placeholder="<?=$lang['EMAIL']?>">
</div>
<div class="form-group">
	<label for="lpass"><?=$lang['PASSWORD']?></label>
	<input type="password" name="lpass" id="lpass" value=""size="30" class="form-control" placeholder="<?=$lang['PASSWORD']?>">
</div>

<!-- newuser-newfield -->

<div class="form-group">
	<label for="lpass"><?=$lang['GROUP']?></label>
		<select name="lgroup" id="lgroup" class="form-control" style="width: 237px;">
		<?php foreach($u_groups as $id=>$group): ?>
			<option value="<?=$group['id']?>"<?php if($group['selected']): ?> selected="selected"<?php endif; ?>><?=$group['name']?> <?php if($group['default']): ?>(<?=$lang['DEFAULT_GROUP']?>)<?php endif; ?></option>
		<?php endforeach;?>
		</select>
</div>

<!-- button -->
<p class="submit <?php if($lang['DIR'] == 'rtl'):?>pull-left<?php endif; ?>">
	<input type="hidden" name="newuser" value="1" />
	<button type="submit" id="submit" name="newuser" class="btn btn-primary" onclick="javascript:submit_kleeja_data('#add_user_form', '#content', 0);"><?=$lang['SUBMIT']?></button>
</p>

<?=$H_FORM_KEYS2?>
</form>
</div>
<!-- the big box end -->

<!-- edit a user -->
<?php elseif($current_smt == 'edit_user'):?>


<div class="page-header">
<h1><a href="<?=$action?>&amp;smt=general" onclick="javascript:location.href=this.href;"><?=$lang['R_GROUPS']?></a>
	<small> &raquo; <?=$lang['EDIT']?> (<?=$title_name?>)</small></h1>
</div>

<?php if($errs): ?>
<div class="alert alert-danger"><?=$errs?></div>
<?php endif; ?>

<form method="post" action="<?=$action?>&amp;uid=<?=$userid?>&amp;qg=<?=$u_qg?>&amp;page=<?=$u_page?>" id="edit_user_form" role="form">
<div class="form-group">
	<label for="l_name"><?=$lang['USERNAME']?></label>
	<input type="text" name="l_name" id="l_name" value="<?=$u_name?>" size="30" class="form-control">
</div>
<div class="form-group">
	<label for="l_mail"><?=$lang['EMAIL']?></label>
	<input type="text" name="l_mail"  id="l_mail" value="<?=$u_mail?>"size="30" class="form-control">
</div>
<div class="form-group">
	<label for="l_pass"><?=$lang['PASS_ON_CHANGE']?></label>
	<input type="password" name="l_pass" id="l_pass" value=""size="30" class="form-control"/>
</div>
<div class="form-group">
	<label for="l_show_filecp"><?=$lang['SHOW_MY_FILECP']?></label>
	<select name="l_show_filecp" id="l_show_filecp" class="form-control">
			<option value="1"<?php if($u_show_filecp): ?> selected="selected"<?php endif; ?>><?=$lang['YES']?></option>
			<option value="0"<?php if($u_show_filecp): ?><?php else: ?> selected="selected"<?php endif; ?>><?=$lang['NO']?></option>
	</select>
</div>
<div class="form-group">
	<label for="l_group"><?=$lang['GROUP']?></label>
	<select name="l_group" id="l_group" class="form-control">
	<?php foreach($u_groups as $id=>$group): ?>
	<?php if($id != 2): ?>
		<option value="<?=$group['id']?>"<?php if($group['selected']): ?> selected="selected"<?php endif; ?>><?=$group['name']?> <?php if($group['default']): ?>(<?=$lang['DEFAULT_GROUP']?>)<?php endif; ?></option>
	<?php endif; ?>
	<?php endforeach;?>
	</select>
</div>

<!-- edituser-newfield -->

<?php if($im_founder): ?>
	<div class="form-group">
		<label for="l_founder"><?=$lang['FOUNDER']?></label>
		<select name="l_founder" id="l_founder" class="form-control">
			<option value="1"<?php if($u_founder): ?> selected="selected"<?php endif; ?>><?=$lang['YES']?></option>
			<option value="0"<?php if($u_founder): ?><?php else: ?> selected="selected"<?php endif; ?>><?=$lang['NO']?></option>
		</select>
</div>
<?php endif; ?>


<input type="text" style="display:none;" name="l_qg" class="btn btn-primary" value="<?=$u_qg?>" />
<input type="text" style="display:none;" name="l_page" class="btn btn-primary" value="<?=$u_page?>" />
<input type="text" style="display:none;" name="uid" class="btn btn-primary" value="<?=$userid?>" />

<!-- button -->
<p class="submit <?php if($lang['DIR'] == 'rtl'):?>pull-left<?php endif; ?>">
	<input type="hidden" name="edituser" value="1" />
	<button type="submit" id="submit" name="edituser" class="btn btn-primary" onclick="javascript:submit_kleeja_data('#edit_user_form', '#content', 0);"><?=$lang['SUBMIT']?></button>
</p>

<?=$H_FORM_KEYS8?>
</form>

<!-- g acls -->
<?php elseif($current_smt == 'group_acl'):?>

<div class="page-header">
<h1><a href="<?=$action_start?>&amp;smt=general" onclick="javascript:location.href=this.href;"><?=$lang['R_GROUPS']?></a>
	<small> &raquo; <?=$lang['EDIT_ACL']?> (<?=$group_name?>)</small></h1>
</div>


<form method="post" action="<?=$action?>" id="edit_acl_form" role="form">
<table class="table table-striped">
<?php foreach($acls as $id=>$group): ?>
<tr>
	<td><h2><?=$group['acl_title']?></h3></td>
	<td class="text-<?php if($lang['DIR'] == 'rtl'):?>left<?php else: ?>right<?php endif; ?>">
		<div class="btn-group acls-radios" data-toggle="buttons">
		<label class="btn btn-primary btn-lg<?php if($group['acl_can'] == 1):?> active<?php endif; ?>">
			<input type="radio" name="<?=$group['acl_name']?>" id="acls_<?=$group['acl_name']?>_1" <?php if($group['acl_can'] == 1):?> checked="checked"<?php endif; ?> value="1"> <?=$lang['HE_CAN']?>
		</label>
		<label class="btn btn-default btn-lg<?php if($group['acl_can'] == 0):?> active<?php endif; ?>">
			<input type="radio" name="<?=$group['acl_name']?>" id="acls_<?=$group['acl_name']?>_2" <?php if($group['acl_can'] == 0):?> checked="checked"<?php endif; ?> value="0"> <?=$lang['HE_CAN_NOT']?>
		</label>
		</div>
	</td>
</tr>
<?php endforeach;?>
</table>

<!-- button -->
<p class="submit <?php if($lang['DIR'] == 'rtl'):?>pull-left<?php endif; ?>">
	<input type="hidden" name="editacl" value="1" />
	<button type="submit" id="submit" name="editacl" class="btn btn-primary" onclick="javascript:submit_kleeja_data('#edit_acl_form', '#content', 0);"><?=$lang['SUBMIT']?></button>
</p>

<?=$H_FORM_KEYS5?>
</form>


<?php elseif($current_smt == 'group_exts'):?>

<!-- exts g begins -->
<div class="page-header">
	<h1><a href="<?=$action_start?>&amp;smt=general" onclick="javascript:location.href=this.href;"><?=$lang['R_GROUPS']?></a>
		<small> &raquo; <?=$lang['R_EXTENSIONS']?> (<?=$group_name?>)</small></h1>
		<div class="btn btn-default new-ext-popover <?php if($lang['DIR'] == 'rtl'):?>pull-left<?php else: ?>pull-right<?php endif; ?>" data-toggle="popover" data-title="<?=$lang['ADD_NEW_EXT']?>"><?=$lang['ADD_NEW_EXT']?></div>
		<div class="clearfix"></div>
</div>



<?php if($GE_INFO): ?>
	<script type="text/javascript">
		setTimeout(function() {
			$('.infoexts').fadeOut('fast');
		}, 5000);
	</script>
	<div class="infoexts">
	<?php if($DELETED_EXT): ?>
		<?php if($DELETED_EXT == 2):?>
		<div class="alert alert-info"><?=$lang['EXT_DELETED']?></div>
		<?php else: ?>
		<div class="alert alert-danger"><?=$lang['DATA_CHANGED_NO']?></div>
		<?php endif; ?>
	<?php endif; ?>
	<?php if($ADDED_EXT): ?>
		<?php if($ADDED_EXT == 2):?>
		<div class="alert alert-info"><?=$lang['NEW_EXT_ADD']?></div>
		<?php else: ?>
		<div class="alert alert-danger"><?=$lang['DATA_CHANGED_NO']?></div>
		<?php endif; ?>
	<?php endif; ?>
	</div>
<?php endif; ?>

<form method="post" action="<?=$action?>" id="edit_exts_form" role="form">
<table class="table table-striped">
<?php foreach($exts as $id=>$group): ?>
<tr>
	<th>#</th>
	<th><?=$lang['EXT']?></th>
	<th><?=$lang['SIZE']?></th>
	<th><?=$lang['DELETE']?></th>
</tr>
<tr>
	<td><img src="<?=$group['ext_icon']?>"></td>
	<td><?=$group['ext_name']?></td>
	<td>
		<div class="input-group">
		<input type="text" name="size[<?=$group['ext_id']?>]" value="<?=$group['ext_size']?>" size="6" class="form-control" style="float:left;">
		<span class="input-group-addon"><?=$lang['KILOBYTE']?></span>
	</div>

	</td>
	<td>
		<a href="<?=$action?>&del=<?=$group['ext_id']?>&<?=$GET_FORM_KEY?>"  onclick="javascript:return confirm_from();" title="<?=$lang['DELETE']?>" class="btn btn-danger">
			<span class="glyphicon glyphicon-trash"></span>
		</a>
	</td>
</tr>
<?php endforeach;?>
</table>


<!-- button -->
<p class="submit <?php if($lang['DIR'] == 'rtl'):?>pull-left<?php endif; ?>">
	<input type="hidden" name="editexts" value="1" />
	<button type="submit" id="submit" name="editexts" class="btn btn-primary" onclick="javascript:submit_kleeja_data('#edit_exts_form', '#content', 0);"><span><?=$lang['SUBMIT']?></span></button>
</p>
<?=$H_FORM_KEYS7?>
</form>

<div class="clearfix"></div>

<br>
<div class="alert alert-info">
	<?=$lang['E_EXTS']?> <button class="btn btn-primary btn-sm converter-popover" data-toggle="popover" data-title="<?=$lang['BCONVERTER']?>"><?=$lang['BCONVERTER']?></button>
</div>


<!-- add new ext -->
<div id="new_ext_form" style="display:none;">

<form method="post" action="<?=$action?>" id="add_new_ext" role="form">
	<label><?=$lang['ADD_NEW_EXT_EXP']?></h4>
	<input type="text" name="extisnew" value="" class="form-control"/>
	</label>



<!-- button -->
<p class="submit <?php if($lang['DIR'] == 'rtl'):?>pull-left<?php endif; ?>">
	<input type="hidden" name="newext" value="1" />
	<button type="submit" id="submit" name="newext" class="btn btn-primary" onclick="javascript:submit_kleeja_data('#add_new_ext', '#content', 0);"><span><?=$lang['SUBMIT']?></span></button>
</p>

<?=$H_FORM_KEYS7?>
</form>
</div><hr>
<!-- the big box end -->

<div id="converter_form" style="display:none;">
<!-- converter table -->
<script type="text/javascript">
/*Created by: Uncle Jim :: http://jdstiles.com/javamain.html */

	function convert(f)
	{
	  f.kb.value=Math.round(f.byte.value/1024*100000)/100000
	  f.mb.value=Math.round(f.byte.value/1048576*100000)/100000
	  f.gb.value=Math.round(f.byte.value/1073741824*100000)/100000
	}

	function convertkb(f)
	{
	  f.byte.value=Math.round(f.kb.value*1024*100000)/100000
	  f.mb.value=Math.round(f.kb.value/1024*100000)/100000
	  f.gb.value=Math.round(f.kb.value/1048576*100000)/100000
	}

	function convertmb(f)
	{
	  f.byte.value=Math.round(f.mb.value*1048576*100000)/100000
	  f.kb.value=Math.round(f.mb.value*1024*100000)/100000
	  f.gb.value=Math.round(f.mb.value/1024*100000)/100000
	}

	function convertgb(f)
	{
	  f.byte.value=Math.round(f.gb.value*1073741824*100000)/100000
	  f.kb.value=Math.round(f.gb.value*1048576*100000)/100000
	  f.mb.value=Math.round(f.gb.value*1024*100000)/100000
	}
</script>


<!-- start c table -->
<form class="table table-striped text-center">
<table>
	<tr class="first">
		<td>Byte</td>
		<td>Kilobyte</td>
		<td>Megabyte</td>
		<td>Gigabyte</td>
	</tr>
	<tr>
		<td><input type="text" size="10" name="byte" value="0" class="form-control" /></td>
		<td><input type="text" size="10" name="kb" value="0" class="form-control" /></td>
		<td><input type="text" size="10" name="mb" value="0" class="form-control" /></td>
		<td><input type="text" size="10" name="gb" value="0" class="form-control" /></td>
	</tr>

	<tr>
		<td style="border-width:0px"><button type="button" name="B2" class="btn btn-default" onClick="javascript:convert(this.form)"><span class="bc">  &gt;  </span></button></td>
		<td style="border-width:0px"><button type="button" name="B22" class="btn btn-default" onClick="javascript:convertkb(this.form)"><span class="bc">  &lt; &gt;  </span></button></td>
		<td style="border-width:0px"><button type="button" name="B23" class="btn btn-default" onClick="javascript:convertmb(this.form)"><span class="bc">  &lt; &gt;  </span></td>
		<td style="border-width:0px"><button type="button" name="B24" class="btn btn-default" onClick="javascript:convertgb(this.form)"><span class="bc">  &lt;  </span></td>
	</tr>
</table>
<!-- end c table -->
</form>
</div>



<?php elseif($current_smt == 'group_data'):?>


<!-- data g begins -->
<div class="page-header">
	<h1><a href="<?=$action_start?>&amp;smt=general" onclick="javascript:get_kleeja_link(this.href);"><?=$lang['R_GROUPS']?></a>
		<small> &raquo; <?=$lang['EDIT_DATA']?> (<?=$group_name?>)</small></h1>
</div>



<form method="post" action="<?=$action?>" id="edit_data_form">
<ul class="list-group">
<?php if(!$user_not_normal): ?>
<li class="list-group-item form-group">
	<h4 class="list-group-item-heading"><label for="group_name"><?=$lang['GROUP_NAME']?></label></h4>
	<p class="list-group-item-text">
		<?php if($gdata['group_is_essential']):?>
			<?=$group_name?>
		<?php else: ?>
			<input type="text" name="group_name" id="group_name" value="<?=$gdata['group_name']?>" class="form-control"  placeholder="<?=$lang['GROUP_NAME']?>l">
		<?php endif; ?>
	</p>
</li>

<li class="list-group-item form-group">
	<h4 class="list-group-item-heading"><label for="group_name"><?=$lang['GROUP_IS_DEFAULT']?></label></h4>
	<p class="list-group-item-text">
		<div class="checkbox">
		<label>
			<input type="radio" name="group_is_default" id="group_is_default" value="1" <?php if($gdata['group_is_default'] == 1):?>checked="checked"<?php endif; ?>> <?=$lang['YES']?>
		</label>
		<label>
			<input type="radio" name="group_is_default" id="group_is_default" value="0" <?php if($gdata['group_is_default'] == 0):?>checked="checked"<?php endif; ?>> <?=$lang['NO']?>
		</label>
		</div>
	</p>
</li>

<?php endif; ?>

<?php foreach($data as $id=>$group): ?>
<li class="list-group-item form-group">
		<p class="list-group-item-text">
			<?=$group['option']?>
		</p>
</li>
<?php endforeach;?>
</ul>

<!-- button -->
<p class="submit <?php if($lang['DIR'] == 'rtl'):?>pull-left<?php endif; ?>">
	<input type="hidden" name="editdata" value="1" />
	<button type="submit" id="submit" name="editdata" class="btn btn-primary" onclick="javascript:submit_kleeja_data('#edit_data_form', '#content', 0);"><?=$lang['SUBMIT']?></button>
</p>

<?=$H_FORM_KEYS6?>
</form>


<!-- end cat users -->
<?php endif; ?>
