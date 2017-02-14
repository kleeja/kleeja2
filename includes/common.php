<?php
/**
*
* @package Kleeja
* @version $Id: common.php 2237 2013-11-30 16:30:29Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/


/**
* @ignore
*/
if (!defined('IN_KLEEJA'))
{
	exit();
}

/**
 * Files in includes folder need this to be accessible
 */
define('IN_COMMON', true);


/**
 * Development stage, KLeeja will treats you as a developer
 */
define('DEV_STAGE', true);
define('DEBUG', true);


/**
 * Error reporting in Development stage are agressive
 */
defined('DEV_STAGE') ? @error_reporting(E_ALL) : @error_reporting(E_ALL ^ E_NOTICE);

#no CACHING while debug
if (defined('DEBUG'))
{

    #refresh cache
    if(($dh = @opendir(PATH . 'cache')) !== false)
    {
     while (($file = readdir($dh)) !== false)
     {
        if($file != "kleeja_log.log")
        {
        	@unlink(PATH . 'cache/' . $file);
        }

     }
     closedir($dh);
    }
	//session_save_path(PATH . 'cache');
}

/**
 * The path of the configuration file of Kleeja
 */
define('KLEEJA_CONFIG_FILE', 'config.php');


/**
 * Set default time zone
 * There is no time difference between Coordinated Universal Time (UTC) and Greenwich Mean Time (GMT).
 * Kleeja supports the changing of time zone through the admin panel, see functions_display.php/kleeja_date()
 */
date_default_timezone_set('GMT');


/**
 * @ignore
 */
if(!defined('PATH'))
{
	if(!defined('__DIR__'))
	{
		define('__DIR__', dirname(__FILE__));
	}
	define('PATH', str_replace(DIRECTORY_SEPARATOR . 'includes', '', __DIR__) . DIRECTORY_SEPARATOR);
}


#start session after setting it right
$s_time = isset($s_time) ? $s_time : 86400 * 2; #two days
$s_path = isset($s_path) ? $s_path : '/';

if (function_exists('session_set_cookie_params'))
{
	session_set_cookie_params($s_time, $s_path);
}

$current_session_id = false;
if(isset($_COOKIE['PHPSESSID']))
{
	$current_session_id = $_COOKIE['PHPSESSID'];
}

if(empty($current_session_id) || !preg_match('/^[a-z0-9\-,]{16,32}$/i', $current_session_id))
{
	#new session id
	$current_session_id = preg_replace('/[^a-z0-9]/', '', uniqid(rand(), true));
	session_id($current_session_id);
}

if(!isset($_SESSION))
{
	session_start();
}

/**
* Get the current microtime, to calculate page speed
*/
function get_microtime()
{
	list($usec, $sec) = explode(' ', microtime());
	return ((float)$usec + (float)$sec);
}

$starttm = get_microtime();



#if no configuration file exists? then go installation
if (!file_exists(PATH . KLEEJA_CONFIG_FILE))
{
	header('Location: ./quick_install.php');
	exit;
}

#load Kleeja configuration file
include PATH . KLEEJA_CONFIG_FILE;

#if no enough config. params, go installation
if (!$dbname || !$dbuser)
{
	header('Location: ./quick_install.php');
	exit;
}

#initiate classes and load functions
$root_path = PATH;
$db_type = isset($db_type) ? $db_type : 'mysqli';

include PATH . 'includes/functions/functions_alternative.php';
include PATH . 'includes/functions/functions.php';
include PATH . 'includes/functions/functions_files.php';
include PATH . 'includes/functions/functions_display.php';
include PATH . 'includes/version.php';

switch ($db_type)
{
	default:
	case 'mysqli':
		include PATH . 'includes/classes/mysqli.php';
	break;
}
#include PATH . 'includes/classes/style.php';
include PATH . 'includes/classes/user.php';
include PATH . 'includes/classes/pagination.php';
include PATH . 'includes/classes/cache.php';
include PATH . 'includes/classes/plugins.php';



if(defined('IN_ADMIN'))
{
	include PATH . 'includes/functions/functions_adm.php';
}


#fix intregation problems
if(empty($script_encoding))
{
	$script_encoding = 'windows-1256';
}

#initiate classes
$SQL	= new database($dbserver, $dbuser, $dbpass, $dbname);
unset($dbpass);
#$tpl	= new kleeja_style;
$usrcp = $user	= new user;
$cache = new cache;
$plugin = new plugins();


#return to the default user system if this given
if(defined('DISABLE_INTR'))
{
	$config['user_system'] = 1;
}

#load cached data
include PATH . 'includes/cache_data.php';


#getting dynamic configs
$query = array(
				'SELECT'	=> 'c.name, c.value',
				'FROM'		=> "{$dbprefix}config c",
				'WHERE'		=> 'c.dynamic = 1',
			);

$result = $SQL->build($query);

while($row=$SQL->fetch($result))
{
	$config[$row['name']] = $row['value'];
}

$SQL->free($result);

#check user or guest
$usrcp->kleeja_check_user();

#+ configs of the current group
$config = array_merge($config, (array) $d_groups[$user->data['group_id']]['configs']);


#no tpl caching in dev stage
#if(defined('DEV_STAGE'))
#{
	#$tpl->caching = false;
#}

#admin path
!defined('ADMIN_PATH') ? define('ADMIN_PATH', $config['siteurl'] . 'admin/') : null;

#Admin style name
!defined('ADMIN_STYLE_NAME') ? define('ADMIN_STYLE_NAME', 'marya') : null;

#site url must end with /
if(!empty($config['siteurl']))
{
	$config['siteurl'] = ($config['siteurl'][strlen($config['siteurl'])-1] != '/') ? $config['siteurl'] . '/' : $config['siteurl'];
}


#set display headers
header('Content-type: text/html; charset=UTF-8');
header('Cache-Control: private, no-cache="set-cookie"');
header('Expires: 0');
header('Pragma: no-cache');

#check the current laguage package
if(!$config['language'] || empty($config['language']))
{
	if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strlen($_SERVER['HTTP_ACCEPT_LANGUAGE']) > 2)
	{
		$config['language'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		if(!file_exists(PATH . 'languages/' . $config['language'] . '/common.php'))
		{
			$config['language'] = 'en';
		}
	}
}

#check the current style
if(!$config['style'] || empty($config['style']))
{
	$config['style'] = 'default';
}

#check h_kay, important for kleeja
if(empty($config['h_key']))
{
	$h_k = sha1(microtime() . rand(0, 100));
	if(!update_config('h_key', $h_k))
	{
		add_config('h_key', $h_k);
	}
}

// $config['style'] = 'default2';

#style of Kleeja
define('STYLE_PATH', $config['siteurl'] . 'styles/' . $config['style'] . '/');
define('STYLE_PATH_ABS', PATH . 'styles/' . $config['style'] . '/');
define('PARENT_STYLE_PATH', $config['siteurl'] . 'styles/' . (trim($config['style_depend_on']) == '' ? $config['style'] : $config['style_depend_on']) . '/');
define('PARENT_STYLE_PATH_ABS', PATH . 'styles/' . (trim($config['style_depend_on']) == '' ? $config['style'] : $config['style_depend_on']) . '/');

#style for admin
define('ADMIN_STYLE_PATH', $config['siteurl'] . 'admin/' . ADMIN_STYLE_NAME . '/');
define('ADMIN_STYLE_PATH_ABS', PATH . 'admin/' . ADMIN_STYLE_NAME . '/');

#get languge of common
get_lang('common');

#ban system
run_ban_system();


#quick_install.php exists, raise a message
if (file_exists(PATH . 'quick_install.php') && !defined('IN_ADMIN') && !defined('IN_LOGIN') && !defined('DEV_STAGE'))
{
	#Different message for admins! delete install folder
	kleeja_info(($user->can('enter_acp') ? $lang['DELETE_INSTALL_FOLDER'] : $lang['WE_UPDATING_KLEEJA_NOW']), $lang['SITE_CLOSED']);
}


#site close message if enabled
$login_page = '';
if ($config['siteclose'] == '1' && !$user->can('enter_acp') && !defined('IN_LOGIN') && !defined('IN_ADMIN'))
{
	#if download, images ?
	if(defined('IN_DOWNLOAD') && (ig('img') || ig('thmb') || ig('thmbf') || ig('imgf')))
	{
		@$SQL->close();
		$fullname = "images/site_closed.jpg";
		$filesize = filesize($fullname);
		header("Content-length: $filesize");
		header("Content-type: image/jpg");
		readfile($fullname);
		exit;
	}

	#Send a 503 HTTP response code to prevent search bots from indexing the maintenace message
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	kleeja_info($config['closemsg'], $lang['SITE_CLOSED']);
}

#exceed total size
if ((isset($stats['total_sizes']) && $stats['total_sizes'] >= ($config['total_size'] *(1048576))) && !defined('IN_LOGIN') && !defined('IN_ADMIN'))
{
	// Send a 503 HTTP response code to prevent search bots from indexing the maintenace message
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	kleeja_info($lang['SIZES_EXCCEDED'], $lang['STOP_FOR_SIZE']);
}


#check for rows per page number
if(empty($perpage) || intval($perpage) == 0)
{
	$perpage = 14;
}

#captcha file path
$captcha_file_path = $config['siteurl'] . 'captcha.php';


($hook = $plugin->run_hook('end_common')) ? eval($hook) : null; //run hook
