
<!-- reports begins -->
<div class="page-header">
  <h1><?=$lang['R_REPORTS']?> <small><?php if($current_smt == 'show_h24'):?> &raquo; <?=$lang['SHOW_FROM_24H']?> <?php else:?> &raquo; <?=$lang['ALL']?><?php endif;?></small></h1>
</div>


<?php if($there_queue):?>
<div class="clearfix"></div>
<div class="alert alert-info">
	<?=$lang['DELETE_PROCESS_IN_WORK']?>
</div>
<hr>
<?php endif;?>

<?php if($no_results):?>
<div class="alert alert-info"><?=$lang['MESSAGE_NONE']?></div>
<?php else:?>


<form method="post" name="reportform" action="<?=$action?>" id="reports_form" rol="form">

<!-- start data group -->
<div class="panel-group" id="accordion">
<?php foreach ($reports_for_tpl as $report_id => $report):?>
    <div class="panel panel-default" id="su[<?=$report['id']?>]">
       <div class="panel-heading">
         <h4 class="panel-title">
			<input type="checkbox" name="del_<?=$report['id']?>" value="<?=$report['id']?>" onclick="change_color(this,'su[<?=$report['id']?>]', 'panel panel-danger', 'panel panel-default');" rel="_del">
          	<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$report['id']?>">
				<?=$report['name']?> @ <?=$report['human_time']?>
			    <span class="label label-default"><?php if($report['sent']):?><?=$lang['IS_SEND_MAIL']?><?php endif;?></span>
		   </a>
		 </h4>
		</div>
		 <div id="collapse<?=$report['id']?>" class="panel-collapse collapse out">
		 <div class="panel-body">
			 <?=$report['text']?>
			 <br>
			 <br>
			 ___________________<br>
		 	<span class="text-muted"><?=$lang['EMAIL']?>:</span> <?=$report['mail']?><br>
			<span class="text-muted"><?=$lang['IP']?>:</span> <a href="<?=$report['ip_finder']?>" target="_blank"><?=$report['ip']?></a><br>
			<span class="text-muted"><?=$lang['TIME']?>:</span> <?=$report['time']?><br>
			<span class="text-muted"><?=$lang['URL']?>:</span> <a target="_blank" href="<?=$report['url']?>"><?=$lang['CLICKHERE']?></a><br>
            <?php if(!empty($report['mail'])):?>
            <hr>
			<button type="button" class="btn btn-default popover-send" data-reportid="<?=$report['id']?>" data-action="<?=$action?>" data-toggle="popover" data-title="<?=$lang['REPLY']?>: <?=$report['mail']?>"><?=$lang['REPLY_REPORT']?></button>
            <?php endif;?>
	    </div>
	    </div>
	  </div>
<?php endforeach;?>
</div>
<!-- end data table -->


<!-- pagination -->
<?=$page_nums?>
<hr>

<!-- button -->
<p class="submit<?php if($lang['DIR'] == 'rtl'):?> pull-left<?php endif;?>">
	<input type="hidden" name="submit" value="1">
	<button type="button" class="btn btn-default" onclick="javascript:checkAll(document.reportform, '_del', 'su', 'panel panel-danger', 'panel panel-default');"><span class="glyphicon glyphicon-th-list"></span> <?=$lang['CHECK_ALL']?></button>
	<button type="submit" name="submit" class="btn btn-primary"><span><?=$lang['DEL_SELECTED']?></span></button>
</p>



<?=$H_FORM_KEYS?>
</form>
<?php endif;?>
<!-- reports ends -->
