<?php
/**
*
* @package adm
* @version $Id: check_update.php 2236 2013-11-30 10:07:23Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/

// not for directly open
if (!defined('IN_ADMIN'))
{
	exit();
}



$current_template	= 'check_update.php';
$current_smt		= g('smt', 'latin', 'general');
$error = false;
$update_link = $config['siteurl'] . 'install/update.php?lang=' . clean_var($config['language'], 'latin');

#to prevent getting the url data for all cats
if($current_smt == 'general'):

#get data from kleeja database
$b_url	= empty($_SERVER['SERVER_NAME']) ? $config['siteurl'] : $_SERVER['SERVER_NAME'];
$b_data = fetch_remote_file('http://www.kleeja.com/check_vers/?i=' . urlencode($b_url) . '&v=' . KLEEJA_VERSION, false, 6);

if ($b_data === false && !ig('show_msg'))
{
	$text	= $lang['ERROR_CHECK_VER'];
	$error	= true;
}
else
{
	//
	// there is a file that we brought it !
	//
	$b_data = @explode('|', $b_data);

	$version_data = trim(clean_var($b_data[0], 'str'));

	if (version_compare(strtolower($version_data), strtolower(KLEEJA_VERSION), '>'))
	{
		$error = true;

		$text = sprintf($lang['UPDATE_NOW_S'] , KLEEJA_VERSION, strtolower($version_data)) . '<br /><br />' . $lang['UPDATE_KLJ_NOW'];
	}
	else if (version_compare(strtolower(KLEEJA_VERSION), strtolower($version_data), '='))
	{
		$text	= $lang['U_LAST_VER_KLJ'];
	}
	else if (version_compare(strtolower($version_data), strtolower(KLEEJA_VERSION), '<'))
	{
		$text = $lang['U_USE_PRE_RE'];
	}


	#lets decode it
	$v = @unserialize($config['new_version']);

	#To prevent expected error [ infinit loop ]
	if(ig('show_msg'))
	{
		$query_get	= array(
							'SELECT'	=> '*',
							'FROM'		=> "{$dbprefix}config",
							'WHERE'		=> "name = 'new_version'"
						);

		$result_get =  $SQL->build($query_get);

		if(!$SQL->num($result_get))
		{
			#add new config value
			add_config('new_version', '');
		}
	}

	$data	= array(
					'version_number'	=> $version_data,
					'last_check'		=> time(),
					'msg_appeared'		=> ig('show_msg') ? true : false,
					'copyrights'		=> !empty($b_data[1]) && strpos($b_data[1], 'yes') !== false ? true : false,
				);

	$data = serialize($data);

	update_config('new_version', $SQL->escape($data), false);
}



#then go back to start
if(ig('show_msg'))
{
	redirect(ADMIN_PATH . '?update_done=1');
	$SQL->close();
	exit;
}

#end current_smt == general
endif;

//secondary menu
$go_menu = array(
				'general' => array('name'=>$lang['R_CHECK_UPDATE'], 'link'=> ADMIN_PATH . '?cp=check_update&amp;smt=general', 'goto'=>'general', 'current'=> $current_smt == 'general'),
				'howto' => array('name'=>$lang['HOW_UPDATE_KLEEJA'], 'link'=> ADMIN_PATH . '?cp=check_update&amp;smt=howto', 'goto'=>'howto', 'current'=> $current_smt == 'howto'),
				'site' => array('name'=>'Kleeja.com', 'link'=> ADMIN_PATH . '?cp=check_update&amp;smt=site', 'goto'=>'site', 'current'=> $current_smt == 'site'),
	);
