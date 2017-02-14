<?php
/**
*
* @package Kleeja
* @version $Id: functions.php 2229 2013-11-17 15:09:07Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/


/**
* @ignore
*/
if (!defined('IN_COMMON'))
{
	exit();
}



/**
 * Is given _GET variable exists?
 *
 * @since 2.0
 * @param string $name The name of _GET variable
 * @return bool
 */
function ig($name)
{
	return isset($_GET[$name]) ? true : false;
}

/**
 * Is given _POST variable exists?
 *
 * @since 2.0
 * @param string $name The name of _POST variable
 * @return bool
 */
function ip($name)
{
	return isset($_POST[$name]) ? true : false;
}

/**
 *  clean _GET variable if exists and return it
 *
 * @since 2.0
 * @param string $name The name of _GET variable
 * @param string $type The type of the varaible, str or int
 * @param mixed $default_value [optional] The default value to be return if not existed
 * @return string|bool
 */
function g($name, $type = 'str', $default_value = false)
{
	return isset($_GET[$name]) ? clean_var($_GET[$name], $type, $default_value) : $default_value;
}

/**
 * clean _POST variable if exists and return it
 *
 * @since 2.0
 * @param string $name The name of _POST variable
 * @param string $type The type of the varaible, str or int
 * @param mixed $default_value [optional] The default value to be return if not existed
 * @return string|bool
 */
function p($name, $type = 'str', $default_value = false)
{
	return isset($_POST[$name]) ? clean_var($_POST[$name], $type, $default_value) : $default_value;
}

/**
 * Clean variable according to the selected type
 *
 * @since 2.0
 * @param mixed $var the variable to be cleaned
 * @param str $type Validate and clean variable according to this select, string, email ..
 * @param str $defaul_value return this value if validation went wrong
 * @return mixed
 */
function clean_var($var, $type = 'str', $defaul_value = false)
{
	$var = trim($var);
	switch($type)
	{
		default:
		case 'str': case 'string':
			return htmlspecialchars(trim($var));
		break;
		case 'int': case 'number':
			return intval($var);
		break;
		case 'latin': case 'english':
			return preg_match('![a-z0-9_]!i', trim($var)) ? trim($var) : $defaul_value;
		break;
		case 'mail': case 'email':
			return !preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i', $var) ? $defaul_value : strtolower($var);
		break;
		case 'bool':
			return (bool) $var;
		break;
	}
	return '';
}


/**
 * The ban system, using IPs to ban access to the website
 *
 * @return void
 */
function run_ban_system()
{
	global $ban_system_values, $lang, $plugin, $user;


	if(!isset($ban_system_values) || (isset($ban_system_values) && empty($ban_system_values)))
	{
		return;
	}

	#now, loop baby loop
	if (is_array($ban_system_values))
	{
		foreach ($ban_system_values as $ban_value=>$ban_type)
		{
			$ban_value = trim($ban_value);

			if(empty($ban_value))
			{
				continue;
			}

			if($ban_type == 'ip')
			{
				$replace_it = str_replace("*", '([0-9]{1,3})', $ban_value);
				$replace_it = str_replace(".", '\.', $replace_it);

				if (($user->data['ip'] == $ban_value || @preg_match('/' . preg_quote($replace_it, '/') . '/i', $user->data['ip'])) && !$user->can('enter_acp'))
				{
					($hook = $plugin->run_hook('banned_ip_get_ban_func')) ? eval($hook) : null; //run hook
					kleeja_info($lang['U_R_BANNED']);
				}
			}
			else if($ban_type == 'username')
			{
				#banned user and is not an admin
				if($user->is_user() && $user->data['name'] == $ban_value && !$user->can('enter_acp'))
				{
					($hook = $plugin->run_hook('banned_username_get_ban_func')) ? eval($hook) : null; //run hook
					kleeja_info($lang['U_R_BANNED']);
				}
			}
		}
	}

	($hook = $plugin->run_hook('get_ban_func')) ? eval($hook) : null; //run hook
}


/**
 * A helper function to fix issue of UTF-8 text with mail function
 *
 * @param string $text string to fix
 * @return string fixed string
 */
function _sm_mk_utf8($text)
{
	return "=?UTF-8?B?" . base64_encode($text) . "?=";
}

/**
 * Send an email message using (mail wrapper)
 *
 * @param string $to email address of recciver
 * @param string $body email message text
 * @param string $subject email title
 * @param string $fromaddress email address of sender
 * @param string $fromname a name of sender
 * @param string $bcc aBBC ddresses
 * @return bool
 */
function send_mail($to, $body, $subject, $fromaddress, $fromname, $bcc='')
{
	global $plugin;

	$eol = "\r\n";
	$headers = '';
	$headers .= 'From: ' . _sm_mk_utf8(trim(preg_replace('#[\n\r:]+#s', '', $fromname))) . ' <' . trim(preg_replace('#[\n\r:]+#s', '', $fromaddress)) . '>' . $eol;
	//$headers .= 'Sender: ' . _sm_mk_utf8($fromname) . ' <' . $fromaddress . '>' . $eol;
	$headers .= 'MIME-Version: 1.0' . $eol;
	$headers .= 'Content-transfer-encoding: 8bit' . $eol; // 7bit
	$headers .= 'Content-Type: text/plain; charset=utf-8' . $eol; // format=flowed
	$headers .= 'X-Mailer: Kleeja Mailer' . $eol;
	$headers .= 'Reply-To: ' . _sm_mk_utf8(trim(preg_replace('#[\n\r:]+#s', '', $fromname))) . ' <' . trim(preg_replace('#[\n\r:]+#s', '', $fromaddress)) . '>' . $eol;
	//$headers .= 'Return-Path: <' . $fromaddress . '>' . $eol;
	if (!empty($bcc))
	{
		$headers .= 'Bcc: ' . trim(preg_replace('#[\n\r:]+#s', '', $bbc)) . $eol;
	}
	//$headers .= 'Message-ID: <' . md5(uniqid(time())) . '@' . _sm_mk_utf8($fromname) . '>' . $eol;
	//$headers .= 'Date: ' . date('r') . $eol;

	//$headers .= 'X-Priority: 3' . $eol;
	//$headers .= 'X-MSMail-Priority: Normal' . $eol;

	//$headers .= 'X-MimeOLE: kleeja' . $eol;

	($hook = $plugin->run_hook('kleeja_send_mail')) ? eval($hook) : null; //run hook

	$body = str_replace(array("\n", "\0"), array("\r\n", ''), $body);

	// Change the linebreaks used in the headers according to OS
	if (strtoupper(substr(PHP_OS, 0, 3)) == 'MAC')
	{
		$headers = str_replace("\r\n", "\r", $headers);
	}
	else if (strtoupper(substr(PHP_OS, 0, 3)) != 'WIN')
	{
		$headers = str_replace("\r\n", "\n", $headers);
	}

	$mail_sent = @mail(trim(preg_replace('#[\n\r]+#s', '', $to)), _sm_mk_utf8(trim(preg_replace('#[\n\r]+#s', '', $subject))), $body, $headers);

	return $mail_sent;
}



/**
 * include a language file
 *
 * @param string $name the langauge file name
 * @param string $folder the langauge folder, if it has different folder.
 * @return bool
 */
function get_lang($name, $folder = '')
{
	global $config, $lang, $plugin;

	($hook = $plugin->run_hook('get_lang_func')) ? eval($hook) : null; //run hook

	$name = str_replace('..', '', $name);
	if($folder != '')
	{
		$folder = str_replace('..', '', $folder);
		$name = $folder . '/' . $name;
	}

	$path = PATH . 'languages/' . $config['language'] . '/' . str_replace('.php', '', $name) . '.php';
	$s = defined('DEBUG') ? include($path) : @include($path);

	if($s === false)
	{
		//$pathen = PATH . 'lang/en/' . str_replace('.php', '', $name) . '.php';
		//$sen = defined('DEBUG') ? include_once($pathen) :  @include_once($pathen);
		//if($sen === false)
		//{
			big_error('There is no language file in the current path', 'languages/' . $config['language'] . '/' . str_replace('.php', '', $name) . '.php  not found');
		//}
	}

	return true;
}


/**
 * Get option value from database dirctly, instead of using $config['...']
 *
 * @param string $name the option name
 * @return string the option value
 */
function get_config($name)
{
	global $dbprefix, $SQL, $d_groups, $user, $plugin;

	$table = "{$dbprefix}config c";

	#what if this config is a group-configs related ?
	$group_id_sql = '';

	if(array_key_exists($name, $d_groups[$user->data['group_id']]['configs']))
	{
		$table = "{$dbprefix}groups_data c";
		$group_id_sql = " AND c.group_id=" . $user->data['group_id'];
	}

	$query = array(
					'SELECT'	=> 'c.value',
					'FROM'		=> $table,
					'WHERE'		=> "c.name = '" . $SQL->escape($name) . "'" . $group_id_sql
				);

	$result	= $SQL->build($query);
	$v		= $SQL->fetch($result);
	$return	= $v['value'];

	($hook = $plugin->run_hook('get_config_func')) ? eval($hook) : null; //run hook
	return $return;
}

/**
 * Add  a new option in config table in database
 *
 * @param string $name the option name
 * @param string $value the option value
 * @param int $order its order in the admin options page
 * @param string $field its field type in the admin options page, for example {text.name} or {yesno.name}
 * @param string $type use 0 for system options, or general, interface, advanced or upload for other choices.
 * @param bool $dynamic to be cached use false, true if you want the value to be refreshed everytime page loads
 * @return bool
 */
function add_config($name, $value = '', $order = 0, $field = '', $type = '0', $dynamic = false)
{
	global $plugin, $cache;

	#if bulk adding
	if(is_array($name))
	{
		foreach($name as $n=>$v)
		{
			add_config($n, $v['order'], $v['field'], $v['type'], $v['dynamic']);
		}

		return;
	}

	global $dbprefix, $SQL, $config, $d_groups;

	if(get_config($name))
	{
		return true;
	}

	if($field != '' && $type == '0')
	{
		$type = 'other';
	}

	if($type == 'groups')
	{
		#add this option to all groups
		$group_ids = array_keys($d_groups);
		foreach($group_ids as $g_id)
		{
			$insert_query	= array(
									'INSERT'	=> '`name`, `value`, `group_id`',
									'INTO'		=> "{$dbprefix}groups_data",
									'VALUES'	=> "'" . $SQL->escape($name) . "','" . $SQL->escape($value) . "', " . $g_id,
								);

			($hook = $plugin->run_hook('insert_sql_add_config_func_groups_data')) ? eval($hook) : null; //run hook

			$SQL->build($insert_query);
		}
	}

	$insert_query	= array(
							'INSERT'	=> '`name` ,`value` ,`option` ,`display_order`, `type`, `dynamic`',
							'INTO'		=> "{$dbprefix}config",
							'VALUES'	=> "'" . $SQL->escape($name) . "','" . $SQL->escape($value) . "', '" . $SQL->escape($field) . "', " . intval($order) . ",'" . $SQL->escape($type) . "','"  . ($dynamic ? 1 : 0) . "'",
						);

	($hook = $plugin->run_hook('insert_sql_add_config_func')) ? eval($hook) : null; //run hook

	$SQL->build($insert_query);

	if($SQL->affected())
	{
		$cache->clean('data_config');
		$config[$name] = $value;
		return true;
	}

	return false;
}

/**
 * Update the value of an option in config table in database
 *
 * @param string $name the option name to update its value
 * @param string $value the option new value
 * @param bool $escape use database escape function or not
 * @param bool|int $group if this option is a group-configs related, provide group id
 * @return bool
 */
function update_config($name, $value = '', $escape = true, $group = false)
{
	global $SQL, $dbprefix, $d_groups, $user, $plugin, $cache;

	$value = ($escape) ? $SQL->escape($value) : $value;
	$table = "{$dbprefix}config";

	#what if this config is a group-configs related ?
	$group_id_sql = '';
	if(array_key_exists($name, $d_groups[$user->data['group_id']]['configs']))
	{
		$table = "{$dbprefix}groups_data";
		if($group == -1)
		{
			$group_id_sql = ' AND group_id=' . $user->data['group_id'];
		}
		else if($group)
		{
			$group_id_sql = ' AND group_id=' . intval($group);
		}
	}

	$update_query	= array(
							'UPDATE'	=> $table,
							'SET'		=> "value='" . ($escape ? $SQL->escape($value) : $value) . "'",
							'WHERE'		=> 'name = "' . $SQL->escape($name) . '"' . $group_id_sql
					);

	($hook = $plugin->run_hook('update_sql_update_config_func')) ? eval($hook) : null; //run hook

	$SQL->build($update_query);
	if($SQL->affected())
	{
		if($table == "{$dbprefix}groups_data")
		{
			$d_groups[$user->data['group_id']]['configs'][$name] = $value;
			$cache->clean('data_groups');
			return true;
		}

		$config[$name] = $value;
		$cache->clean('data_config');
		return true;
	}

	return false;
}

/**
 * Delete an option from config table in database
 *
 * @param string $name the option name to be deleted
 * @return bool
 */
function delete_config($name)
{
	global $plugin;

	if(is_array($name))
	{
		foreach($name as $n)
		{
			delete_config($n);
		}

		return;
	}

	global $dbprefix, $SQL, $d_groups, $user;

	//
	// 'IN' doesnt work here with delete, i dont know why ?
	//
	$delete_query	= array(
								'DELETE'	=> "{$dbprefix}config",
								'WHERE'		=>  "name  = '" . $SQL->escape($name) . "'"
						);
	($hook = $plugin->run_hook('del_sql_delete_config_func')) ? eval($hook) : null; //run hook

	$SQL->build($delete_query);

	if(array_key_exists($name, $d_groups[$user->data['group_id']]['configs']))
	{
		$delete_query	= array(
									'DELETE'	=> "{$dbprefix}groups_data",
									'WHERE'		=>  "name  = '" . $SQL->escape($name) . "'"
							);
		($hook = $plugin->run_hook('del_sql_delete_config_func2')) ? eval($hook) : null; //run hook

		$SQL->build($delete_query);
	}

	if($SQL->affected())
	{
		return true;
	}

	return false;
}




/**
 * Check if the given value of CAPTCHA is valid
 *
 * @return bool
 */
function kleeja_check_captcha()
{
	global $config, $plugin;

	if((int) $config['enable_captcha'] == 0)
	{
		return true;
	}

	$return = false;

	$answer_value = p('kleeja_code_answer', 'str', '');

	if(!empty($_SESSION['klj_sec_code']) && $answer_value != '')
	{
		if($_SESSION['klj_sec_code'] == trim($answer_value))
		{
			unset($_SESSION['klj_sec_code']);
			$return = true;
		}
	}

	($hook = $plugin->run_hook('kleeja_check_captcha_func')) ? eval($hook) : null; //run hook
	return $return;
}


/**
* Logging and testing, enabled only in development stage only.
*
* @param string $text The string you want to save to the log file
* @return bool
*/
function kleeja_log($text, $reset = false)
{
	#if not in development stage, abort
	if(!defined('DEV_STAGE'))
	{
		return false;
	}

	$log_file = PATH . 'cache/kleeja_log.log';
    $l_c = @file_get_contents($log_file);
	$fp = @fopen($log_file, 'w');
	@fwrite($fp, $text . " [time : " . date('H:i a, d-m-Y') . "] \r\n" . $l_c);
	@fclose($fp);
	return true;
}



/**
 * Get domain from a url
 *
 * @param string $url The link you want to get the domain from
 * @return mixed
 */
function get_domain($url)
{
	$pieces = parse_url($url);
	$domain = isset($pieces['host']) ? $pieces['host'] : '';
	if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs))
	{
		return $regs['domain'];
 	}
 	return false;
}


/**
 * Actions to be done before script ending
 *
 * @return void
 */
function garbage_collection()
{
	if(defined('garbage_collection_done'))
	{
		return true;
	}

	global $SQL;

	if($SQL)
	{
		$SQL->close();
	}

	#now close session to let user open any other page in Kleeja
	@session_write_close();

	define('garbage_collection_done', true);
}


/**
 * Generate a filter, filiter is a value stored in the database to use it later
 *
 * @param string $type Unique name to connect multiple filters together if you want
 * @param string $value The stored value
 * @param bool|int $time [optional] timestamp if this filter depends on time  or leave it
 * @param bool|int $user [optional] user ID if this filter depends on user or leave it
 * @param string $status [optional] if this filter has status, then fill it or leave it
 * @param bool|string $uid [optional] filter uid of your choice or leave it for random one
 * @return int
 */
function insert_filter($uid, $value, $type = 'general', $time = false, $user_id = false, $status = '')
{
	global $SQL, $dbprefix, $user, $plugin;

	$user_id = !$user_id ? $user->data['id'] : $user_id;
	$time = !$time ? time() : $time;

	$insert_query	= array(
							'INSERT'	=> 'filter_uid, filter_type ,filter_value ,filter_time ,filter_user, filter_status',
							'INTO'		=> "{$dbprefix}filters",
							'VALUES'	=> "'" . ($uid ? $uid : uniqid()) . "', '" . $SQL->escape($type) . "','" . $SQL->escape($value) . "',
							 " . intval($time) . "," . intval($user_id) . ",'" . $SQL->escape($status) . "'"
						);
	($hook = $plugin->run_hook('insert_sql_insert_filter_func')) ? eval($hook) : null; //run hook

	$SQL->build($insert_query);

	return $SQL->id();
}



/**
 * Update filter value..
 *
 * @param int|string $id_or_uid Number of filter_id or the unique id string of filter_uid
 * @param string $value The modified value of filter
 * @param string $filter_type if given, use it with sql where
 * @param string $filter_status if given, update the filter status
 * @return bool
 */
function update_filter($id_or_uid, $value, $filter_type = 'general', $filter_status = false, $user_id = false)
{
	global $SQL, $dbprefix, $plugin;

	$update_query	= array(
							'UPDATE'	=> "{$dbprefix}filters",
							'SET'		=> "filter_value='" . $SQL->escape($value) . "'" . ($filter_status ? ", filter_status='" . $SQL->escape($filter_status) . "'" : ''),
							'WHERE'		=> (strval(intval($id_or_uid)) == strval($id_or_uid) ? 'filter_id=' . intval($id_or_uid) : "filter_uid='" . $SQL->escape($id_or_uid) . "'")
								. ($filter_type ? " AND filter_type='" . $SQL->escape($filter_type) . "'" : '')
								. ($user_id ? " AND filter_user=" . intval($user_id) . "" : '')
					);

	($hook = $plugin->run_hook('update_filter_func')) ? eval($hook) : null; //run hook

	$SQL->build($update_query);
	if($SQL->affected())
	{
		return true;
	}

	return false;
}

/**
 * Get filter from db..
 *
 * @param string|int $item The value of $get_by, to get the filter depend on it
 * @param string $get_by The name of filter column we want to get the filter value from
 * @param bool $just_value If true the return value should be just filter_value otherwise all filter rows
 * @param string $filter_type if given, use it with sql where
 * @return mixed
 */
function get_filter($item, $filter_type = false, $just_value = false, $get_by = 'filter_uid', $user_id = false)
{
	global $dbprefix, $SQL, $plugin;

	$valid_filter_columns = array('filter_id', 'filter_uid', 'filter_user', 'filter_status');

	if(!in_array($get_by, $valid_filter_columns))
	{
		$get_by = 'filter_uid';
	}

	$query = array(
					'SELECT'	=> $just_value ? 'f.filter_value' : 'f.*',
					'FROM'		=> "{$dbprefix}filters f",
					'WHERE'		=> "f." . $get_by . " = " . ($get_by == 'filter_id' ? intval($item) : "'" . $SQL->escape($item) . "'")
					. ($filter_type ? " AND f.filter_type='" . $SQL->escape($filter_type) . "'" : '')
					. ($user_id ? " AND f.filter_user=" . intval($user_id) . "" : '')
				);

	$result	= $SQL->build($query);
	$v		= $SQL->fetch($result);

	($hook = $plugin->run_hook('get_filter_func')) ? eval($hook) : null; //run hook

	$SQL->free($result);
	if($just_value)
	{
		return $v['filter_value'];
	}

	return $v;
}

/**
 * check if filter exists or not
 *
 * @param string|int $item The value of $get_by, to find the filter depend on it
 * @param string $get_by The name of filter column we want to get the filter from
 * @return bool|int
 */
function filter_exists($item, $get_by = 'filter_id', $filter_type = false, $user_id = false)
{
	global $dbprefix, $SQL, $plugin;

	$query = array(
					'SELECT'	=> 'f.filter_id',
					'FROM'		=> "{$dbprefix}filters f",
					'WHERE'		=> "f." . $get_by . " = " . ($get_by == 'filter_id' ? intval($item) : "'" . $SQL->escape($item) . "'")
									. ($filter_type ? " AND f.filter_type='" . $SQL->escape($filter_type) . "'" : '')
									. ($user_id ? " AND f.filter_user=" . intval($user_id) . "" : '')

				);

	($hook = $plugin->run_hook('filter_exists_func')) ? eval($hook) : null; //run hook

	$result	= $SQL->build($query);
	return $SQL->num($result);
}


/**
 * delete a filter
 *
 * @param string|int $item The value of $get_by, to find the filter depend on it
 * @param string $get_by The name of filter column we want to get the filter from
 * @return bool|int
 */
function delete_filter($item, $get_by = 'filter_id', $filter_type = false, $user_id = false)
{
	global $dbprefix, $SQL, $plugin;

	$query = array(
					'DELETE'	=> "{$dbprefix}filters",
					'WHERE'		=> $get_by . " = " . ($get_by == 'filter_id' ? intval($item) : "'" . $SQL->escape($item) . "'")
									. ($filter_type ? " AND filter_type='" . $SQL->escape($filter_type) . "'" : '')
									. ($user_id ? " AND filter_user=" . intval($user_id) . "" : '')
				);

	($hook = $plugin->run_hook('filter_delete_func')) ? eval($hook) : null; //run hook

	$result	= $SQL->build($query);

	if($SQL->affected())
	{
		return true;
	}

	return false;
}



/**
 * update stats, if not existed, create it
 *
 *
 * @param string $name stats name
 * @param string $value stats value
 * @param string $plus_or_minue use this for increasing or
 *           decreasing the number, empty means new value
 * @return bool
 */
function update_stats($name, $value = '1', $plus_or_minue = '+')
{
	global $stats, $SQL, $dbprefix;

	$exist = true;
	if(!isset($stats[$name]))
	{
		$exist = false;
		if(filter_exists($name, 'filter_uid', 'stats'))
		{
			$exist = true;
		}
	}

	if(!$exist)
	{
		return insert_filter($name, $value, 'stats');
	}

	$update_query	= array(
							'UPDATE'	=> "{$dbprefix}filters",
							'SET'		=> "filter_user=0, filter_value=" . ($plus_or_minue == '' ? '' : 'filter_value') . ($plus_or_minue == '+' ? '+' : ($plus_or_minue == '-'  ? '-': '')) . $SQL->escape($value),
							'WHERE'		=> "filter_uid='" . $SQL->escape($name) . "' AND filter_type='stats'"
					);

	$SQL->build($update_query);

	if($SQL->affected())
	{
		return true;
	}
}
