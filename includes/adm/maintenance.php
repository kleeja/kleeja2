<?php
/**
*
* @package adm
* @version $Id: maintenance.php 2240 2013-12-07 23:22:54Z phpfalcon@gmail.com $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/


// not for directly open
if (!defined('IN_ADMIN'))
{
	exit();
}


#turn time-limit off
@set_time_limit(0);

#get current case
$case = g('case', 'str', false);

#set _get form key
$GET_FORM_KEY = kleeja_add_form_key_get('REPAIR_FORM_KEY');


//check _GET Csrf token
if($case && in_array($case, array('clearc', 'resync', 'sync_users', 'tables', 'sync_sizes', 'status_file')))
{
	if(!kleeja_check_form_key_get('REPAIR_FORM_KEY'))
	{
		kleeja_admin_err($lang['INVALID_GET_KEY'], ADMIN_PATH, false);
	}
}

switch($case):

default:

# Get real number from database right now
$all_files = get_actual_stats('total_files');
$all_images = get_actual_stats('total_images');
$all_users = get_actual_stats('total_users');
$all_sizes = readable_size(get_actual_stats('total_sizes'));


#links
$del_cache_link		= ADMIN_PATH . '?cp=maintenance&amp;case=clearc&amp;' . $GET_FORM_KEY;
$resync_files_link	= ADMIN_PATH . '?cp=maintenance&amp;case=resync&amp;c=sync_files&amp;' . $GET_FORM_KEY;
$resync_images_link	= ADMIN_PATH . '?cp=maintenance&amp;case=resync&amp;c=sync_images&amp;' . $GET_FORM_KEY;
$resync_users_link	= ADMIN_PATH . '?cp=maintenance&amp;case=sync_users&amp;' . $GET_FORM_KEY;
$resync_sizes_link	= ADMIN_PATH . '?cp=maintenance&amp;case=sync_sizes&amp;' . $GET_FORM_KEY;
$repair_tables_link	= ADMIN_PATH . '?cp=maintenance&amp;case=tables&amp;' . $GET_FORM_KEY;
$status_file_link	= ADMIN_PATH . '?cp=maintenance&amp;case=status_file&amp;' . $GET_FORM_KEY;



$current_template = "maintenance.php";

break;


// We, I mean developrts and support team anywhere, need sometime
// some inforamtion about the status of Kleeja .. this will give
// a zip file contain those data ..
case 'status_file':

if(ig('_ajax_'))
{
	exit('Ajax is forbidden here !');
}

#kleeja version
$text .= "\n\n----------------------- kleeja version  --------------------\n";
$text .= KLEEJA_VERSION;

#config file data
$config_file_data = file_get_contents(PATH . 'config.php');
$cvars = array('dbuser', 'dbpass', 'dbname', 'script_user', 'script_pass', 'script_db');
$config_file_data = preg_replace('!\$(' . implode('|', $cvars). ')(\s*)=(\s*)["|\']([^\'"]+)["|\']!', '$\\1\\2=\\3"******"', $config_file_data);
$text .= "\n\n----------------------- config.php file info ---------------\n";
$text .= $config_file_data;

unset($config_file_data);

#kleeja log
$text .= "\n\n----------------------- kleeja_log.log info ----------------\n";

if(file_exists(PATH . 'cache/kleeja_log.log') && defined('DEV_STAGE'))
{
	$text .= 'file is existed.';
}
else
{
	$text .= 'file is not existed.';
}

#Groups info
$text .= "\n\n----------------------- groups info ------------------------\n";
$text .= var_export($d_groups, true);

#eval test, Im not so sure about this test, must be
#tried at real situation.
$t = 'OFF';
@eval('$t = "ON";');
$text .= "\n\n----------------------- eval function test -----------------\n";
$text .= $t;
#plugins info
#later


#ban info
$text .= "\n\n----------------------- ban info ----------------------------\n";
$text .= var_export($banss, true);

#stats
$stat_vars = array('stat_files', 'stat_imgs', 'stat_sizes', 'stat_users', 'stat_last_file', 'stat_last_f_del',
				'stat_last_google', 'stat_last_bing', 'stat_google_num', 'stat_bing_num', 'stat_last_user');

$text .= "\n\n----------------------- stats info --------------------------\n";
$text .=var_export(compact($stat_vars), true);
unset($stat_vars);


#grab configs
$d_config = $config;
unset($d_config['h_key'], $d_config['ftp_info']);
$text .= "\n\n----------------------- config variables info ---------------\n";
$text .= var_export($d_config, true);
unset($d_config);

#php info
ob_start();
@phpinfo();
$phpinfo = ob_get_contents();
ob_end_clean();
$text .= "\n\n----------------------- phpinfo  ----------------------------\n";
$text .= $phpinfo;
unset($phpinfo);

#push it
header('Content-Type: application/txt');
header('X-Download-Options: noopen');
header('Content-Disposition: attachment; filename="KleejaDataForSupport' .  date('dmY'). '.txt"');
echo $text;
$SQL->close();
exit;

break;


//
//fix tables ..
//
case 'tables':

$query	= "SHOW TABLE STATUS";
$result	= $SQL->query($query);
$text = '';

while($row=$SQL->fetch($result))
{
	$queryf	=	"REPAIR TABLE `" . $row['Name'] . "`";
	$resultf = $SQL->query($queryf);
	if ($resultf)
	{
		$text .= '<li>' . $lang['REPAIRED_TABLE'] . $row['Name'] . '</li>';
	}
}

$SQL->free($result);

$text .= '<script type="text/javascript"> setTimeout("get_kleeja_link(\'' . ADMIN_PATH . '?cp=maintenance' .  '\');", 2000);</script>' . "\n";
$current_template = 'info.php';


break;

//
//re-sync sizes ..
//
case 'sync_sizes':

#no start ? or there
$start = g('start', 'int', false);

$end = sync_total_files(true, $start, true);

#no end, then sync'ing is done...
if(!$end)
{
	$cache->clean('data_stats');
	$text = $title = sprintf($lang['SYNCING_DONE'], $lang['ALL_FILES']);
	$link_to_go = ADMIN_PATH . '?cp=maintenance';
}
else
{
	$text = $title = sprintf($lang['SYNCING'], $lang['ALL_FILES']) . ' (' . (!$start ? 0 : $start) . '->'  . (!$end  ? '?' : $end) . ')';
	$link_to_go = ADMIN_PATH . '?cp=maintenance&case=sync_sizes&amp;' . $GET_FORM_KEY . '&start=' . $end;
}


$text .= '<script type="text/javascript"> setTimeout("location.href=\'' .   str_replace('&amp;', '&', $link_to_go) .  '\';", 3000);</script>' . "\n";

$current_template = 'info.php';

break;

//
// resync images & files number
//
case 'resync':


#no start ? or there
$start = g('start', 'int', false);

switch(g('c', 'str', '')):
default:
case 'sync_files':

$end = sync_total_files(true, $start);

#no end, then sync'ing is done...
if(!$end)
{
	$cache->clean('data_stats');
	$text = $title = sprintf($lang['SYNCING_DONE'], $lang['ALL_FILES']);
	$link_to_go = ADMIN_PATH . '?cp=maintenance';
}
else
{
	$text = $title = sprintf($lang['SYNCING'], $lang['ALL_FILES']) . ' (' . (!$start ? 0 : $start) . '->'  . (!$end  ? '?' : $end) . ')';
	$link_to_go = ADMIN_PATH . '?cp=maintenance&case=resync&c=sync_files&amp;' . $GET_FORM_KEY . '&start=' . $end;
}


$text .= '<script type="text/javascript"> setTimeout("location.href=\'' .   str_replace('&amp;', '&', $link_to_go) .  '\';", 3000);</script>' . "\n";

$current_template = 'info.php';
break;

case 'sync_images':

$end = sync_total_files(false, $start);

#no end, then sync'ing is done...
if(!$end)
{
	$cache->clean('data_stats');
	$text = $title = sprintf($lang['SYNCING_DONE'], $lang['ALL_IMAGES']) . ' (' . (!$start ? 0 : $start) . '->' . (!$end  ? '?' : $end) . ')';
	$link_to_go = ADMIN_PATH . '?cp=maintenance';
}
else
{
	$text = $title = sprintf($lang['SYNCING'], $lang['ALL_IMAGES']);
	$link_to_go = ADMIN_PATH . '?cp=maintenance&case=resync&c=sync_images&amp;' . $GET_FORM_KEY .'&start=' . $end;
}


$text .= '<script type="text/javascript"> setTimeout("location.href=\'' . str_replace('&amp;', '&', $link_to_go) .  '\';", 3000);</script>' . "\n";

$current_template = 'info.php';

break;
endswitch;

break;



//
//re-sync total users number
//
case 'sync_users':

$query_w	= array(
					'SELECT'	=> 'name',
					'FROM'		=> "{$dbprefix}users"
				);

$result_w = $SQL->build($query_w);

update_stats('total_users', 0, '');

$user_number = 0;
while($row=$SQL->fetch($result_w))
{
	$user_number++;
}

$SQL->free($result_w);

update_stats('total_users', $user_number, '+');

$cache->clean('data_stats');

$text = sprintf($lang['SYNCING'], $lang['USERS_ST']);
$text .= '<script type="text/javascript"> setTimeout("get_kleeja_link(\'' . ADMIN_PATH . '?cp=maintenance' .  '\');", 2000);</script>' . "\n";

$current_template = 'info.php';


break;


//
//clear all cache
//
case 'clearc':

#clear cache
$cache->clean_all();

#show done, msg
$text = '';
$text .= '<li>' . $lang['REPAIRED_CACHE'] . '</li>';
$text .= '<script type="text/javascript"> setTimeout("get_kleeja_link(\'' . ADMIN_PATH . '?cp=maintenance' .  '\');", 2000);</script>' . "\n";


$current_template = 'info.php';


break;

endswitch;
