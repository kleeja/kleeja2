<?php
/**
*
* @package adm
* @version $Id: users.php 2240 2013-12-07 23:22:54Z phpfalcon@gmail.com $
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
$current_template	= "users.php";
$current_smt	= g('smt', 'latin', 'general');
$action			= ADMIN_PATH . '?cp=users' . (ig('page')  ? '&amp;page=' . g('page', 'int', 1) : '');
$action			.= (ig('search_id') ? '&amp;search_id=' . g('search', 'str', '') : '');
$action			.= (ig('qg') ? '&amp;qg=' . g('qg', 'int', '') : '') . '&amp;smt=' . $current_smt;
$action_all		= ADMIN_PATH . '?cp=users'  . '&amp;smt=' . $current_smt . (ig('page') ? '&amp;page=' . g('page', 'int', 1) : '');
$action_start	= ADMIN_PATH . '?cp=users';

$is_search		= $affected = $is_asearch = $GE_INFO = false;
$isn_search		= true;

#use plugin system for this
$user_not_normal = false;

#form keys
$GET_FORM_KEY	= kleeja_add_form_key_get('adm_users');
$H_FORM_KEYS	= kleeja_add_form_key('adm_users');
$H_FORM_KEYS2	= kleeja_add_form_key('adm_users_newuser');
$H_FORM_KEYS3	= kleeja_add_form_key('adm_users_newgroup');
$H_FORM_KEYS4	= kleeja_add_form_key('adm_users_delgroup');
$H_FORM_KEYS5	= kleeja_add_form_key('adm_users_editacl');
$H_FORM_KEYS6	= kleeja_add_form_key('adm_users_editdata');
$H_FORM_KEYS7	= kleeja_add_form_key('adm_users_editexts');
$H_FORM_KEYS8	= kleeja_add_form_key('adm_users_edituser');

//
// Check form key
//
$forms_vars_keys = array('submit'=>'adm_users', 'newuser'=>'adm_users_newuser', 'edituser'=>'adm_users_edituser',
					'delgroup'=>'adm_users_delgroup', 'newgroup'=>'adm_users_newgroup', 'editacl'=>'adm_users_editacl',
					'editdata'=>'adm_users_editdata', 'newext'=>'adm_users_editexts', 'editexts'=>'adm_users_editexts');

foreach ($forms_vars_keys as $submit_var => $form_key)
{
	if (ip($submit_var))
	{
		if(!kleeja_check_form_key($form_key))
		{
			kleeja_admin_err($lang['INVALID_FORM_KEY'], $action . (ip('edituser') ? '&uid=' . g('uid', 'int', 0) : ''), 1);
		}
	}
}


//
// delete all user's files [only one user]
//
if(ig('deleteuserfile'))
{
	#check _GET Csrf token
	if(!kleeja_check_form_key_get('adm_users'))
	{
		kleeja_admin_err($lang['INVALID_GET_KEY'], $action_all, true);
	}

	$user_id = g('deleteuserfile', 'int', 0);

	#does the user exist?
	if(!$SQL->num($SQL->query("SELECT * FROM {$dbprefix}users WHERE id=" . $user_id)))
	{
		redirect($action_all);
	}

	$query = array(
					'SELECT'	=> 'size, name, folder',
					'FROM'		=> "{$dbprefix}files",
					'WHERE'		=> 'user=' . $user_id,
				);

	$result = $SQL->build($query);

	$sizes = $num = 0;
	while($row=$SQL->fetch($result))
	{
		#delete from folder ..
		@kleeja_unlink (PATH . $row['folder'] . "/" . $row['name']);
		#delete thumb
		if (file_exists(PATH . $row['folder'] . "/thumbs/" . $row['name']))
		{
			@kleeja_unlink (PATH . $row['folder'] . "/thumbs/" . $row['name']);
		}

		$num++;
		$sizes += $row['size'];
	}

	$SQL->free($result);

	if($num == 0)
	{
		kleeja_admin_err($lang['ADMIN_DELETE_NO_FILE'], $action_all, true);
	}
	else
	{
		#update number of stats
		update_stats('total_files', $num, '-');
		update_stats('total_sizes', $sizes, '-');

		$cache->clean('data_stats');


		//delete all files in just one query
		$d_query	= array(
							'DELETE'	=> "{$dbprefix}files",
							'WHERE'		=> "user=" . $user_id,
							);

		$SQL->build($d_query);

		kleeja_admin_info($lang['ADMIN_DELETE_FILE_OK'], $action_all, true);
	}
}

//
// Delete a user account
//
if(ig('del_user'))
{
	#check _GET Csrf token
	if(!kleeja_check_form_key_get('adm_users'))
	{
		kleeja_admin_err($lang['INVALID_GET_KEY'], $action_all, true);
	}

	$user_id = g('del_user', 'int', 0);

	#does the user exist?
	if(!$SQL->num($SQL->query("SELECT * FROM {$dbprefix}users WHERE id=" . $user_id)))
	{
		redirect($action_all);
	}

	#delete all files in just one query
	$d_query	= array(
						'DELETE'	=> "{$dbprefix}users",
						'WHERE'		=> "id=" . $user_id,
						);

	$SQL->build($d_query);


	// TODO: update username in files table to be 'deleted user'

	kleeja_admin_info($lang['USER_DELETED'], $action_all, true);
}


//
// add new user
//
else if (ip('newuser'))
{
	$data = array(
			'lname'	=> p('lname', 'str', ''),
			'lpass'	=> p('lpass', 'str', ''),
			'lmail'	=> p('lmail', 'email', false),
			'lgroup'=> p('lgroup', 'int', 3)
	);


	if ($data['lname'] == '' || $data['lpass'] == '')
	{
		$ERRORS[] = $lang['EMPTY_FIELDS'];
	}
	else if (!$data['lmail'])
	{
		$ERRORS[] = $lang['WRONG_EMAIL'];
	}
	else if (strlen($data['lname']) < 2 || strlen($data['lname']) > 25)
	{
		$ERRORS[] = str_replace('4', '2', $lang['WRONG_NAME']);
	}
	else if ($SQL->num($SQL->query("SELECT * FROM {$dbprefix}users WHERE clean_name='" . $SQL->escape($usrcp->cleanusername($data['lname'])) . "'")) != 0)
	{
		$ERRORS[] = $lang['EXIST_NAME'];
	}
	else if ($SQL->num($SQL->query("SELECT * FROM {$dbprefix}users WHERE mail='" . $SQL->escape(strtolower($data['lmail'])) . "'")) != 0)
	{
		$ERRORS[] = $lang['EXIST_EMAIL'];
	}

	#no errors, then add a new user
	if(empty($ERRORS))
	{
		$name			= (string) $SQL->escape($data['lname']);
		$user_salt		= (string) substr(base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
		$pass			= (string) $usrcp->kleeja_hash_password($SQL->escape($data['lpass']) . $user_salt);
		$mail			= (string) $data['lmail'];
		$clean_name		= (string) $usrcp->cleanusername($name);
		$group			= (int) $data['lgroup'];

		$insert_query	= array(
								'INSERT'	=> 'name ,password, password_salt ,group_id, mail,founder, session_id, clean_name',
								'INTO'		=> "{$dbprefix}users",
								'VALUES'	=> "'$name', '$pass', '$user_salt', $group , '$mail', 0 , '', '$clean_name'"
						);

		if($SQL->build($insert_query))
		{
			$last_user_id = $SQL->id();
			update_stats('total_users');
			$cache->clean('data_stats');
		}

		#User added
		kleeja_admin_info($lang['USER_ADDED'], $action_start . '&smt=show_group&qg=' . $group, true);
	}
	else
	{
		$errs =	'';
		foreach($ERRORS as $r)
		{
			$errs .= '- ' . $r . '. <br />';
		}

		$current_smt = 'new_u';
	}
}


//
// edit user
//
if(ip('edituser'))
{
	$data = array(
			'uid'			=>	p('uid', 'int', 0),
			'l_name'		=>	p('l_name', 'str', ''),
			'l_pass'		=>	p('l_pass', 'str', ''),
			'l_mail'		=>	p('l_mail', 'email', false),
			'l_group'		=>	p('lgroup', 'int', 3),
			'l_founder'		=>	p('l_founder', 'int', 0),
			'l_show_filecp'	=>	p('l_show_filecp', 'int', 0)
	);

	#does the user exist?
	if(!$SQL->num($SQL->query("SELECT id FROM {$dbprefix}users WHERE id=" . $data['uid'])))
	{
		kleeja_admin_err('ERROR-NO-ID', ADMIN_PATH . '?cp=users', true);
	}

	$query = array(
					'SELECT'	=> 'name, mail, clean_name, group_id, founder, show_my_filecp',
					'FROM'		=> "{$dbprefix}users",
					'WHERE'		=> 'id=' . $data['uid'],
				);

	$result = $SQL->build($query);
	$udata = $SQL->fetch($result);
	$SQL->free($result);

	$new_clean_name = $SQL->escape($usrcp->cleanusername($data["l_name"]));

	$new_name = $new_mail = false;
	$pass  = '';

	if ($data['l_name'] == '')
	{
		$ERRORS[] = $lang['EMPTY_FIELDS'] . ' (' . $lang['USERNAME'] . ')';
	}
	else if(!$data['l_mail'])
	{
		$ERRORS[] = $lang['WRONG_EMAIL'];
	}
	elseif($udata['clean_name'] != $new_clean_name)
	{
		$new_name = true;
		if (strlen($data['l_name']) < 2 || strlen($data['l_name']) > 100)
		{
			$ERRORS[] = str_replace('4', '2', $lang['WRONG_NAME']);
		}
		else if ($SQL->num($SQL->query("SELECT * FROM {$dbprefix}users WHERE clean_name='" . $new_clean_name . "'")) != 0)
		{
			$ERRORS[] = $lang['EXIST_NAME'];
		}
	}
	else if ($udata['mail'] != $data['l_mail'])
	{
		$new_mail = true;
		if($SQL->num($SQL->query("SELECT * FROM {$dbprefix}users WHERE mail='" . $SQL->escape($data["l_mail"]) . "'")) != 0)
		{
			$ERRORS[] = $lang['EXIST_EMAIL'];
		}
	}
	else if ($data['l_pass'] != '')
	{
		$user_salt	= substr(kleeja_base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
		$pass		= "password = '" . $usrcp->kleeja_hash_password($data['l_pass'] . $user_salt) . "', password_salt='" . $user_salt . "',";
	}

	//no errors, lets do process
	if(empty($ERRORS))
	{
		$update_query	= array(
									'UPDATE'	=> "{$dbprefix}users",
									'SET'		=>  ($new_name ? "name = '" . $SQL->escape($data['l_name']) . "', clean_name='" . $SQL->escape($new_clean_name) . "', " : '') .
													($new_mail ? "mail = '" . $SQL->escape($data['l_mail']) . "'," : '') .
													$pass .
													(ip('l_founder') ? "founder=" . $data['l_founder'] . "," : '') .
													"group_id=" . $data['l_group'] . "," .
													"show_my_filecp=" . $data['l_show_filecp'],
									'WHERE'		=>	'id=' . $data['uid']
								);

		$SQL->build($update_query);

		kleeja_admin_info(($SQL->affected() ? $lang['USER_UPDATED'] : $lang['NO_UP_CHANGE_S']), ADMIN_PATH . '?cp=users&smt=show_group&qg=' . p('l_qg', 'int', 0) . '&page='. p('l_page', 'int', 0), true);
	}
	else
	{
		$errs =	'';
		foreach($ERRORS as $r)
		{
			$errs .= '- ' . $r . '. <br />';
		}

		$current_smt = 'edit_user';
	}
}


//
//add new group
//
if(ip('newgroup'))
{
	$data = array(
		'gname'	=> p('gname', 'str', ''),
		'cfrom'	=> p('cfrom', 'int', 0)
	);

	if ($data['gname'] == '')
	{
		$ERRORS[] = $lang['EMPTY_FIELDS'];
	}
	else if (strlen($data['gname']) < 2 || strlen($data['gname']) > 100)
	{
		$ERRORS[] = str_replace('4', '1', $lang['WRONG_NAME']);
	}
	else if ($SQL->num($SQL->query("SELECT * FROM {$dbprefix}groups WHERE group_name='" . $SQL->escape($data["gname"]) . "'")) != 0)
	{
		$ERRORS[] = $lang['EXIST_NAME'];
	}
	elseif (in_array(strtolower($data['gname']), array_map('strtolower', array($lang['ADMINS'], $lang['GUESTS'], $lang['USERS']))))
	{
		$ERRORS[] = $lang['TAKEN_NAMES'];
	}

	#no errors, lets do process
	if(empty($ERRORS))
	{
		#Insert the group ..
		$insert_query	= array(
									'INSERT'	=> 'group_name',
									'INTO'		=> "{$dbprefix}groups",
									'VALUES'	=> "'" . $SQL->escape($data["gname"]) . "'"
							);

		$SQL->build($insert_query);

		#Then, get the ID
		$new_group_id = $SQL->id();
		$org_group_id = $data['cfrom'];
		if(!$new_group_id or !$org_group_id)
		{
			kleeja_admin_err('ERROR-NO-ID', ADMIN_PATH . '?cp=users');
		}
		if($org_group_id == -1)
		{
			$org_group_id = (int) $config['default_group'];
		}

		#copy acls from the other group to this group
		$query = array(
						'SELECT'	=> 'acl_name, acl_can',
						'FROM'		=> "{$dbprefix}groups_acl",
						'WHERE'		=> 'group_id=' . $org_group_id,
						'ORDER BY'	=> 'acl_name ASC'
				);
		$result = $SQL->build($query);

		while($row=$SQL->fetch($result))
		{
			$insert_query	= array(
										'INSERT'	=> 'acl_name, acl_can, group_id',
										'INTO'		=> "{$dbprefix}groups_acl",
										'VALUES'	=>  "'" . $row['acl_name'] . "', " . $row['acl_can'] . ", " . $new_group_id
								);
			$SQL->build($insert_query);
		}
		$SQL->free($result);

		#copy configs from the other group to this group
		$query = array(
						'SELECT'	=> 'd.name, d.value',
						'FROM'		=> "{$dbprefix}groups_data d",
						'WHERE'		=> 'd.group_id=' . $org_group_id,
						'ORDER BY'	=> 'd.name ASC'
				);
		$result = $SQL->build($query);

		while($row=$SQL->fetch($result))
		{
			$insert_query	= array(
										'INSERT'	=> 'name, value, group_id',
										'INTO'		=> "{$dbprefix}groups_data",
										'VALUES'	=>  "'" . $row['name'] . "', '" . $SQL->escape($row['value']) . "', " . $new_group_id
								);
			$SQL->build($insert_query);
		}
		$SQL->free($result);

		#copy exts from the other group to this group
		$query = array(
						'SELECT'	=> 'e.ext, e.size',
						'FROM'		=> "{$dbprefix}groups_exts e",
						'WHERE'		=> 'e.group_id=' . $org_group_id,
						'ORDER BY'	=> 'e.ext_id ASC'
				);
		$result = $SQL->build($query);

		while($row=$SQL->fetch($result))
		{
			$insert_query	= array(
										'INSERT'	=> 'ext, size, group_id',
										'INTO'		=> "{$dbprefix}groups_exts",
										'VALUES'	=>  "'" . $row['ext'] . "', " . $row['size'] . ", " . $new_group_id
								);
			$SQL->build($insert_query);
		}
		$SQL->free($result);

		#show group-is-added message
		$cache->clean('data_groups');
		kleeja_admin_info(sprintf($lang['GROUP_ADDED'], $data['gname']),  $action_start . '&amp;smt=group_data&qg=' . $new_group_id);
	}
	else
	{
		$errs =	'';
		foreach($ERRORS as $r)
		{
			$errs .= '- ' . $r . '. <br />';
		}

		kleeja_admin_err($errs, $action, true);
	}
}

//
//delete group
//
if(ip('delgroup'))
{
	$from_group = p('dgroup', 'int', 0);
	$to_group = p('tgroup', 'int', 0);

	#if missing IDs of groups, deleted one and transfering-to one.
	if(!$from_group || !$to_group)
	{
		kleeja_admin_err('ERROR-NO-ID', $action_start);
	}

	#We can not move users to the same group we deleting ! that's stupid pro!
	if($from_group == $to_group)
	{
		kleeja_admin_err($lang['NO_MOVE_SAME_GRP'], $action_start);
	}

	#to_group = '-1' : means default group .. so now we get the real ID.
	if($to_group == -1)
	{
		$to_group = (int) $config['default_group'];
	}

	#you can not delete default group !
	if($from_group == (int) $config['default_group'])
	{
		kleeja_admin_err($lang['DEFAULT_GRP_NO_DEL'], $action_start);
	}

	#delete the exts
	$query_del	= array(
							'DELETE'	=> "{$dbprefix}groups_exts",
							'WHERE'		=> 'group_id=' . $from_group
						);

	$SQL->build($query_del);
	#then, delete the configs
	$query_del	= array(
							'DELETE'	=> "{$dbprefix}groups_data",
							'WHERE'		=> 'group_id=' . $from_group
						);

	$SQL->build($query_del);
	#then, delete acls
	$query_del	= array(
							'DELETE'	=> "{$dbprefix}groups_acl",
							'WHERE'		=> 'group_id=' . $from_group
						);

	$SQL->build($query_del);
	#then, delete the group itself
	$query_del	= array(
							'DELETE'	=> "{$dbprefix}groups",
							'WHERE'		=> 'group_id=' . $from_group
						);

	$SQL->build($query_del);
	#then, move users to the dest. group
	$update_query = array(
							'UPDATE'	=> "{$dbprefix}users",
							'SET'		=> "group_id=" . $to_group,
							'WHERE'		=> "group_id=". $from_group
						);

	$SQL->build($update_query);

	#get those groups name
	$group_name_from	= get_group_name($from_group);
	$group_name_to		= get_group_name($to_group);

	#delete cache ..
	$cache->clean('data_groups');
	kleeja_admin_info(sprintf($lang['GROUP_DELETED'], $group_name_from, $group_name_to), $action_start);
}

//
//begin of default users page
//
$query = array();
$show_results = false;
switch($current_smt):

case 'general':

	$query = array(
					'SELECT'	=> 'COUNT(group_id) AS total_groups',
					'FROM'		=> "{$dbprefix}groups",
					'ORDER BY'	=> 'group_id ASC'
			);

	$result = $SQL->build($query);

	$nums_rows = 0;
	$n_fetch = $SQL->fetch($result);
	$nums_rows = $n_fetch['total_groups'];
	$no_results = false;
	$e_groups	= $c_groups = array();
	$l_groups	= array();


	if ($nums_rows > 0)
	{
		$query['SELECT'] =	'group_id, group_name, group_is_default, group_is_essential';

		$result = $SQL->build($query);

		while($row=$SQL->fetch($result))
		{
			$r = array(
						'id'	=> $row['group_id'],
						'name'	=> get_group_name($row['group_name'], true),
						'is_default'	=> (int) $row['group_is_default'] ? true : false
				);

			if((int) $row['group_is_essential'] == 1)
			{
				$e_groups[] = $r;
			}
			else
			{
				$c_groups[] = $r;
			}
		}
	}

	if($user_not_normal)
	{
		$c_groups = false;
	}

	$SQL->free($result);

break;

#handling editing ACLs(permissions) for the requesting groups
case 'group_acl':

	$req_group = g('qg', 'int', 0);

	if(!$req_group)
	{
		kleeja_admin_err('ERROR-NO-ID', $action_start);
	}

	$group_name	= get_group_name($req_group);


	$query = array(
					'SELECT'	=> 'acl_name, acl_can',
					'FROM'		=> "{$dbprefix}groups_acl",
					'WHERE'		=> 'group_id=' . $req_group,
					'ORDER BY'	=> 'acl_name ASC'
			);

	$result = $SQL->build($query);

	$acls = $submitted_on_acls = $submitted_off_acls = array();
	while($row=$SQL->fetch($result))
	{
		#if submit
		if(ip('editacl'))
		{
			if(p($row['acl_name'], 'int', 0) == 1)
			{
				$submitted_on_acls[] = $row['acl_name'];
			}
			else if(!p($row['acl_name'], 'int', 0))
			{
				$submitted_off_acls[] = $row['acl_name'];
			}
		}

		#Guests are no meant for this!
		if($req_group == 2 && in_array($row['acl_name'], array('access_fileuser', 'enter_acp')))
		{
			continue;
		}

		$acls[] = array(
						'acl_title'	=> $lang['ACLS_' .  strtoupper($row['acl_name'])],
						'acl_name'	=> $row['acl_name'],
						'acl_can'	=> (int) $row['acl_can']
				);

	}

	$SQL->free($result);

	#if submit
	if(ip('editacl'))
	{
		#update 'can' acls
		if(sizeof($submitted_on_acls))
		{
			$update_query = array(
									'UPDATE'	=> "{$dbprefix}groups_acl",
									'SET'		=> "acl_can=1",
									'WHERE'		=> "acl_name IN ('" . implode("', '", $submitted_on_acls) . "') AND group_id=". $req_group
								);

			$SQL->build($update_query);
		}

		#update 'can not' acls
		if(sizeof($submitted_off_acls))
		{
			$update_query2 = array(
									'UPDATE'	=> "{$dbprefix}groups_acl",
									'SET'		=> "acl_can=0",
									'WHERE'		=> "acl_name IN ('" . implode("', '", $submitted_off_acls) . "') AND group_id=". $req_group
								);

			$SQL->build($update_query2);
		}

		#delete cache ..
		$cache->clean('data_groups');

		kleeja_admin_info($lang['CONFIGS_UPDATED'], $action_start);
	}
break;

#handling editing settings for the requested group
case 'group_data':

	$req_group = g('qg', 'int', 0);

	if(!$req_group && !ig('lang_change'))
	{
		kleeja_admin_err('ERROR-NO-ID', $action_start);
	}


	# When user change langauge from start page, hurry hurry section, he/she comes here
	# this part has nothing with the other thing below
	if(ig('lang_change'))
	{
		#check _GET Csrf token
		if(!kleeja_check_form_key_get('adm_start_actions'))
		{
			kleeja_admin_err($lang['INVALID_GET_KEY'], ADMIN_PATH . '?cp=start');
		}

		$got_lang = g('lang_change', 'latin', '');

		# -1 means all
		if($req_group == -1)
		{
			update_config('language', $got_lang);
			$group_name = $lang['ALL'];
		}
		else
		{
			update_config('language', $got_lang, true, $req_group);
			$group_name	= get_group_name($req_group);
		}

		#msg, done
		kleeja_admin_info($lang['CONFIGS_UPDATED'] . ', ' . $lang['LANGUAGE']  . ':' .
							$got_lang . ' - ' . $lang['FOR'] . ':' . $group_name, ADMIN_PATH . '?cp=start');
	}


	$group_name	= get_group_name($req_group);
	$gdata		= $d_groups[$req_group]['data'];

	$query = array(
					'SELECT'	=> 'c.name, c.option, c.value',
					'FROM'		=> "{$dbprefix}config c",
					'WHERE'		=> "c.type='groups'",
					'ORDER BY'	=> 'c.display_order ASC'
			);

	$result = $SQL->build($query);

	$data = array();
	$cdata= $con = $d_groups[$req_group]['configs'];
	$STAMP_IMG_URL = file_exists(PATH . 'images/watermark.gif') ? PATH . 'images/watermark.gif' : PATH . 'images/watermark.png';

	while($row=$SQL->fetch($result))
	{
		#submit, why here ? dont ask me just accept it as it.
		if(ip('editdata'))
		{
			($hook = $plugin->run_hook('after_submit_adm_users_groupdata')) ? eval($hook) : null; //run hook

			$new[$row['name']] = p($row['name'], 'str', $row['value']);

			$update_query = array(
									'UPDATE'	=> "{$dbprefix}groups_data",
									'SET'		=> "value='" . $SQL->escape($new[$row['name']]) . "'",
									'WHERE'		=> "name='" . $row['name'] . "' AND group_id=". $req_group
								);

			$SQL->build($update_query);
			continue;
		}

		if($row['name'] == 'language')
		{
			//get languages
			if ($dh = @opendir(PATH . 'lang'))
			{
				while (($file = readdir($dh)) !== false)
				{
					if(strpos($file, '.') === false && $file != '..' && $file != '.')
					{
						$lngfiles .= '<option ' . ($d_groups[$req_group]['configs']['language'] == $file ? 'selected="selected"' : '') . ' value="' . $file . '">' . $file . '</option>' . "\n";
					}
				}
				@closedir($dh);
			}
		}

		if($req_group == 2 && in_array($row['name'], array('enable_userfile')))
		{
			continue;
		}

		$option_value = '';
		if(trim($row['option']) != '')
		{

			$option_value = preg_replace_callback(
			'!\{([a-z]+)\.([a-zA-Z0-9-_]+)(\.([a-zA-Z0-9_-]+))?\}!',
			'parse_options',
			$row['option']);

		}

		$data[$row['name']] = array(
					'label'		=> (!empty($lang[strtoupper($row['name'])]) ? $lang[strtoupper($row['name'])] : $olang[strtoupper($row['name'])]),
					'option'		 => '<div class="form-group">' . "\n" .
										'<label for="' . $row['name'] . '">' . (!empty($lang[strtoupper($row['name'])]) ? $lang[strtoupper($row['name'])] : strtoupper($row['name'])) . '</label>' . "\n" .
										'' . $option_value . '' . "\n" .
										'</div>' . "\n" . '',
					'name'		=> $row['name'],
				);

	}
	$SQL->free($result);

	#submit
	if(ip('editdata'))
	{
		#Remove group_is_default from the current one
		if(p('group_is_default', 'int', 0) == 1)
		{
			$update_query = array(
									'UPDATE'	=> "{$dbprefix}groups",
									'SET'		=> "group_is_default=0",
									'WHERE'		=> "group_is_default=1"
									);
			$SQL->build($update_query);

			#update config value of the current default group
			update_config('default_group', $req_group);
			$cache->clean('data_config');
		}

		#update not-configs data
		$update_query = array(
								'UPDATE'	=> "{$dbprefix}groups",
								'SET'		=> "group_is_default=" . p('group_is_default', 'int', 0) . (ip('group_name') ? ", group_name='" . $SQL->escape(p('group_name', 'str', '--empty--')) . "'" : ''),
								'WHERE'		=> "group_id=". $req_group
								);
		$SQL->build($update_query);

		#delete cache ..
		$cache->clean('data_groups');

		kleeja_admin_info($lang['CONFIGS_UPDATED'], $action_start);
	}

break;

#handling adding-editing allowed file extensions for requested group
case 'group_exts':

	$req_group = g('qg', 'int', 0);

	if(!$req_group)
	{
		kleeja_admin_err('ERROR-NO-ID', $action_start);
	}

	$group_name	= get_group_name($req_group);

	#check if there is klj_exts which means this is an upgraded website !
	if(empty($config['exts_upraded1_5']))
	{
		$ex_exts = $SQL->query("SHOW TABLES LIKE '{$dbprefix}exts';");
		if($SQL->num($ex_exts))
		{
			$xquery = array(
							'SELECT'	=> 'ext, gust_size, user_size, gust_allow, user_allow',
							'FROM'		=> "{$dbprefix}exts",
							'WHERE'		=> 'gust_allow=1 OR user_allow=1',
					);

			$xresult = $SQL->build($xquery);

			$xexts = '';
			while($row=$SQL->fetch($xresult))
			{
				if($row['gust_allow'])
				{
					$xexts .= ($xexts == '' ? '' : ',') . "('" . $SQL->escape($row['ext']) . "', 2, " . $row['gust_size'] . ")";
				}

				if($row['user_allow'])
				{
					$xexts .= ($xexts == '' ? '' : ',') . "('" . $SQL->escape($row['ext']) . "', 3, " . $row['user_size'] . ")";
				}
			}

			$SQL->free($result);

			#delete prev exts before adding
			$query_del	= array(
								'DELETE'	=> "{$dbprefix}groups_exts",
								'WHERE'		=> 'group_id=2 OR group_id=3'
							);

			$SQL->build($query_del);

			$SQL->query("INSERT INTO {$dbprefix}groups_exts (ext, group_id, size) VALUES " . $xexts . ";");

			add_config('exts_upraded1_5', 'done');
		}
	}

	#delete ext?
	$DELETED_EXT = $GE_INFO = false;
	if(ig('del'))
	{
		//check _GET Csrf token
		if(!kleeja_check_form_key_get('adm_users'))
		{
			kleeja_admin_err($lang['INVALID_GET_KEY'], $action);
		}

		$req_ext = g('del', 'int', 0);

		if(!$req_ext)
		{
			kleeja_admin_err('ERROR-NO-EXT-ID', $action);
		}

		$query_del	= array(
							'DELETE'	=> "{$dbprefix}groups_exts",
							'WHERE'		=> 'ext_id=' . $req_ext
						);

		$SQL->build($query_del);

		#done
		$DELETED_EXT = $GE_INFO = 2;
		$cache->clean('data_groups');
	}

	#add ext?
	$ADDED_EXT = false;
	if(ip('newext'))
	{
		$new_ext = ip('extisnew') ? preg_replace('/[^a-z0-9]/', '', strtolower(p('extisnew', 'str', ''))) : false;

		if(!$new_ext)
		{
			kleeja_admin_err($lang['EMPTY_EXT_FIELD'], $action_start . '&smt=group_exts&gq=' . $req_group);
		}

		//check if it's welcomed one
		//if he trying to be smart, he will add like ext1.ext2.php
		//so we will just look at last one
		$check_ext = strtolower(array_pop(explode('.', $new_ext)));
		$not_welcomed_exts = array('php', 'php3', 'php5', 'php4', 'asp', 'aspx', 'shtml', 'html', 'htm', 'xhtml', 'phtml', 'pl', 'cgi', 'ini', 'htaccess', 'sql', 'txt');
		if(in_array($check_ext, $not_welcomed_exts))
		{
			kleeja_admin_err(sprintf($lang['FORBID_EXT'], $check_ext), $action);
		}

		//check if there is any exists of this ext in db
		$query = array(
						'SELECT'	=> '*',
						'FROM'		=> "{$dbprefix}groups_exts",
						'WHERE'		=> "ext='" . $new_ext . "' and group_id=" . $req_group,
					);

		$result = $SQL->build($query);

		if ($SQL->num($result))
		{
			kleeja_admin_err(sprintf($lang['NEW_EXT_EXISTS_B4'], $new_ext), $action);
		}

		#add
		$default_size = '2097152';#bytes
		$insert_query	= array(
								'INSERT'	=> 'ext ,group_id, size',
								'INTO'		=> "{$dbprefix}groups_exts",
								'VALUES'	=> "'$new_ext', $req_group, $default_size"
							);

		$SQL->build($insert_query);

		#done
		$ADDED_EXT = $GE_INFO =  2;
		$cache->clean('data_groups');
	}

	#if submit/update
	if(ip('editexts'))
	{
		#I trust this _POST, the only post in this file
		$ext_ids = $_POST['size'];

		if(is_array($ext_ids))
		{
			foreach($ext_ids as $e_id=>$e_val)
			{
				$update_query = array(
										'UPDATE'	=> "{$dbprefix}groups_exts",
										'SET'		=> "size=" . (intval($e_val)*1024),
										'WHERE'		=> "ext_id=" . intval($e_id) . " AND group_id=". $req_group
										);
				$SQL->build($update_query);
			}

			#delete cache ..
			$cache->clean('data_groups');

			kleeja_admin_info($lang['UPDATED_EXTS'], $action);
		}
	}

	#show exts
	$query = array(
					'SELECT'	=> 'ext_id, ext, size',
					'FROM'		=> "{$dbprefix}groups_exts",
					'WHERE'		=> 'group_id=' . $req_group,
					'ORDER BY'	=> 'ext_id ASC'
			);

	$result = $SQL->build($query);

	$exts = array();
	while($row=$SQL->fetch($result))
	{
		$exts[] = array(
						'ext_id'	=> $row['ext_id'],
						'ext_name'	=> $row['ext'],
						'ext_size'	=> round((int) $row['size'] / 1024),
						'ext_icon'	=> file_exists(PATH . "images/filetypes/".  $row['ext'] . ".png") ? PATH . "images/filetypes/" . $row['ext'] . ".png" : PATH. 'images/filetypes/file.png'
			);
	}
	$SQL->free($result);


break;

#show users (from search keyword)
case 'show_su':

	$filter = get_filter(p('search_id', 'str', ''), 'filter_uid');

	if(!$filter)
	{
		kleeja_admin_err($lang['ERROR_TRY_AGAIN'], $action_start);
	}

	$search	= unserialize(htmlspecialchars_decode($filter['filter_value']));

	$usernamee	= $search['username'] != '' ? 'AND (name  LIKE \'%' . $SQL->escape($search['username']) . '%\' OR clean_name LIKE \'%' . $SQL->escape($search['username']) . '%\') ' : '';
	$usermailee	= $search['usermail'] != '' ? 'AND mail  LIKE \'%' . $SQL->escape($search['usermail']) . '%\' ' : '';
	$is_search	= true;
	$isn_search	= false;
	$query['WHERE']	=	"name <> '' $usernamee $usermailee";

#show users (for requested group)
case 'show_group':
	if($current_smt != 'show_su')
	{
		$is_search	= true;
		$isn_search	= false;
		$is_asearch = true;
		$req_group	= g('qg', 'int', 0);
		$group_name	= get_group_name($req_group);

		$query['WHERE']	= "name != '' AND group_id =  " . $req_group;
	}

#show users (all)
case 'users':

	$query['SELECT']	= 'COUNT(id) AS total_users';
	$query['FROM']		= "{$dbprefix}users";
	$query['ORDER BY']	= 'id ASC';

	$result = $SQL->build($query);

	$nums_rows = 0;
	$n_fetch = $SQL->fetch($result);
	$nums_rows = $n_fetch['total_users'];

	//pagination
	$currentPage	= g('page', 'int', 1);
	$Pager			= new pagination($perpage, $nums_rows, $currentPage);
	$start			= $Pager->get_start_row();

	$no_results = false;
	$arr = array();

	if ($nums_rows > 0)
	{
		$query['SELECT'] =	'id, name, founder, group_id, last_visit';
		$query['LIMIT']	=	"$start, $perpage";

		$result = $SQL->build($query);

		while($row=$SQL->fetch($result))
		{
			$userfile =  $config['siteurl'] . ($config['mod_writer'] ? 'fileuser-' . $row['id'] . '.html' : 'ucp.php?go=fileuser&amp;id=' . $row['id']);

			$arr[$row['id']]	= array(
						'id'		=> $row['id'],
						'name'		=> $row['name'],
						'userfile_link' => $userfile,
						'delusrfile_link'	=> $row['founder'] && (int) $user->data['founder'] == 0 ? false : ADMIN_PATH .'?cp=users' . '&amp;deleteuserfile='. $row['id'] . (ig('page') ? '&amp;page=' . g('page', 'int', 1) : ''),
						'delusr_link'		=> $user->data['id'] == $row['id'] || ($row['founder'] && (int) $user->data['founder'] == 0) ? false : ADMIN_PATH .'?cp=users' . '&amp;del_user='. $row['id'] . (ig('page') ? '&amp;page=' . g('page', 'int', 1) : ''),
						'editusr_link'		=> $row['founder'] && (int) $user->data['founder'] == 0 ? false : ADMIN_PATH .'?cp=users' . '&amp;smt=edit_user&amp;uid='. $row['id'] . (ig('page') ? '&amp;page=' . g('page', 'int', 1) : ''),
						'founder'			=> (int) $row['founder'],
						'last_visit'		=> empty($row['last_visit']) ? $lang['NOT_YET'] : kleeja_date($row['last_visit']),
						'group'				=> get_group_name($row['group_id'])

				);
		}

		$SQL->free($result);
	}
	else #num rows
	{
		$no_results = true;
	}

	//pages
	$total_pages 	= $Pager->get_total_pages();
	$page_nums 		= $Pager->print_nums(
								ADMIN_PATH . '?cp=users' . (ig('search_id') ? '&search_id=' . g('search_id', 'str', '') : '')
								. (ig('qg') ? '&qg=' . g('qg', 'int', 1) : '') . (ig('smt') ? '&smt=' . $current_smt : ''),
								'onclick="javascript:get_kleeja_link($(this).attr(\'href\'), \'#content\'); return false;"'
							);

	$show_results = true;
break;

#editing a user, form
case 'edit_user':

	#does it exist from previous post form ?
	if(!isset($data['userid']))
	{
		$userid = g('uid', 'int', 0);
		if(!$SQL->num($SQL->query("SELECT * FROM {$dbprefix}users WHERE id=" . $userid)))
		{
			kleeja_admin_err('ERROR-NO-USER-FOUND', $action_start);
		}
	}

	$query = array(
					'SELECT'	=> 'name, mail, group_id, founder, show_my_filecp',
					'FROM'		=> "{$dbprefix}users",
					'WHERE'		=> 'id=' . $userid,
				);

	$result = $SQL->build($query);
	$udata = $SQL->fetch($result);
	$SQL->free($result);

	#founder can only edit his data
	$u_founder	= p('l_founder', 'int', $udata['founder']);
	$im_founder	= (int) $user->data['founder'];
	$u_group	= p('l_group', 'int', $udata['group_id']);
	$u_qg		= p('l_qg', 'int', $udata['group_id']);

	if($u_founder && !$im_founder)
	{
		kleeja_admin_err($lang['HV_NOT_PRVLG_ACCESS'], ADMIN_PATH . '?cp=users&smt=show_group&gq=' . $u_group, 'int');
	}

	$errs = isset($errs) ? $errs : false;

	#prepare them for the template
	$title_name	= $udata['name'];
	$u_name = p('l_name', 'str', $udata['name']);
	$u_mail = p('l_mail', 'str', $udata['mail']);
	$u_show_filecp = p('l_show_filecp', 'int', $udata['show_my_filecp']);

	$u_page = g('page', 'int', p('page', 'int', 0));

	$k_groups = array_keys($d_groups);
	$u_groups = array();
	foreach($k_groups as $id)
	{
		$u_groups[] = array(
					'id'		=> $id,
					'name'		=> get_group_name($id),
					'default'	=> $config['default_group'] == $id ? true : false,
					'selected'	=> $id == $u_group
		);
	}

break;


#new user adding form
case 'new_u':

	#preparing the template
	$errs	= isset($errs) ? $errs : false;
	$uname	= p('lname', 'str', '');
	$umail	= p('lmail', 'str', '');

	$k_groups = array_keys($d_groups);
	$u_groups = array();
	foreach($k_groups as $id)
	{
		#guests? no skip
		if($id == 2)
		{
			continue;
		}

		$u_groups[] = array(
					'id'		=> $id,
					'name'		=> get_group_name($id),
					'default'	=> $config['default_group'] == $id ? true : false,
					'selected'	=> ip('lgroup') ? p('lgroup', 'int') == $id : $id == $config['default_group']
		);
	}

break;

endswitch;


#after submit
if (ip('submit'))
{
	$g_link = ADMIN_PATH . '?cp=users' . '&amp;page=' . g('page', 'int', 1) .
				(ig('search_id') ? '&amp;search_id=' . g('search_id', 'str', '') : '') . '&amp;smt=' . $current_smt;

	$text	= ($affected ? $lang['USERS_UPDATED'] : $lang['NO_UP_CHANGE_S']) .
				'<script type="text/javascript"> setTimeout("get_kleeja_link(\'' . str_replace('&amp;', '&', $g_link) . '\');", 2000);</script>' . "\n";
	$stylee	= "admin_info";
}


#secondary menu
$go_menu = array(
				'general' => array('name'=>$lang['R_GROUPS'], 'link'=> ADMIN_PATH . '?cp=users&amp;smt=general', 'goto'=>'general', 'current'=> $current_smt == 'general'),
				#'users' => array('name'=>$lang['R_USERS'], 'link'=> ADMIN_PATH . '?cp=users&amp;smt=users', 'goto'=>'users', 'current'=> $current_smt == 'users'),
				'show_su' => array('name'=>$lang['SEARCH_USERS'], 'link'=> ADMIN_PATH . '?cp=search&amp;smt=users', 'goto'=>'show_su', 'current'=> $current_smt == 'show_su'),
				'new_u' => array('name'=>$lang['NEW_USER'], 'link'=> ADMIN_PATH . '?cp=users&amp;smt=new_u', 'goto'=>'new_u', 'current'=> $current_smt == 'new_u')
	);
