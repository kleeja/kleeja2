<!-- the big box begin -->
<div class="big-box">
<div class="tit_con">
	<h1><?=$lang['R_BAN']?></h1>
</div>

<p class="lead"><?=$lang['BAN_EXP1']?></p>


<div class="table-responsive">
<table class="table table-striped">
<thead style="font-size:11px">
	<tr>
		<th>ban</th>
		<th>value</th>
		<th><?=$lang['DELETE']?></th>
	</tr>

</thead>
<tbody>
	<?php foreach($ban_list as $id=>$ban):?>
	<tr>
		<td><?=$ban['filter_status'] == 'ip' ? $lang['IP'] : $lang['USERNAME']?></td>
		<td><?=$ban['filter_value']?></td>
		<td><a href="<?=$action?>&amp;bd=<?=$ban['filter_id']?>&amp;<?=$GET_FORM_KEY?>"><?=$lang['DELETE']?></a></td>
	</tr>
	<?php endforeach;?>
</tbody>
</table>
</div>




<!-- add new ban begin -->
<hr>
<div id="newban">
<h3><?=$lang['ADD_NEW_BAN']?></h3>
<?php if(sizeof($ERRORS)):?>
<div class="alert alert-danger">
<?php foreach ($ERRORS as $key => $value):?>
<?=$value?>
<?php endforeach;?>
</div>
<?php endif;?>
<form method="post" action="<?=$action?>" id="add_group_form" class="form-inline" role="form">
<div class="form-group">
	<input type="text" class="form-control" name="ban_value" id="ban_value" placeholder="<?=$lang['IP']?>/<?=$lang['USERNAME']?>">
</div>
<div class="form-group">
	<select name="ban_type" id="ban_type" class="form-control">
		<option value="ip"><?=$lang['IP']?></option>
		<option value="username"><?=$lang['USERNAME']?></option>
	</select>
</div>
<div class="form-group">
	<button type="submit" name="new_ban_submit" class="btn btn-primary"><?=$lang['SUBMIT']?></button>
</div>
<?=$H_FORM_KEYS?>
</form>
<small class="text-muted"><?=$lang['BAN_EXP2']?></small>

<!-- add new ban end -->
</div>
<!-- note -->
