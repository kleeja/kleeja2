</div> <!-- end of main wrap -->

<div class="clearfix"></div>
<footer class="">
	<p class="text-center text-muted">&copy; <a href="http://www.kleeja.com" target="_tab">Kleeja</a> 2007-<?=date('Y')?></p>
</footer>

<script src="<?=ADMIN_STYLE_PATH?>js/jquery.min.js"></script>
<script src="<?=ADMIN_STYLE_PATH?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=ADMIN_STYLE_PATH?>js/jqBarGraph.js"></script>

<?php if($go_to == 'rules' || $go_to == 'extra'):?>
<script type="text/javascript" src="<?=ADMIN_STYLE_PATH?>js/jqueryte.js"></script>
<?php endif;?>


<script type="text/javascript">
<!--
var STYLE_PATH_ADMIN = '<?=ADMIN_STYLE_PATH?>';
var go_to = '<?=$go_to?>';

<?php if($go_to == 'start'):?>
$('#chart_stats').jqBarGraph({
	data: arrayOfDataMulti,
	colors: ['#2D6BA9','#91C7E5'] ,
   legends: ['<?=$lang['FILE']?>','<?=$lang['IMAGE']?>'],
   legend: true,
   height: 200,
   barSpace: 5,
   width:700

});
<?php elseif($go_to == 'images' || $go_to == 'files'):?>
	$('[rel="popover"]').popover({
		html:true,
		trigger:'hover',
		delay: { show: 500, hide: 100 },
		content:function(){
			return $(this).parent().children('.extra_info').html();
		}
	});

		<?php if($go_to == 'images'):?>
		$(".kcheck input[type=checkbox]").change( function(){
			if($('.kcheck input[type=checkbox]:checked').length == 1){
				$('#search-one-item').css('display', 'inline');
			} else{
				$('#search-one-item select').prop('selectedIndex', 0);
				$('#search-one-item').css('display', 'none');
			}
		});

		$(".kcheck input[type=checkbox]").click( function(){
			$(this).trigger('change');
		});

		$(".kcheck label").click( function(){
			$(this).find('input').trigger('change');
		});

		$('#search-one-item').change(function(){
			tt = this.options[this.selectedIndex].value;
			dd = $('.kcheck input[type=checkbox]:checked').val();
			if(tt == 1){
				s_value = $('#ip_'+dd).html();
			}else if(tt == 2){
				s_value = $('#user_'+dd).html();
			}

			window.open("<?=$action_search?>&s_input="+tt+"&s_value=" + encodeURI(s_value), '_newtab');
		});
		<?php endif;?>

<?php elseif($go_to == 'messages' || $go_to=='reports'):?>
$('.popover-send').popover({
	html:true,
	placement:'auto top',
	content:function(){
		var msg = '<form method="post" action="' + $(this).data('action') + '" id="send_form" role="form"> \
            				<textarea name="v_'+ <?php if($go_to=='reports'):?>$(this).data('reportid')<?php else:?>$(this).data('messageid')<?php endif;?>+ '" cols="80" class="form-control" rows="3"></textarea> \
            				<input type="hidden" name="reply_submit" value="1"> \
            				<br> \
            				<p class="submit"> \
            					<button type="submit" name="reply_submit class="btn btn-primary btn-sm"><span><?=$lang['REPLY']?></span></button> \
            				</p> \
                        </form>';

		return msg;



	}
});
<?php elseif($go_to == 'search'):?>
    $(":text").keyup(function(e) {
        if($(this).val() != '') {
            $(":text").not(this).attr('disabled','disabled');
        } else {
            $(":text").removeAttr('disabled');
        }
    });

	<?php if(ig('s_input') && g('s_input', 'int') == 1):?>$('#user_ip').focus(); <?php endif;?>
	<?php if(ig('s_input') && g('s_input', 'int') == 2):?>$('#username').focus();<?php endif;?>

<?php elseif($go_to == 'users'):?>
$('.del-usergroup').popover({
	html:true,
	placement:'auto left',
	content:function(){
		f = $(this).data('id2del');
		return $('#delete_group_' + f).html();
	}
});
$('.new-ext-popover').popover({
	html:true,
	placement:'auto right',
	content:function(){return $('#new_ext_form').html();}
});
$('.converter-popover').popover({
	html:true,
	placement:'auto top',
	content:function(){return $('#converter_form').html();}
});
$('.acls-radios').button();
<?php endif;?>


function confirm_from(r)
{
	var msg = !r ? '<?=$lang['ARE_YOU_SURE_DO_THIS']?>' : r;
	if(confirm(msg)){
		return true;
	}else{
		return false;
	}
}


//check for msg, reports every 5min
// set timeout
var tid = setTimeout(check_msg_and_reports, 240000);
function check_msg_and_reports(){
$.ajax({
	url: './?check_msgs=1',
	success: function(data) {
		if(data.indexOf("::") != -1){
			var nums = data.split("::");
			if(nums[0] != 0){
				$('#messages').html(nums[0]).css('display', 'inline');
			}
			if(nums[1] != 0){
				$('#reports').html(nums[1]).css('display', 'inline');
			}
		}
  }
});

  tid = setTimeout(check_msg_and_reports, 240000);
}

function get_kleeja_link(URL, ID, p)
{
	if($.isArray(p) && p.confirm) {
		confirm_from();
	}

	location.href=URL;
	return false;
}

function submit_kleeja_data(formid, ID, p)
{

	if(p){
		confirm_from();
	}

	$(formid).submit();
	//return;
}

function change_color(obj, id, c, c2)
{
    c = (c == null) ? 'danger' : c;
    c2 = (c == null) ? 'nothing_is_here' : c2;
    var ii = document.getElementById(id);
    if (obj.checked) {
        ii.setAttribute("class", c);
        ii.setAttribute("className", c)
    } else {
        ii.setAttribute("class", c2);
        ii.setAttribute("className", c2)
    }
}
function checkAll(form, id, _do_c_, c, c2)
{
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].getAttribute("rel") != id) continue;
        if (form.elements[i].checked) {
			uncheckAll(form, id, _do_c_, c, c2);
			break
        }
        form.elements[i].checked = true;
        change_color(form.elements[i], _do_c_ + '[' + form.elements[i].value + ']', c, c2)
    }
}
function uncheckAll(form, id, _do_c_, c, c2)
{
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].getAttribute("rel") != id) continue;
        form.elements[i].checked = false;
        change_color(form.elements[i], _do_c_ + '[' + form.elements[i].value + ']', c, c2)
    }
}
function change_color_exts(id)
{
    eval('var ii = document.getElementById("su[' + id + ']");');
    eval('var g_obj = document.getElementById("gal_' + id + '");');
    eval('var u_obj = document.getElementById("ual_' + id + '");');
    if (g_obj.checked && u_obj.checked) {
        ii.setAttribute("class", 'o_all');
        ii.setAttribute("className", 'o_all')
    } else if (g_obj.checked) {
        ii.setAttribute("class", 'o_g');
        ii.setAttribute("className", 'o_g')
    } else if (u_obj.checked) {
        ii.setAttribute("class", 'o_u');
        ii.setAttribute("className", 'o_u')
    } else {
        ii.setAttribute("class", '');
        ii.setAttribute("className", '')
    }
}
function checkAll_exts(form, id, _do_c_)
{
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].getAttribute("rel") != id) continue;
        if (form.elements[i].checked) {
			uncheckAll_exts(form, id, _do_c_);
			break
        }
        form.elements[i].checked = true;
        change_color_exts(form.elements[i].value)
    }
}
function uncheckAll_exts(form, id, _do_c_) {
    for (var i = 0; i < form.elements.length; i++) {
        if (form.elements[i].getAttribute("rel") != id) continue;
        form.elements[i].checked = false;
        change_color_exts(form.elements[i].value)
    }
}

//-->
</script>
<?php /* Don't ever delete next line, it is for queue system */ ?>
<img src="<?=$config['siteurl']?>queue.php?image.gif" width="1" height="1" alt="queue" />
</body>
</html>
