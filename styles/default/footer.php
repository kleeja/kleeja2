<?php if(!defined('IN_KLEEJA')) { exit; } ?>


<hr>

<footer>
	<!--
		Powered by kleeja,
		Kleeja is a software, designed to help webmasters to
		give their Users the ability to upload files yo thier servers.
		www.Kleeja.com
	 -->

   <p>
	   <?=$lang['COPYRIGHTS_X']?> &copy; <a href="<?=$config['siteurl']?>"><?=$config['sitename']?></a>
	   <?php if($user->can('enter_acp')):?>
   		<a href="<?=ADMIN_PATH?>" class="btn btn-md btn-warning pull-<?php if($lang['DIR']=='rtl'):?>left<?php else:?>right<?php endif;?>"><?=$lang['ADMINCP']?></a>
		<?php endif;?>
		<?php if($page_stats):?>
		<div class="text-muted"><small><?=$page_stats?></small></div>
		<?php endif;?>

   </p>
 </footer>

</div><!--/.container-->



<?php if($google_analytics):?>
<?=$google_analytics?>
<?php endif;?>

<?php /* Don't ever delete next line, it is for queue system */ ?>
<img src="<?=$config['siteurl']?>queue.php?image.gif" width="1" height="1" alt="queue" />

<script type="text/javascript" src="<?=STYLE_PATH?>javascript/jquery.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


<script>
$(document).ready(function(){
	$('#uploader').submit(function(){
		$('#loadbox').css('display', 'block');
		$('#uploader').css('display', 'none');
	});

    //@see https://getbootstrap.com/javascript/#tooltips
    $('[data-toggle="tooltip"]').tooltip()
});

//javascript for captcha
function update_kleeja_captcha(captcha_file, input_id)
{
	document.getElementById(input_id).value = '';
	//Get a reference to CAPTCHA image
	img = document.getElementById('kleeja_img_captcha');
	 //Change the image
	img.src = captcha_file + '?' + Math.random();
}

<?php ($hook = $plugin->run_hook('footer_tpl_end_page_javascript')) ? eval($hook) : null;?>
</script>
</body>
</html>
