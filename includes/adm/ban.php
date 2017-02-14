<?php
/**
*
* @package adm
* @version $Id: ban.php 2236 2013-11-30 10:07:23Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/


// not for directly open
if (!defined('IN_ADMIN'))
{
	exit();
}

#template file
$current_template	= "ban.php";
$action				= ADMIN_PATH . '?cp=ban';

#set form keys
$GET_FORM_KEY = kleeja_add_form_key_get('get_adm_ban');
$H_FORM_KEYS= kleeja_add_form_key('adm_ban');


//
// Banning a new ip/username form
//
$ERRORS = array();
if (ip('new_ban_submit'))
{
	if(!kleeja_check_form_key('adm_ban'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], $action, false);
	}

	$data['ban_value'] = p('ban_value', 'str', '');
	$data['ban_type'] = p('ban_type', 'str', 'ip');

	#empty ?
	if($data['ban_value'] == '')
	{
		$ERRORS['ban_value'] = $lang['BAN_VALUE_EMPTY'];
	}

	if(!sizeof($ERRORS))
	{
		#is it valid ip?
		if(function_exists('filter_var') && $data['ban_type'] == 'ip')
		{
			if(filter_var(trim($data['ban_value']), FILTER_VALIDATE_IP) === false)
			{
				$ERRORS['ban_value'] = $lang['BAN_IP_NOT_VALID'];
			}
		}

		#username is valid and existed
		if($data['ban_type'] == 'username')
		{
			if(!$SQL->num($SQL->query("SELECT * FROM {$dbprefix}users WHERE name='" . $SQL->escape(trim($data['ban_value'])) ."'")))
			{
				$ERRORS['ban_value'] = $lang['BAN_USERNAME_NOT_FOUND'];
			}
		}

		if(!sizeof($ERRORS))
		{
			 if(filter_exists(trim($data['ban_value']),'filter_value', 'ban_system'))
			 {
				 $ERRORS['ban_value'] = $lang['BAN_EXISTS_BEFORE'];
			 }
		}
	}

	#no errors yet? then add it
	if(!sizeof($ERRORS))
	{
		$filter_new_id = insert_filter(
									false,
									trim($data['ban_value']),
									'ban_system',
									time(),
									0,
									trim($data['ban_type'])
								);
		if($filter_new_id)
		{
			kleeja_admin_info($lang['BAN_ADDED_SUCCESSFULLY'], $action, false);
			$cache->clean('data_ban');
		}
		else
		{
			kleeja_admin_error($lang['ERROR'], $action, false);
		}
	}
}



//
// Delete a ban rule
//
if (ig('bd'))
{
	if(!kleeja_check_form_key_get('get_adm_ban'))
	{
		kleeja_admin_err($lang['INVALID_GET_KEY'], $action, false);
	}

	$ban_id = g('bd', 'int', 0);

	if($ban_id)
	{
		if(delete_filter($ban_id, 'filter_id', 'ban_system'))
		{
			kleeja_admin_info($lang['BAN_DELETED_SUCCESSFULLY'], $action, false);
			$cache->clean('data_ban');
		}
		else
		{
			kleeja_admin_err($lang['ERROR'], $action, false);
		}

	}
}


$query	= array(
				'SELECT'	=> "filter_id, filter_value, filter_time, filter_status",
				'FROM'		=> "{$dbprefix}filters",
				'WHERE'		=> "filter_type='ban_system'",
				'ORDER BY'	=> "filter_id DESC",
				);


$result = $SQL->build($query);

$ban_list = array();

while($row=$SQL->fetch($result))
{
	$ban_list[$row['filter_id']] = $row;
}
$SQL->free($result);
