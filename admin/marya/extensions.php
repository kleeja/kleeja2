

<h3><?=$lang['SELECT_GRP_CHNG_EXTS']?></h3>
<hr>
    <div class="row groups-list">
	<?php foreach($all_groups as $id=>$group): ?>
        <div class="col-xs-12 col-md-4">
            <div class="panel adm-groups <?php if($group['id']==1):?>panel-danger<?php elseif($group['id']==2):?>panel-warning<?php elseif($group['id']==3):?>panel-info<?php else:?>panel-default<?php endif;?>">
                <div class="panel-heading panel-clickable" onclick="location.href='<?=$group['exts_url']?>';">
                    <h1 class="panel-title text-center"><?=$group['name']?></h1>
                </div>
            </div>
        </div>
	<?php endforeach;?>
</div>
