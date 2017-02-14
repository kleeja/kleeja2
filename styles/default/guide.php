<?php if(!defined('IN_KLEEJA')) { exit; } ?>


<!-- title -->
<div class="page-header">
  <h1><?=$current_title?></h1>
</div>


<!-- groups of files allowed -->
<?php foreach($guide_exts as $group_id=>$group_data):?>
<div class="panel panel-default">
  <div class="panel-heading"><?=$group_data['group_name']?></div>
  <div class="panel-body">

				<table class="table table-striped ">
				<thead>
				<tr>
					<th><?=$lang['EXT']?></th>
					<th><?=$lang['SIZE']?></th>
				</tr>
				</thead>
				<!-- group list files -->
				<?php foreach($group_data['exts'] as $ext=>$size):?>
					<tr>
						<td class="guide_ext_cell"><?=$ext?></td>
						<td class="guide_size_cell"><?=readable_size($size)?></td>
					</tr>
				<?php endforeach;?>
				</table>
  </div>
</div>
<?php endforeach;?>
