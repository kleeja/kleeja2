<?php if(!defined('IN_KLEEJA')) { exit; } ?>

<!-- title -->
<div class="page-header">
  <h1><?=$current_title?></h1>
</div>


<ul class="list-group">
	<li class="list-group-item">
	  	<h4 class="list-group-item-heading"><?=$lang['FILES_ST']?></h4>
		<p class="list-group-item-text"><?=$files_st?> <?=$lang['FILE']?> <?=$lang['AND']?> <?=$imgs_st?> <?=$lang['IMAGE']?></p>
	</li>
	<li class="list-group-item">
		<h4 class="list-group-item-heading"><?=$lang['USERS_ST']?></h4>
		<p class="list-group-item-text"><?=$users_st?> <?=$lang['USER']?></p>
	</li>
	<li class="list-group-item">
		<h4 class="list-group-item-heading"><?=$lang['SIZES_ST']?></h4>
		<p class="list-group-item-text"><?=$sizes_st?></p>
	</li>
<?php if($config['allow_online']):?>
	<li class="list-group-item">
		<h4 class="list-group-item-heading"><?=$lang['MOST_EVER_ONLINE']?></h4>
		<p class="list-group-item-text"><?=$most_online?>  <?=$lang['ON']?> <?=$on_muoe?></p>
	</li>
<?php endif;?>
</ul>


<ins><?=$lang['LAST_1_H']?></ins>
