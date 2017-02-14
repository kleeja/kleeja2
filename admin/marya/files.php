
<!-- files begin -->
<div class="page-header">
<h1>
  <?=$lang['R_FILES']?>
  <?php if($total_pages > 1):?><small>[<?=$current_page?> / <?=$total_pages?>]</small><?php  endif; ?>
</h1>
</div>


<?php if($is_search): ?>
<p class="lead well text-center">
	<?=$lang['FIND_IP_FILES']?> ( <?=$nums_rows?> ) <?=$lang['FILE']?>
	<?php if($nums_rows): ?>
		 | <a href="<?=$deletelink?>"  class="btn btn-default btn-lg" onclick="javascript:get_kleeja_link(this.href, '#content', {confirm:true});">
			 <span class="glyphicon glyphicon-trash"></span>
			 <?=$lang['DELETEALLRES']?>
		 </a>
	 <?php endif; ?>
</p>
<?php endif; ?>



<form method="post" name="filesform" action="<?=$action?>" id="files_form">

<?php if($no_results): ?>

	<div class="alert alert-info">
	<p class=""><?=$lang['NO_RESULT_USE_SYNC']?></p>
	</div>

<?php else: ?>

<!-- start data table -->
<div class="table-responsive">
<table class="table table-striped">
<thead style="font-size:11px">
	<tr>
		<th><a href="javascript:void(0);" onclick="checkAll(document.filesform, '_del', 'su');" title="<?=$lang['DELETE']?>">#</a></th>
		<th></th>
		<th></th>
		<th class="hidden-xs"></th>
		<!-- admin files data td1 extra -->
</thead>
<tbody>
	<?php foreach($files_list as $id=>$file):?>
	<tr id="su[<?=$id?>]">
		<td style="width:20px;"><input type="checkbox" name="del_<?=$id?>" value="<?=$id?>" onclick="change_color(this,'su[<?=$id?>]');" rel="_del" /></td>
		<td style="width:20px;"><img src="<?=$file['typeicon']?>" alt="<?=$file['type']?>" title="<?=$file['type']?>" /></td>
		<td >
            <span rel="popover" data-title="<?=$file['fullname']?>"><?=$file['name']?></span>
    		<div style="min-width:400px !important;" class="extra_info hidden-lg hidden-md">
    			<div class="img-info-box <?php if($lang['DIR'] == 'rtl'):?>text-right<?php endif; ?>">
    			<span class="hidden-xs"><span class="text-muted"><?=$lang['FILENAME']?> : </span><?=$file['fullname']?><br></span>
    			<span class="text-muted"><?=$lang['FILETYPE']?> : </span><?=$file['type']?><br>
    			<span class="text-muted"><?=$lang['FILESIZE']?> : </span> <?=$file['size']?><br>
    			<span class="text-muted"><?=$lang['FILEDATE']?> : </span> <?=$file['time']?><br>
    			<span class="text-muted"><?=$lang['FOLDER']?> : </span> <?=$file['folder']?><br>
    			<span class="text-muted"><?=$lang['BY']?> : </span> <?=$file['user']?><br>
    			<span class="text-muted"><?=$lang['IP']?> : </span> <?=$file['ip']?>
    			</div>
    		</div>
                <br>
                <small class="text-muted" style="font-size:11px;">
                <?php if(!$file['direct']):?><?=$lang['DIRECT_FILE_NOTE']?><?php else: ?><?=$lang['FILEUPS']?>: <?=$file['ups']?> <?php endif; ?>
                <?php if($file['report']>0):?>~ <?=$lang['NUMPER_REPORT']?>: <?=$file['report']?><?php endif; ?>
                </small>
        </td>
		<td style="width:25%" class="hidden-xs">
            <small class="text-muted" style="font-size:11px;">
                <a href="<?=$file['ip_info_link']?>" target="_tab"><span class="glyphicon glyphicon-info-sign"></span> <?=$lang['IP_INFO']?></a> | <a href="<?=$file['showfilesbyip']?>" target="_tab" title=""><span class="glyphicon glyphicon-search"></span> <?=$lang['SHOWFILESBYIP']?></a><br>
                <span title="<?=$file['time']?>"><?=$file['time_human']?></span>
            </small>
        </td>
		<!-- admin files data td2 extra -->
	</tr>
    <?php endforeach;?>
</tbody>
</table>
</div>
<!-- end data table -->


<!-- pagination -->
<?=$page_nums?>
<hr>

<!-- button -->
<p class="submit <?php if($lang['DIR'] == 'rtl'):?>pull-left<?php endif; ?>">
	<input type="hidden" name="submit" value="1" />
	<button type="button" class="btn btn-default" onclick="checkAll(document.filesform, '_del', 'su');"><span class="glyphicon glyphicon-th-list"></span> <?=$lang['CHECK_ALL']?></button>
	<button type="submit" name="submit" class="btn btn-primary"><span><?=$lang['DEL_SELECTED']?></span></button>
</p>

<?php endif; ?>

<?=$H_FORM_KEYS?>
</form>
</div>
<!-- the big box end -->
