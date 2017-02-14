<?php
/**
*
* @package adm
* @version $Id: messages.php 2236 2013-11-30 10:07:23Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/

// not for directly open
if (!defined('IN_ADMIN'))
{
	exit();
}


#initiate variables
$current_template= 'messages.php';
$current_smt	= g('smt', 'latin', 'general');
$action			= ADMIN_PATH . '?cp=messages&amp;page=' . g('page', 'int', 1) . '&amp;smt=' . $current_smt;
$msg_sent		= g('sent', 'int', false);
$H_FORM_KEYS	= kleeja_add_form_key('adm_messages');
$there_queue	= isset($config['queue']) ? preg_match('!:del_[a-z0-9]{0,3}messages:!i', $config['queue']) : false;



#if $config['queue'] is not set yet
if(!isset($config['queue']))
{
	add_config('queue', '',0, 0, 0, true);
}


//
// Check form key
//
if (ip('submit'))
{
	if(!kleeja_check_form_key('adm_messages'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], $action);
	}
}


#add delete process to the queue
if($current_smt == 'del_d30' || $current_smt == 'del_all')
{

	if(strpos($config['queue'], ':' . $current_smt . 'messages:') !== false)
	{
		kleeja_admin_err($lang['DELETE_PROCESS_IN_WORK'], ADMIN_PATH . '?cp=messages');
	}
	else
	{
		update_config('queue', $config['queue'] . ':' . $current_smt . 'messages:');
		kleeja_admin_info($lang['DELETE_PROCESS_QUEUED'], ADMIN_PATH . '?cp=messages');
	}
}


$query	= array(
				'SELECT'	=> 'c.*',
				'FROM'		=> "`{$dbprefix}messages` c",
				'ORDER BY'	=> 'c.id DESC'
		);


if($current_smt == 'show_h24')
{
	$query['WHERE'] = 'c.time > ' . intval(time() - 3600 * 24);
}

$result = $SQL->build($query);

//pagination
$nums_rows		= $SQL->num($result);
$current_page	= g('page', 'int', 1);
$pagination		= new pagination($perpage, $nums_rows, $current_page);
$start			= $pagination->get_start_row();


$no_results = false;
$del_nums = array();

if ($nums_rows > 0)
{
	$query['LIMIT']	= "$start,$perpage";
	$result = $SQL->build($query);

	while($row=$SQL->fetch($result))
	{
		#make new lovely arrays
		$messages_for_tpl[$row['id']]	= array(
												'id' 		=> $row['id'],
												'name' 		=> $row['name'],
												'mail' 		=> $row['mail'],
												'text' 		=> nl2br(htmlspecialchars($row['text'])),
												'human_time'=> kleeja_date($row['time']),
												'time' 		=> kleeja_date($row['time'], false),
												'ip' 		=> $row['ip'],
												'sent'		=> ($row['id'] == $msg_sent || $row['replied']),
												'ip_finder'	=> 'http://www.ripe.net/whois?form_type=simple&full_query_string=&searchtext=' . $row['ip'] . '&do_search=Search'
											);

		$del[$row['id']] = p('del_' . $row['id'], 'int', false);
		$sen[$row['id']] = p('v_' . $row['id'], 'str', false);

		#when submit
		if (ip('submit'))
		{
			if ($del[$row['id']])
			{
				$del_nums[] = $row['id'];
			}
		}

		if (ip('reply_submit'))
		{
			if($sen[$row['id']])
			{
				$to      = $row['mail'];
				$subject = $lang['REPLY_MESSAGE'] . ':' . $config['sitename'];
				$message = "\n " . $lang['REPLY_MESSAGE'] . " " . $row['name'] . "\r\n " . $lang['ABOUT_YOUR_MESSAGE'] . " : " . $config['sitename'] .
							"\r\n " . $lang['BY_EMAIL'] . ": " . $row['mail'] . "\r\n" . $lang['ADMIN_REPLIED'] . "\r\n" . $sen[$row['id']] . "\r\n\r\n " . $config['sitename'];

				$send =  send_mail($to, $message, $subject, $config['sitemail'], $config['sitename']);
				//$send = true;

				if($send)
				{
					//
					//We will redirect to pages of results and show info msg there !
					//
					kleeja_admin_info($lang['IS_SEND_MAIL'], ADMIN_PATH . '?cp=messages&page=' . $current_page . '&sent=' . $row['id']);

					#update the status to replied
					$update_query = array(
											'UPDATE'	=> "{$dbprefix}messages",
											'SET'		=> "replied=1",
											'WHERE'		=> "id=". $row['id']
										);

					$SQL->build($update_query);

				}
				else
				{
					kleeja_admin_err($lang['ERR_SEND_MAIL'], ADMIN_PATH . '?cp=messages&page=' . $current_page);
				}
			}
		}
	}
	$SQL->free($result);
}
else
{
	$no_results = true;
}

//if deleted
if(sizeof($del_nums))
{
	$query_del	= array(
						'DELETE'	=> "`{$dbprefix}messages`",
						'WHERE'		=> "id IN('" . implode("', '", $del_nums) . "')"
					);

	$SQL->build($query_del);
}

$total_pages	= $pagination->get_total_pages();
$page_nums		= $pagination->print_nums(ADMIN_PATH . '?cp=messages');

//after submit
if (ip('submit'))
{
	$text	= ($SQL->affected() ? $lang['MESSAGES_UPDATED'] : $lang['NO_UP_CHANGE_S']);
	$text	.= '<script type="text/javascript"> setTimeout("get_kleeja_link(\'' . str_replace('&amp;', '&', $action) .  '\'); check_msg_and_reports();", 2000);</script>' . "\n";
	kleeja_admin_info($text, $action);
}


//secondary menu
$go_menu = array(
				'general' => array('name'=>$lang['R_MESSAGES'], 'link'=> ADMIN_PATH . '?cp=messages&amp;smt=general', 'goto'=>'general', 'current'=> $current_smt == 'general'),
				'show_h24' => array('name'=>$lang['SHOW_FROM_24H'], 'link'=> ADMIN_PATH . '?cp=messages&amp;smt=show_h24', 'goto'=>'show_h24', 'current'=> $current_smt == 'show_h24'),
				#CHECK IF IT'S ALREADY DONE ?
				'del_d30' => array('name'=>$lang['DELETE_EARLIER_30DAYS'], 'link'=> ADMIN_PATH . '?cp=messages&amp;smt=del_d30', 'goto'=>'del_d30', 'current'=> $current_smt == 'del_d30', 'confirm'=>true),
				'del_all' => array('name'=>$lang['DELETE_ALL'], 'link'=> ADMIN_PATH . '?cp=messages&amp;smt=del_all', 'goto'=>'del_all', 'current'=> $current_smt == 'del_all', 'confirm'=>true),
	);
