
<!-- call begins -->
<div class="page-header">
  <h1><?=$lang['R_MESSAGES']?> <small><?php if($current_smt == 'show_h24'):?> &raquo; <?=$lang['SHOW_FROM_24H']?> <?php else:?> &raquo; <?=$lang['ALL']?><?php endif;?></small></h1>
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

<form method="post" name="callform" action="<?=$action?>" id="messages_form" role="form">
<!-- start data group -->
<div class="panel-group" id="accordion">
	<?php foreach ($messages_for_tpl as $message_id => $message):?>
	    <div class="panel panel-default" id="su[<?=$message_id?>]">
	       <div class="panel-heading">
	         <h4 class="panel-title">
				<input type="checkbox" name="del_<?=$message_id?>" value="<?=$message_id?>" onclick="change_color(this,'su[<?=$message_id?>]', 'panel panel-danger', 'panel panel-default');" rel="_del">
	          	<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?=$message_id?>">
				   <?=$message['name']?> @ <?=$message['human_time']?>
				    <span class="label label-default"><?php if($message['sent']):?><?=$lang['IS_SEND_MAIL']?><?php endif;?></span>
			   </a>
			 </h4>
			</div>
			 <div id="collapse<?=$message_id?>" class="panel-collapse collapse out">
			 <div class="panel-body">
				 <?=$message['text']?>
				 <br>
				 <br>
				 ____<br>
			 	<?php if(!empty($message['mail'])):?><span class="text-muted"><?=$lang['EMAIL']?>:</span> <?=$message['mail']?><br><?php endif;?>
				<span class="text-muted"><?=$lang['IP']?>:</span> <a href="<?=$message['ip_finder']?>" target="_blank"><?=$message['ip']?></a><br>
				<span class="text-muted"><?=$lang['TIME']?>:</span> <?=$message['time']?><br>
                <?php if(!empty($message['mail'])):?>
                <hr>
				<button type="button" class="btn btn-default popover-send" data-messageid="<?=$message_id?>" data-action="<?=$action?>" data-toggle="popover" data-title="<?=$lang['REPLY']?>: <?=$message['mail']?>"><?=$lang['REPLY_MESSAGE']?></button>
                <?php endif;?>

		    </div>
		    </div>
		  </div>
	<?php endforeach;?>
</div>
<!-- end data group -->

<!-- pagination -->
<?=$page_nums?>
<br>

<!-- button -->
<p class="submit">
	<input type="hidden" name="submit" value="1">
	<button type="button" class="btn btn-default" onclick="javascript:checkAll(document.callform, '_del', 'su', 'panel panel-danger', 'panel panel-default');"><span class="glyphicon glyphicon-th-list"></span> <?=$lang['CHECK_ALL']?></button>
	<button type="submit"  name="submit" class="btn btn-primary"><span><?=$lang['DEL_SELECTED']?></span></button>
</p>

<?=$H_FORM_KEYS?>
</form>
<?php endif;?>
<!-- call ends -->
