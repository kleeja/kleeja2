
<!--- START EDIT STAGE -->
<!-- the big box begin -->
<div class="configs_page">
<form method="post" action="<?=$action?>" id="opt_form" role="form" class="form">
<!-- admin configs tr extra begin -->

<?=$options?>


<!-- admin configs tr extra -->

<!-- button -->
<p class="submit <?php if($lang['DIR'] == 'rtl'):?>">pull-left<?php endif;?>">
	<input type="hidden" name="submit" value="1" />
	<button type="submit" id="submit" name="_submit_" class="btn btn-primary"><span><?=$n_submit?></span></button>
</p>

<?=$H_FORM_KEYS?>

</form>
</div>
<!-- the big box end -->
<!--- / END EDIT STAGE -->
