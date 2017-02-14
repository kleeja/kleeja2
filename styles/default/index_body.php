<?php if(!defined('IN_KLEEJA')) { exit; } ?>

<!-- index body template -->
<?php ($hook = $plugin->run_hook('index_body_tpl_page')) ? eval($hook) : null;?>


<div class="row">
<div class="col-md-8 col-md-offset-2">
<?php if(!empty($ERRORS)):?>
<!-- msg, Infos & Alerts & Errors -->
<div id="alert alert-info">
    <?php foreach($ERRORS as $msg):?>
    <ul class="">
        <li><?=$lang['INFORMATION']?> : <span> <?$msg?> </span></li>
    </ul>
    <?php endforeach;?>
</div><!-- @end-msg -->
<?php else:?>
<!-- welcome -->
<div class="jumbotron">
<h5><span class="glyphicon glyphicon-heart" aria-hidden="true"></span> <?=$lang['WELCOME']?> .. <?php if($user->is_user()):?>[ <?=$user->data['name']?> ]<?php endif;?></h5>
<p><?=$welcome_msg?></p>
</div>
<!-- @end-welcome -->
<?php endif;?>
</div>
</div>


<!-- form upload -->
<form id="uploader" action="<?=$config['siteurl']?>" method="post" enctype="multipart/form-data">

<!-- upload fields  -->
<div class="row">
    <div class="col-md-8 col-md-offset-2">
  <?php foreach($FILES_NUM_LOOP as $number=>$show):?>
    <div class="input-group" <?php if(!$show):?>display:none<?php endif;?>>
        <span class="input-group-btn" onclick="javascript:document.getElementById('file<?=$number?>').click();">
            <div class="btn btn-default file-button-browse">
                <span class="glyphicon glyphicon-folder-open"></span>
                <span class="image-preview-input-title"><?=$lang['OPEN']?></span>
            </div>
    		<!-- <button id="fake-file-button-browse" type="button" class="btn btn-default">
    			<span class="glyphicon glyphicon-folder-open"></span>
    		</button> -->
    	</span>
    	<input type="file" name="file[]" id="file<?=$number?>" style="display:none" onchange="javascript:document.getElementById('file-input-name<?=$number?>').value = this.value;">
    	<input type="text" id="file-input-name<?=$number?>" disabled="disabled" placeholder="File not selected" class="form-control">
    </div>
  <?php endforeach;?>
  <!-- @upload fields end -->


  <!-- verification code -->
  <?php if($config['enable_captcha'] && $config['safe_code']):?>
  <div class="safe_code">
    <p><?=$lang['VERTY_CODE']?></p>
    <div class="clr"></div>
    <div>
      <img style="vertical-align:middle;" id="kleeja_img_captcha" src="<?=$captcha_file_path?>" alt="<?=$lang['REFRESH_CAPTCHA']?>" title="<?=$lang['REFRESH_CAPTCHA']?>" onclick="javascript:update_kleeja_captcha('<?=$captcha_file_path?>', 'kleeja_code_answer');" />
      <input type="text" name="kleeja_code_answer" id="kleeja_code_answer" tabindex="5" />
    </div>
    <div class="clr"></div>
    <p class="explain"><?=$lang['NOTE_CODE']?></p>
  </div>
  <?php endif;?>
  <!-- @end-verification-code -->

  <br>
  <input name="submit_files" type="submit" class="btn btn-primary btn-lg btn-block" value="<?=$lang['DOWNLOAD_F']?>">
  </div>
</div>

</form>
<!-- @end-form-upload -->


<!-- box loading -->
<div id="loadbox">
    <div class="waitloading">
        <img src="<?=STYLE_PATH?>images/spin.gif" alt="<?=$lang['WAIT_LOADING']?>" />
        <p><?=$lang['WAIT_LOADING']?></p>
    </div>
</div><!-- @end-box-loading -->



<!-- OnLine -->
<?php if($show_online):?>
<div class="online">
  <p class="onlineall"><?=$lang['NUMBER_ONLINE']?> : [ <?=$usersnum?> ]</p>
  <?php foreach ($online_names as $name):?>
  <p class="name_users"><?=$name?></p>
  <?php endforeach;?>
</div>
<?php endif;?>
<!-- @end-OnLine -->



<!-- end of index -->
<?php ($hook = $plugin->run_hook('index_body_tpl_end_page')) ? eval($hook) : null;?>
