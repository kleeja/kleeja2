
<div class="page-header">
 <h1><?=$lang['R_MAINTENANCE']?></h1>
</div>


<div class="section">
	<h3><?=$lang['DEL_CACHE']?></h3>
	<button class="btn btn-default " onclick="javascript:get_kleeja_link('<?=$del_cache_link?>'); return false;"><?=$lang['DELETE']?></button>
</div>



<hr>
<div class="alert alert-info">
	<?=$lang['WHY_SYNCING']?>
</div>

<div class="clear"></div>


<ul class="list-group">
  <li class="list-group-item">
    <span class="badge"><?=$all_files?></span>
    <?=$lang['ALL_FILES']?> -
	<a class="btn btn-default btn-xs" href="<?=$resync_files_link?>"><?=$lang['RESYNC']?></a>
  </li>

    <li class="list-group-item">
      <span class="badge"><?=$all_images?></span>
      <?=$lang['ALL_IMAGES']?> -
  	<a class="btn btn-default btn-xs" href="<?=$resync_images_link?>"><?=$lang['RESYNC']?></a>
    </li>

    <li class="list-group-item">
      <span class="badge"><?=$all_users?></span>
      <?=$lang['USERS_ST']?> -
  	<a class="btn btn-default btn-xs" href="<?=$resync_users_link?>"><?=$lang['RESYNC']?></a>
    </li>

    <li class="list-group-item">
      <span class="badge"><?=$all_sizes?></span>
      <?=$lang['SIZES_ST']?>
     <a class="btn btn-default btn-xs" href="<?=$resync_sizes_link?>"><?=$lang['RESYNC']?></a>
    </li>


    <li class="list-group-item">
     <?=$lang['REPAIR_DB_TABLES']?> -
		<a class="btn btn-default btn-xs" href="<?=$repair_tables_link?>"><?=$lang['SUBMIT']?></a>
    </li>


    <li class="list-group-item">
     <?=$lang['SUPPORT_TXT_FILE']?> -
		<a class="btn btn-default btn-xs" href="<?=$status_file_link?>"><?=$lang['DOWNLAOD']?></a>
    </li>

</ul>
