<?php if(!defined('IN_KLEEJA')) { exit; } ?>
<!-- Downlod template -->

<!--  title -->
<div class="page-header">
  <h1><?=shorten_text($current_title)?></h1>
</div>


<div class="row">
	<div class="col-md-6">
		<!-- Information File -->
		<!-- <p class="text-center"><strong><?=$lang['FILE_INFO']?></strong></p> -->
		<ul class="list-group">
			  <li class="list-group-item">
				  <h4 class="list-group-item-heading"><?=$lang['FILENAME']?></h4>
				  <p class="list-group-item-text"><?=$name?></p>
			  </li>
			  <li class="list-group-item">
				  <h4 class="list-group-item-heading"><?=$lang['FILETYPE']?></h4>
				  <p class="list-group-item-text"><?=$type?></p>
			  </li>
			  <li class="list-group-item">
				  <h4 class="list-group-item-heading"><?=$lang['FILESIZE']?></h4>
				  <p class="list-group-item-text"><?=$size?></p>
			  </li>
			  <li class="list-group-item">
				  <h4 class="list-group-item-heading"><?=$lang['FILEDATE']?></h4>
				  <p class="list-group-item-text"><?=$time?></p>
			  </li>
			  <li class="list-group-item">
				  <h4 class="list-group-item-heading"><?=$lang['FILEUPS']?></h4>
				  <p class="list-group-item-text"><?=$uploads?></p>
			  </li>
			  <?php if(!empty($fusername)):?>
			  <li class="list-group-item">
				  <h4 class="list-group-item-heading"><?=$lang['USERNAME']?></h4>
				  <p class="list-group-item-text"><a href="<?=$userfolder?>"><?=$fusername?></a></p>
			  </li>
		  	<?php endif;?>

			<li class="list-group-item list-group-item-info text-center">
				<a href="<?=$REPORT?>"><span class="glyphicon glyphicon-flag" aria-hidden="true"></span> <?=$lang['FILEREPORT']?></a>
			</li>
		</ul>
	</div>
	<!-- @end-Information-File -->



	<!-- box Downlod -->
	<div class="col-md-6">
		<div class="panel panel-default">
		  <div class="panel-heading">
		    <h3 class="panel-title"><?=$lang['FILE_FOUNDED']?></h3>
		  </div>
		  <div class="panel-body">
			  <div class="jumbotron text-center" id="url">
			    <p class="alert alert-danger"><?=$lang['JS_MUST_ON']?></p>
			  </div>
		  </div>
		</div>
	</div>
	<!-- @end-box-Downlod -->

</div>

<script type="text/javascript">
<!--
var timer = <?=$seconds_w?>;
ti();
function ti()
{
	if(timer > 0)
	{
		document.getElementById("url").innerHTML = '<h2 class="wait"><?=$lang['WAIT']?> ' + timer + ' <\/h2>';
		timer = timer - 1;
		setTimeout("ti()", 1000)
	}
	else
	{
		document.getElementById("url").innerHTML = '<p><a class="btn btn-primary btn-lg" href="<?=$url_file?>" target="balnk"><span class="glyphicon glyphicon-download" aria-hidden="true"><\/span> <?=$lang['CLICK_DOWN']?><\/a><\/p><p><?=$size?><\/p>';
	}
}
//-->
</script>

<!-- @end-Downlod-template -->
