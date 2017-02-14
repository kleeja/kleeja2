<?php
/**
*
* @package adm
* @version $Id: functions.php 1910 2012-08-28 01:50:53Z saanina $
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
 * Print admin area errors
 *
 * @param string $message The message of error
 * @param bool $show_side_menu [optional] Show the side mneu or not
 * @param bool|string $redirect [optional] if link given it will redirected to it after $rs seconds
 * @param string $style [optional] this is just here to use it inside kleeja_admin_info to use admin_info
 */
function kleeja_admin_err($message,  $redirect = false, $show_side_menu = false, $style = 'error.php')
{
	global $adm_extensions_menu, $go_to;
	global $lang, $plugin, $SQL, $user;

	($hook = $plugin->run_hook('kleeja_admin_err_func')) ? eval($hook) : null; //run hook

	#assign {text} in err template
	$text = $message;
	$title = strpos($style, 'error') !== false ? $lang['ERROR'] : $lang['INFORMATION'];

	#header
	include get_template_path('header.php');
	#show tpl
	include get_template_path($style);
	#footer
	include get_template_path('footer.php');


	if($redirect)
	{
		redirect($redirect, false);
	}
	else
	{
		#exit, clean it
		garbage_collection();
		exit();
	}
}


/**
 * Print admin area inforamtion messages
 *
 * @param string $message The message of information
 * @param bool $show_side_menu [optional] Show the side mneu or not
 * @param bool|string $redirect [optional] if link given it will redirected to it after $rs seconds
 * @param int $rs [optional] if $redirected is given and not false, this will be the time in seconds
 */
function kleeja_admin_info($message, $redirect = false, $show_side_menu=true)
{
	global $plugin;

	($hook = $plugin->run_hook('kleeja_admin_info_func')) ? eval($hook) : null; //run hook

	#since info message and error message are the same, we use one function callback
	kleeja_admin_err($message, $redirect, $show_side_menu, 'info.php');
}



/**
 * Costruct a query for the file search
 *
 * @param array $search The Array of the search values
 * @return string
 */
function build_search_query($search)
{
	if(!is_array($search))
	{
		return '';
	}

	global $SQL;

	$fields = array('filename', 'username', 'than', 'size', 'ups', 'uthan', 'rep', 'rthan', 'lastdown', 'ext', 'user_ip');

	foreach ($fields as $field_name)
	{
		$search[$field_name] = !isset($search[$field_name]) ? '' : clean_var($search[$field_name], 'str');
	}

	$file_namee	= $search['filename'] != '' ? 'AND f.real_filename LIKE \'%' . $SQL->escape($search['filename']) . '%\' ' : '';
	$usernamee	= $search['username'] != '' ? 'AND u.name LIKE \'%' . $SQL->escape($search['username']) . '%\'' : '';
	$size_than	= ' f.size ' . ($search['than']!=1 ? '<=' : '>=') . (intval($search['size']) * 1024) . ' ';
	$ups_than	= $search['ups'] != '' ? 'AND f.uploads ' . ($search['uthan']!=1 ? '<' : '>') . intval($search['ups']) . ' ' : '';
	$rep_than	= $search['rep'] != '' ? 'AND f.report ' . ($search['rthan']!=1 ? '<' : '>') . intval($search['rep']) . ' ' : '';
	$lstd_than	= $search['lastdown'] != '' ? 'AND f.last_down =' . (time()-(intval($search['lastdown']) * (24 * 60 * 60))) . ' ' : '';
	$exte		= $search['ext'] != '' ? "AND f.type IN ('" . implode("', '", @explode(",", $SQL->escape($search['ext']))) . "')" : '';
	$ipp		= $search['user_ip'] != '' ? 'AND f.user_ip LIKE \'%' . $SQL->escape($search['user_ip']) . '%\' ' : '';

	return "$size_than $file_namee $ups_than $exte $rep_than $usernamee $lstd_than $exte $ipp";
}

/**
 * To re-count the total files, without making the server goes down
 *
 * @param bool $files [optional] If true, function will just count files; false, just images
 * @param bool|int $start This value is used in couning in segments, in loop every refresh
 * @return bool|int
 */
function sync_total_files($files = true, $start = false, $sizes = false)
{
	global $SQL, $dbprefix;

	$query	= array(
				'SELECT'	=> 'MIN(f.id) as min_file_id, MAX(f.id) as max_file_id',
				'FROM'		=> "{$dbprefix}files f",
		);

	$query['WHERE'] = '';
	#!files == images
	if(!$sizes)
	{
		$img_types = array('gif','jpg','png','bmp','jpeg','GIF','JPG','PNG','BMP','JPEG');
		$query['WHERE'] = "f.type" . ($files  ? ' NOT' : '') ." IN ('" . implode("', '", $img_types) . "')";
	}

	$result	= $SQL->build($query);
	$v		= $SQL->fetch($result);
	$SQL->free($result);

	#if no data, turn them to number
	$min_id = (int) $v['min_file_id'];
	$max_id = (int) $v['max_file_id'];

	#every time batch
	$batch_size = 1500;

	#no start? start = min
	$first_loop = !$start ? true : false;
	$start	= !$start ? $min_id : $start;
	$end	= $start + $batch_size;

	#now lets get this step's files number
	unset($v, $result);

	$query['SELECT'] = $sizes ? 'SUM(f.size) AS num_files' : 'COUNT(f.id) AS num_files';
	$query['WHERE'] .= ($sizes ? '' : ' AND ') . 'f.id BETWEEN ' . $start . ' AND ' . $end;

	$result	= $SQL->build($query);
	$v		= $SQL->fetch($result);
	$SQL->free($result);

	$this_step_count = $v['num_files'];
	if($this_step_count == 0)
	{
		return false;
	}

	$update_this = $sizes ? 'total_sizes' : ($files ? 'total_files' : 'total_images');

	#make it zero, firstly
	if($first_loop)
	{
		update_stats($update_this, 0, '');
	}

	update_stats($update_this, $this_step_count, '+');


	return $end;
}

/**
 * Get the *right* now number of the given stat from stats table
 *
 * @param string $name The name of stats you want get from the DB
 * @return string|int
 */
function get_actual_stats($name)
{
	return get_filter($name, 'stats', true);
}



/**
 * Options page, values of <select> option
 *
 * @param string $name The name of config name, ex: config.lang
 * @param string $default_value The default value of a select input
 * @return string|bool
 */
function option_select_values($name, $default_value = '')
{
	global $plugin, $lang;

	$values = '';

	switch($name)
	{
		case 'time_zone':
			$zones = time_zones();
			foreach($zones as $z=>$t)
			{
				$values .= '<option ' . ($default_value == $t ? 'selected="selected" ' : '') . 'value="' . $t . '">' . $z . '</option>' . "\n";
			}

		break;

		case 'language':

			if ($dh = @opendir(PATH . 'languages'))
			{
				while (($file = readdir($dh)) !== false)
				{
					if(strpos($file, '.') === false && $file != '..' && $file != '.')
					{
						$values .= '<option ' . ($default_value == $file ? 'selected="selected"' : '') . ' value="' . $file . '">' . $file . '</option>' . "\n";
					}
				}
				@closedir($dh);
			}

		break;

		case 'user_system':

			#fix previous choice in old kleeja
			if(in_array($default_value, array('2', '3', '4')))
			{
				$default_value = str_replace(array('2', '3', '4'), array('phpbb', 'vb', 'mysmartbb'), $default_value);
			}

			$values .= '<option value="1"' . ($default_value=='1' ? ' selected="selected"' : '') . '>' . $lang['NORMAL'] . '</option>' . "\n";
			if ($dh = @opendir(PATH . 'includes/auth_integration'))
			{
				while (($file = readdir($dh)) !== false)
				{
					if(strpos($file, '.php') !== false)
					{
						$file = trim(str_replace('.php', '', $file));
						$values .= '<option value="' . $file . '"' . ($default_value == $file ? ' selected="selected"' : '') . '>' . $file . '</option>' . "\n";
					}
				}
				@closedir($dh);
			}


		break;

		case 'decode':

			$decode_types = array(
					0 => $lang['NO_CHANGE'],
					1 => $lang['CHANGE_TIME'],
					2 => $lang['CHANGE_MD5']
				);

				($hook = $plugin->run_hook('option_select_values_decode_types_func')) ? eval($hook) : null; //run hook

				foreach($decode_types as $d=>$l)
				{
					$values .= '<option ' . ($default_value == $d ? 'selected="selected" ' : '') . 'value="' . $d . '">' . $l . '</option>' . "\n";
				}

		break;

		case 'id_form':

			$id_form_types = array(
					'id' => $lang['IDF'],
					'filename' => $lang['IDFF'],
					'direct' => $lang['IDFD']
				);

				($hook = $plugin->run_hook('option_select_values_decode_types_func')) ? eval($hook) : null; //run hook

				foreach($id_form_types as $d=>$l)
				{
					$values .= '<option ' . ($default_value == $d ? 'selected="selected" ' : '') . 'value="' . $d . '">' . $l . '</option>' . "\n";
				}

		break;

	}

	($hook = $plugin->run_hook('option_select_values_func')) ? eval($hook) : null; //run hook

	return $values;
}



/**
 * Disply options on admin panel as they are supposed to be
 *
 * @param string $opt option value
 * @return string The parsed option html or value
 */
function parse_options($opt)
{
	global $con, $lang, $plugin;

	#Exceptions for some options
	if($opt[2] == 'write_imgs')
	{
		$opt[4] = '<br /><img src="' . (file_exists(PATH . 'images/watermark.gif') ? PATH . 'images/watermark.gif' : PATH . 'images/watermark.png') . '" alt="Seal photo" style=\"margin-top:4px;border:1px groove #FF865E;">';
	}
	else if($opt[2] == 'googleanalytics')
	{
		$opt[4] = '<a href="http://www.google.com/analytics">Google Analytics</a>';

	}

	#if it's only the value
	if($opt[1] == 'con' && trim($opt[2]) != '' && isset($con[$opt[2]]))
	{
		return $con[$opt[2]];
	}

	#language term
	if($opt[1] == 'lang' && trim($opt[2]) != '' && isset($lang[$opt[2]]))
	{
		return $lang[$opt[2]];
	}

	#yes or no option
	if($opt[1] == 'yesno' && trim($opt[2]) != '')
	{
		return '<div class="radio"><label><input type="radio" id="' . $opt[2] . '" name="' . $opt[2] . '" value="1" ' . ($con[$opt[2]] == 1 ? ' checked="checked"' :'') . '>' . $lang['YES'] . '</label></div>' .
					'<div class="radio"><label><input type="radio" id="' .  $opt[2] . '" name="' . $opt[2] . '" value="0" ' . ($con[$opt[2]] == 0 ? ' checked="checked"' :'') . '>' . $lang['NO'] . '</label></div>' .
					(isset($opt[4]) ? '<br> <small class="text-muted">' . (isset($lang[strtoupper($opt[4])]) ? $lang[strtoupper($opt[4])] :  $opt[4])  .'</small>': '');
	}

	#text or left-to-right text input
	if(($opt[1] == 'text' || $opt[1] == 'ltr') && trim($opt[2]) != '')
	{
		return '<input type="text" id="' . $opt[2] . '" name="' . $opt[2] . '" value="' . $con[$opt[2]] . '" class="form-control text-options" ' . ($opt[1] == 'ltr'? ' style="direction:ltr"' : '') .' />' .
		(isset($opt[4]) ? '<br> <small class="text-muted">' . (isset($lang[$opt[4]]) ? $lang[$opt[4]] :  $opt[4])  .'</small>': '');
	}

	#select option
	if($opt[1] == 'select' && trim($opt[2]) != '')
	{
		return '<select name="' . $opt[2] . '" class="form-control"  id="' . $opt[2] . '">\r\n ' . option_select_values($opt[2], $con[$opt[2]])  . '\r\n </select>' .
		(isset($opt[4]) ? '<br> <small class="text-muted">' . (isset($lang[$opt[4]]) ? $lang[$opt[4]] :  $opt[4])  .'</small>': '');
	}


	($hook = $plugin->run_hook('parse_options_func')) ? eval($hook) : null; //run hook

}
