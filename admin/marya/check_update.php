<!-- the big box begin -->
<div class="tit_con">

<?php if($current_smt == 'general'):?>
<h1><?=$lang['R_CHECK_UPDATE']?></h1>
</div>

<div class="big-box">

<div class="alert <?php if($error):?>alert-danger<?php else:?>alert-info<?php endif;?>"><?=$text?></div>


<?php elseif($current_smt == 'howto'):?>
<h1><?=$lang['HOW_UPDATE_KLEEJA']?></h1>


<ul class="update_list">
	<li><?=$lang['HOW_UPDATE_KLEEJA_STEP1']?></li>
	<li><?=$lang['HOW_UPDATE_KLEEJA_STEP2']?></li>
	<li><?=$lang['HOW_UPDATE_KLEEJA_STEP3']?></li>
</ul>


<div class="link_copy"><?=$update_link?></div>

<?php elseif($current_smt == 'site'):?>
<h2><a href="http://www.kleeja.com" rel="external">Kleeja.com &gt;</a></h2>
<?php endif;?>
</div>
<!-- the big box end -->
