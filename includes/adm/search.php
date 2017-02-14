<?php
/**
*
* @package adm
* @version $Id: search.php 2240 2013-12-07 23:22:54Z phpfalcon@gmail.com $
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
$current_template = "search.php";
$action = ADMIN_PATH . "?cp=search";
$default_user_system = (int) $config['user_system'] == 1 ? true : false;

$H_FORM_KEYS	= kleeja_add_form_key('adm_files_search');
$H_FORM_KEYS2	= kleeja_add_form_key('adm_users_search');

$current_smt	= g('smt', 'latin', 'files');


#filling the inputs automatically via GET
$filled_ip = $filled_username = '';
if(ig('s_input'))
{
	$search_input = g('s_input', 'int');
	$search_value = g('s_value', 'str');
  	if($search_input == 2)
	{
		$filled_username = $search_value;
	}
	elseif($search_input == 1)
	{
		$filled_ip = $search_value;
	}
}


if (ip('search_file'))
{
	if(!kleeja_check_form_key('adm_files_search'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], $action);
	}

	#delete all searches greater than 10
	$s_del = array(
							'DELETE'	=> "{$dbprefix}filters",
							'WHERE'		=> "filter_type='file_search' AND filter_user=" . $user->data['id'],
							'ORDER BY'	=> "filter_id DESC",
							'LIMIT'		=> '5, 18446744073709551615'
							);

	$SQL->build($s_del);


	#add as a file_search filter
	$s = $_POST;

	#reduce number of array keys
	unset($s['search_file'], $s['k_form_key'], $s['k_form_time']);
	foreach ($s as $key => $v)
	{
		if ($s[$key] == '')
		{
			unset($s[$key]);
		}
		else
		{
			$s[$key] = clean_var($s[$key], 'str');
		}
	}

	$d = serialize($s);

	if(($search_id = insert_filter(false, $d, 'file_search')))
	{
		$filter = get_filter($search_id, 'file_search', false, 'filter_id');
		redirect(ADMIN_PATH . "?cp=files&search_id=" . $filter['filter_uid']);
	}
	else
	{
		kleeja_admin_err($lang['ERROR_TRY_AGAIN'], $action);
	}
}


if (ip('search_user'))
{
	if(!kleeja_check_form_key('adm_users_search'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], $actiom . '&amp;smt=users');
	}

	#delete all searches greater than 10
	$s_del = array(
							'DELETE'	=> "{$dbprefix}filters",
							'WHERE'		=> "filter_type='user_search' AND filter_user=" . $userinfo['id'],
							'ORDER BY'	=> "filter_id DESC",
							'LIMIT'		=> '5, 18446744073709551615'
							);

	$SQL->build($s_del);


	#add as a user_search filter
	$s = array_map('htmlspecialchars', $_POST);

	#reduce number of array keys
	unset($s['search_user'], $s['k_form_key'], $s['k_form_time']);

	$d = serialize($s);
	if(($search_id = insert_filter(false, $d, 'user_search')))
	{
		$filter = get_filter($search_id, 'user_search', false, 'filter_id');
		redirect(ADMIN_PATH . "?cp=users&smt=show_su&search_id=" . $filter['filter_uid'], false);
	}
	else
	{
		kleeja_admin_err($lang['ERROR_TRY_AGAIN'], $action . '&amp;smt=users', 1);
	}
}

//secondary menu
$go_menu = array(
				'files' => array('name'=>$lang['R_SEARCH'], 'link'=> $action . '&amp;smt=files', 'goto'=>'files', 'current'=> $current_smt == 'files'),
				#'sep1' => array('class'=>'separator'),
				'users' => array('name'=>$lang['SEARCH_USERS'], 'link'=> $action . '&amp;smt=users', 'goto'=>'users', 'current'=> $current_smt == 'users'),
				#'sep2' => array('class'=>'separator'),
	);

if(!$default_user_system)
{
	unset($go_menu['users']);
}
