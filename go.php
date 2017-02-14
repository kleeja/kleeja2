<?php
/**
*
* @package Kleeja
* @version $Id: go.php 2238 2013-12-06 16:36:08Z phpfalcon@gmail.com $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/


/**
 * We are in go.php file, useful for exceptions
 */
define('IN_GO', true);


/**
 * @ignore
 */
define('IN_KLEEJA', true);
define('PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
include PATH . 'includes/common.php';


#to be avaliable for later, extra code between head tag
$extra_code_in_header = '';

($hook = $plugin->run_hook('begin_go_page')) ? eval($hook) : null; //run hook


/**
 * General Pages of Kleeja
 * ucp.php?go=[...]
 */
switch (g('go', 'str', ''))
{
	case 'exts':
	case 'guide':

		#page info
		$current_template	= 'guide.php';
		$current_title = $lang['GUIDE'];

		#orgnize the extensions to be shown in categories
		$tgroups = $ttgroups = array();
		$tgroups = array_keys($d_groups);
		$same_group= $rando = 0;
		foreach($tgroups as $gid)
		{
			#if this is admin group, dont show it public
			if($gid == 1 && $user->data['group_id'] != 1)
			{
				continue;
			}

			$guide_exts[$gid] = array(
				'group_name' => preg_replace_callback('!{lang.([A-Z0-9]+)}!',
				function ($m)
				{
					global $lang;
				    return $lang[$m[1]];
				 }, $d_groups[$gid]['data']['group_name']),
				'exts' => $d_groups[$gid]['exts']
			);


			$rando = $rando ? 0 : 1;
		}

		($hook = $plugin->run_hook('guide_go_page')) ? eval($hook) : null; //run hook

	break;

	case 'report' :

		#page info
		$current_template	= 'report.php';
		$current_title		= $lang['REPORT'];
		$id_d	= ig('id') ? g('id', 'int') : p('rid', 'int', 0);
		$url_id	= $config['mod_writer'] == 1 ? $config['siteurl'] . 'download' . $id_d . '.html' : $config['siteurl'] . 'do.php?id=' . $id_d;
		$action	= $config['siteurl'] . 'go.php?go=report';


		#no error yet
		$ERRORS = false;

		#Does this file exists ?
		if($id_d)
		{
			$query = array(
							'SELECT'	=> 'f.real_filename, f.name',
							'FROM'		=> "{$dbprefix}files f",
							'WHERE'		=> 'id=' . $id_d,
							'LIMIT'		=> '1'
						);

			($hook = $plugin->run_hook('qr_report_go_id')) ? eval($hook) : null; //run hook

			$result	= $SQL->build($query);

			if ($SQL->num($result))
			{
				$row = $SQL->fetch($result);
				$filename_for_show	= $row['real_filename'] == '' ? $row['name'] : $row['real_filename'];
			}
			else
			{
				($hook = $plugin->run_hook('not_exists_qr_report_go_id')) ? eval($hook) : null; //run hook
				kleeja_error($lang['FILE_NO_FOUNDED']);
			}
			$SQL->free($result);
		}

		#set variables
		$t_rname = p('rname', 'str', '');
		$t_rmail = p('rmail', 'mail', '');
		$t_rtext = p('rtext', 'str', '');
		$t_surl	= p('surl', 'str', '');

		#no submit yet
		if (!ip('submit'))
		{
			($hook = $plugin->run_hook('no_submit_report_go_page')) ? eval($hook) : null; //run hook
		}
		#submited
		else
		{
			$ERRORS	= array();

			($hook = $plugin->run_hook('submit_report_go_page')) ? eval($hook) : null; //run hook

			//check for form key
			if(!kleeja_check_form_key('report'))
			{
				$ERRORS['form_key'] = $lang['INVALID_FORM_KEY'];
			}
			if(!kleeja_check_captcha())
			{
				$ERRORS['captcha']	= $lang['WRONG_VERTY_CODE'];
			}
			if ($t_rname == '' && !$user->is_user())
			{
				$ERRORS['rname'] = $lang['EMPTY_FIELDS'] . ' : ' . (p('rname', 'str') == '' && !$user->is_user() ? ' [ ' . $lang['YOURNAME'] . ' ] ' : '')
									. (p('rurl', 'str') == '' ? '  [ ' . $lang['URL']  . ' ] ': '');
			}
			if($t_surl == '')
			{
				$ERRORS['surl']	=  $lang['EMPTY_FIELDS'] . ' : [ ' . $lang['URL_F_FILE'] . ' ]';
			}
			if (!$t_rmail && !$user->is_user())
			{
				$ERRORS['rmail'] = $lang['WRONG_EMAIL'];
			}
			if (strlen($t_rtext) > 300)
			{
				$ERRORS['rtext'] = $lang['NO_ME300RES'];
			}
			if ($t_surl == ''  && !$id_d)
			{
				$ERRORS['rid'] = $lang['NO_ID'];
			}

			($hook = $plugin->run_hook('submit_report_go_page2')) ? eval($hook) : null; //run hook

			#no error , lets do process
			if(empty($ERRORS))
			{
				$name	= $SQL->escape(!$user->is_user() ? $t_rname : $user->data['name']);
				$text	= $SQL->escape($t_rtext);
				$mail	= $SQL->escape(!$user->is_user() ? $t_rmail : $user->data['mail']);
				$url	= $SQL->escape($id_d ? $url_id : $t_surl);
				$time 	= (int) time();
				$ip		=  $SQL->escape($user->data['ip']);

				$insert_query	= array(
										'INSERT'	=> 'name ,mail ,url ,text ,time ,ip',
										'INTO'		=> "{$dbprefix}reports",
										'VALUES'	=> "'$name', '$mail', '$url', '$text', $time, '$ip'"
									);

				($hook = $plugin->run_hook('qr_insert_new_report')) ? eval($hook) : null; //run hook

				$SQL->build($insert_query);

				#update number of reports
				$update_query	= array(
										'UPDATE'	=> "{$dbprefix}files",
										'SET'		=> 'report=report+1',
										'WHERE'		=> 'id=' . $id_d,
									);

				($hook = $plugin->run_hook('qr_update_no_file_report')) ? eval($hook) : null; //run hook

				$SQL->build($update_query);

				if(!defined('NO_EMAIL_REPORTS'))
				{
					$to = $config['sitemail2']; //administrator e-mail
					$message = $text . "\n\n\n\n" . 'URL :' . $url . ' - TIME : ' . date('d-m-Y h:i a', $time) . ' - IP:' . $ip;
					$subject = $lang['REPORT'];
					send_mail($to, $message, $subject, $mail, $name);
				}

				kleeja_info($lang['THNX_REPORTED']);
			}
		}

		($hook = $plugin->run_hook('report_go_page')) ? eval($hook) : null; //run hook

	break;

	case 'call' :

		#Not allowed to access this page ?
		if (!$user->can('access_call'))
		{
			($hook = $plugin->run_hook('user_cannot_access_call')) ? eval($hook) : null; //run hook
			kleeja_info($lang['HV_NOT_PRVLG_ACCESS']);
		}

		#page info
		$current_template	= 'call.php';
		$current_title	= $lang['CALL'];
		$action	= $config['siteurl'] . 'go.php?go=call';


		#no error yet
		$ERRORS = false;

		#set variables
		$t_cname = p('cname', 'str', '');
		$t_cmail = p('cmail', 'mail', false);
		$t_ctext = p('ctext', 'str', '');

		#submited
		if (!ip('submit'))
		{
			($hook = $plugin->run_hook('no_submit_call_go_page')) ? eval($hook) : null; //run hook
		}
		#sumbited
		else
		{
			$ERRORS	= array();

			($hook = $plugin->run_hook('submit_call_go_page')) ? eval($hook) : null; //run hook

			#check for form key
			if(!kleeja_check_form_key('call'))
			{
				$ERRORS['form_key'] = $lang['INVALID_FORM_KEY'];
			}
			if(!kleeja_check_captcha())
			{
				$ERRORS['captcha'] = $lang['WRONG_VERTY_CODE'];
			}
			if (($t_cname == '' && !$user->is_user())  || $t_ctext =='')
			{
				$ERRORS['cname']	= $lang['EMPTY_FIELDS'] . ' : ' . ($t_cname == '' && !$user->is_user() ? ' [ ' . $lang['YOURNAME'] . ' ] ' : '')
								. ($t_ctext == '' ? '  [ ' . $lang['TEXT']  . ' ] ': '');
			}
			if (!$t_cmail && !$user->is_user())
			{
				$ERRORS['cmail'] = $lang['WRONG_EMAIL'];
			}
			if (strlen($t_ctext) > 300)
			{
				$ERRORS['ctext'] = $lang['NO_ME300TEXT'];
			}

			if($t_cname == '_kleeja_')
			{
				update_config('new_version', '');
			}

			($hook = $plugin->run_hook('submit_call_go_page2')) ? eval($hook) : null; //run hook

			#no errors ,lets do process
			if(empty($ERRORS))
			{
				$name	= $SQL->escape(!$user->is_user() ? $t_cname : $user->data['name']);
				$text	= $SQL->escape($t_ctext);
				$mail	= $SQL->escape(!$user->is_user() ? $t_cmail : $user->data['mail']);
				$timee	= time();
				$ip		= $user->data['ip'];

				$insert_query	= array(
										'INSERT'	=> "name ,text ,mail ,time ,ip",
										'INTO'		=> "`{$dbprefix}messages`",
										'VALUES'	=> "'$name', '$text', '$mail', $timee, '$ip'"
									);

				($hook = $plugin->run_hook('qr_insert_new_call')) ? eval($hook) : null; //run hook

				if ($SQL->build($insert_query))
				{
					send_mail($config['sitemail2'], $text  . "\n\n\n\n" . 'TIME : ' . date('d-m-Y h:i a', $timee) . ' - IP:' . $ip, $lang['CALL'], $mail, $name);
					kleeja_info($lang['THNX_CALLED']);
				}
			}
		}

		($hook = $plugin->run_hook('call_go_page')) ? eval($hook) : null; //run hook

	break;


	case 'del' :

		($hook = $plugin->run_hook('del_go_page')) ? eval($hook) : null; //run hook

		#is it allowd ?
		if (!$config['del_url_file'])
		{
			kleeja_info($lang['NO_DEL_F'], $lang['E_DEL_F']);
		}

		//examples :
		//f2b3a82060a22a80283ed961d080b79f
		//aa92468375a456de21d7ca05ef945212
		$cd	= preg_replace('/[^0-9a-z]/i', '', g('cd', 'str', ''));

		if (empty($cd))
		{
			kleeja_error($lang['WRONG_URL']);
		}
		else
		{
			#to check
			if(g('sure', 'str', '') == 'ok')
			{
				$query	= array(
								'SELECT'=> 'f.id, f.name, f.folder, f.size, f.type',
								'FROM'	=> "{$dbprefix}files f",
								'WHERE'	=> "f.code_del='" . $SQL->escape($cd) . "'",
								'LIMIT'	=> '1',
							);

				($hook = $plugin->run_hook('qr_select_file_with_code_del')) ? eval($hook) : null; //run hook

				$result	= $SQL->build($query);

				if ($SQL->num($result))
				{
					$row=$SQL->fetch($result);

					kleeja_unlink($row['folder'] . '/' . $row['name']);

					#delete thumb
					if (file_exists($row['folder'] . '/thumbs/' . $row['name']))
					{
						kleeja_unlink($row['folder'] . '/thumbs/' . $row['name']);
					}

					$is_img = in_array($row['type'], array('png','gif','jpg','jpeg','tif','tiff', 'bmp')) ? true : false;

					$query_del	= array(
										'DELETE' => "{$dbprefix}files",
										'WHERE'	=> 'id=' . $row['id']
									);

					($hook = $plugin->run_hook('qr_del_file_with_code_del')) ? eval($hook) : null; //run hook

					$SQL->build($query_del);

					if($SQL->affected())
					{
						#update number of stats
						update_stats($is_img ? 'total_images':'total_files', 1, '-');
						update_stats('total_sizes', intval($row['size']), '-');

						$SQL->build($update_query);
						kleeja_info($lang['DELETE_SUCCESFUL']);
					}
					else
					{
						kleeja_info($lang['ERROR_TRY_AGAIN']);
					}


					$SQL->free($result);
				}
			}
			else
			{
				//fix for IE+
				$extra_codes = '<script type="text/javascript">
						function confirm_from()
						{
							if(confirm(\'' . $lang['ARE_YOU_SURE_DO_THIS'] . '\')){
								window.location = "go.php?go=del&sure=ok&cd=' . $cd . '";
							}else{
								window.location = "index.php";
							}
						}
						window.onload=confirm_from;
					</script>';
				kleeja_info($lang['ARE_YOU_SURE_DO_THIS'], '', true, false, 0, $extra_codes);
			}
		}#else

	break;


	case 'stats' :

		#Not allowed to access this page ?
		if (!$user->can('access_stats'))
		{
			($hook = $plugin->run_hook('user_cannot_access_stats')) ? eval($hook) : null; //run hook
			//kleeja_info($lang['HV_NOT_PRVLG_ACCESS']);
		}

		#is it allowed?
		if (!$config['allow_stat_pg'])
		{
			kleeja_info($lang['STATS_CLOSED'], $lang['STATS_CLOSED']);
		}

		#stats of most online users
		if(empty($config['most_user_online_ever']) || trim($config['most_user_online_ever']) == '')
		{
			$most_online	= 1; # 1 == you
			$on_muoe		= time();
		}
		else
		{
			list($most_online, $on_muoe) = @explode(':', $config['most_user_online_ever']);
		}

		#page info
		$current_title		= $lang['STATS'];
		$current_template	= 'stats.php';
		$files_st	= isset($stats['total_files']) ? $stats['total_files'] : 0;
		$imgs_st	= isset($stats['total_images']) ? $stats['total_images'] : 0;
		$users_st	= isset($stats['total_users']) ? $stats['total_users'] : 0;
		$sizes_st	= isset($stats['total_sizes']) ? readable_size($stats['total_sizes']) : 0;
		$on_muoe	= kleeja_date($on_muoe);

		($hook = $plugin->run_hook('stats_go_page')) ? eval($hook) : null; //run hook

	break;


	# Depreacted from 1rc6+, see do.php
	case 'down':

		#go.php?go=down&n=$1&f=$2&i=$3
		if(ig('n'))
		{
			$url_file = $config['mod_writer'] == 1 ? $config['siteurl'] . 'download' . g('i', 'int') . '.html' : $config['siteurl'] . 'do.php?id=' . g('n', 'int');
		}
		else
		{
			$url_file = $config['siteurl'];
		}

		$SQL->close();

		#redirect and exit
		redirect($url_file, true, true);
	break;



	// Default , if you are a developer , you can embed your page here with this hook
	// by useing g('go') == 'your_page' and your codes.
	default:

		$no_request = true;

		($hook = $plugin->run_hook('default_go_page')) ? eval($hook) : null; //run hook

		if($no_request)
		{
			kleeja_error($lang['ERROR_NAVIGATATION']);
		}

	break;
}#end switch

($hook = $plugin->run_hook('end_go_page')) ? eval($hook) : null; //run hook

#no template, no title, set them to default
$current_template  = empty($current_template) ? 'info.php' : $current_template;
$current_title  = empty($current_title) ? '' : $current_title;


#header
kleeja_header($current_title, $extra_code_in_header);
#page template
include get_template_path($current_template);
#footer
kleeja_footer();
