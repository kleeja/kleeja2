<?php if(!defined('IN_KLEEJA')) { exit; } ?>
<!-- Contact Us template -->

<!--  title -->
<div class="page-header">
  <h1><?=$current_title?></h1>
</div>


<?php if($ERRORS):?>
   <div class="alert alert-danger">
      <ul>
         <?php foreach($ERRORS as $n=>$error):?>
         <li> <strong><?=$lang['INFORMATION']?> </strong> <?=$error?></li>
         <?php endforeach;?>
      </ul>
   </div>
<?php endif;?>



 <!-- form Contact Us -->
 <form action="<?=$action?>" method="post" class="form-horizontal">

    <div class="form-group<?=(isset($ERRORS['cname']) ? ' has-error':'')?>">
        <label for="cname" class="col-sm-2 control-label"><?=$lang['YOURNAME']?></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" name="cname" id="cname" placeholder="<?=$lang['YOURNAME']?>" required>
        </div>
    </div>

    <div class="form-group<?=(isset($ERRORS['cmail']) ? ' has-error':'')?>">
        <label for="cmail" class="col-sm-2 control-label"><?=$lang['EMAIL']?></label>
        <div class="col-sm-10">
            <input type="text" class="form-control" id="cmail" name="cmail" placeholder="<?=$lang['EMAIL']?>" required>
        </div>
    </div>
    <div class="form-group<?=(isset($ERRORS['ctext']) ? ' has-error':'')?>">
        <label for="ctext" class="col-sm-2 control-label"><?=$lang['TEXT']?></label>
        <div class="col-sm-10">
            <textarea name="ctext" class="form-control" id="ctext" placeholder="<?=$lang['TEXT']?>" rows="3" required></textarea>
        </div>
    </div>
    <!-- verification code -->
    <?php if($config['enable_captcha']):?>
    <div class="form-group<?=(isset($ERRORS['captcha']) ? ' has-error':'')?>">
        <label for="kleeja_code_answer" class="col-sm-2 control-label"><?=$lang['VERTY_CODE']?></label>
        <div class="col-sm-10">
           <img style="" id="kleeja_img_captcha" src="<?=$captcha_file_path?>" alt="<?=$lang['REFRESH_CAPTCHA']?>" title="<?=$lang['REFRESH_CAPTCHA']?>" onclick="javascript:update_kleeja_captcha('<?=$captcha_file_path?>', 'kleeja_code_answer');">

           <input type="text" name="kleeja_code_answer" id="kleeja_code_answer"  class="form-control" aria-describedby="helpBlock"
           placeholder="<?=$lang['VERTY_CODE']?>" required>
           <span id="helpBlock" class="help-block"><?=$lang['NOTE_CODE']?></span>
        </div>
    </div>
    <?php endif;?>

    <div class="form-group">
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" name="submit" class="btn btn-default"><?=$lang['SEND']?></button>
        <?=kleeja_add_form_key('call')?>
      </div>
    </div>
 </form>
 <!-- @end-form -->
