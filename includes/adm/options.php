<?php
/**
*
* @package adm
* @version $Id: options.php 2240 2013-12-07 23:22:54Z phpfalcon@gmail.com $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/

// not for directly open
if (!defined('IN_ADMIN'))
{
	exit();
}


//for style ..
$current_template = 'options.php';
$current_smt	= isset($_GET['smt']) ? (preg_match('![a-z0-9_]!i', trim($_GET['smt'])) ? trim($_GET['smt']) : 'general') : 'general';
//words
$action 		= ADMIN_PATH . '?cp=options&amp;smt=' . $current_smt;
$n_submit 		= $lang['UPDATE_CONFIG'];
$options		= '';
#$current_type	= isset($_GET['type']) ? htmlspecialchars($_GET['type']) : 'general';
$CONFIGEXTEND	= false;
$H_FORM_KEYS	= kleeja_add_form_key('adm_options');




#secondary menu
$query	= array(
				'SELECT'	=> 'DISTINCT(type)',
				'FROM'		=> "{$dbprefix}config c",
				'WHERE'		=> "c.option <> '' AND c.type <> 'groups'",
				'ORDER BY'	=> 'display_order'
			);

$result = $SQL->build($query);

while($row = $SQL->fetch($result))
{
	$name = !empty($lang['CONFIG_KLJ_MENUS_' . strtoupper($row['type'])]) ? $lang['CONFIG_KLJ_MENUS_' . strtoupper($row['type'])] : $lang['CONFIG_KLJ_MENUS_OTHER'];
	$go_menu[$row['type']] = array('name'=>$name, 'link'=>$action . '&amp;smt=' . $row['type'], 'goto'=>$row['type'], 'current'=> $current_smt == $row['type']);
}

$go_menu['all'] = array('name'=>$lang['CONFIG_KLJ_MENUS_ALL'], 'link'=>$action . '&amp;smt=all', 'goto'=>'all', 'current'=> $current_smt == 'all');

//
// Check form key
//
if (isset($_POST['submit']))
{
	if(!kleeja_check_form_key('adm_options'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], true, $lang['ERROR'], true, $action, 1);
	}
}



#general varaibles
$optionss	= array();
$query	= array(
					'SELECT'	=> '*',
					'FROM'		=> "{$dbprefix}config",
					'ORDER BY'	=> 'display_order, type ASC'
			);

#$CONFIGEXTEND	  = $SQL->escape($current_smt);
#$CONFIGEXTENDLANG = $go_menu[$current_smt]['name'];

if($current_smt != 'all')
{
	$query['WHERE'] = "type = '" . $SQL->escape($current_smt) . "' OR type = ''";
}
else if($current_smt == 'all')
{
	$query['WHERE'] = "type <> 'groups' OR type = ''";
}

$result = $SQL->build($query);

$thumbs_are = get_config('thmb_dims');

while($row=$SQL->fetch($result))
{
	#make new lovely array !!
	$con[$row['name']] = $row['value'];

	#if($row['name'] == 'thumbs_imgs')
	#{
	#	list($thmb_dim_w, $thmb_dim_h) = array_map('trim', @explode('*', $thumbs_are));
	#}


	($hook = $plugin->run_hook('while_fetch_adm_config')) ? eval($hook) : null; //run hook


	#parsing options form the database
	if(!empty($row['option']))
	{
		$option_value = preg_replace_callback(
		'!\{([a-z]+)\.([a-zA-Z0-9-_]+)(\.([a-zA-Z0-9_-]+))?\}!',
		'parse_options',
		$row['option']);



		$optionss[$row['name']] = array(
				'option'		 => '<div class="form-group">' . "\n" .
									'<label for="' . $row['name'] . '">' . (!empty($lang[strtoupper($row['name'])]) ? $lang[strtoupper($row['name'])] : strtoupper($row['name'])) . '</label>' . "\n" .
									'' . $option_value . '' . "\n" .
									'</div>' . "\n" . '',
				'type'			=> $row['type'],
				'display_order' => $row['display_order'],
			);
	}

	#after user's submit
	if (isset($_POST['submit']))
	{
		//-->
		$new[$row['name']] = (isset($_POST[$row['name']])) ? $_POST[$row['name']] : $con[$row['name']];

		#make sure before saving them
		if($row['name'] == 'thumbs_imgs')
		{
			if(intval($_POST['thmb_dim_w']) < 10)
			{
				$_POST['thmb_dim_w'] = 10;
			}

			if(intval($_POST['thmb_dim_h']) < 10)
			{
				$_POST['thmb_dim_h'] = 10;
			}

			//$thumbs_were = trim($_POST['thmb_dim_w']) . '*' . trim($_POST['thmb_dim_h']);
			//update_config('thmb_dims', $thumbs_were);
		}
		else if($row['name'] == 'livexts')
		{
			$new['livexts'] = implode(',', array_map('trim', explode(',', $_POST['livexts'])));
		}
		else if($row['name'] == 'prefixname')
		{
			$new['prefixname'] = preg_replace('/[^a-z0-9_\-\}\{\:\.]/', '', strtolower($_POST['prefixname']));
		}
		else if($row['name'] == 'siteurl')
		{
			if($_POST['siteurl'][strlen($_POST['siteurl'])-1] != '/')
			{
				$new['siteurl'] .= '/';
			}

			if($config['siteurl'] != $new['siteurl'])
			{
				#when site url changed, cookies will be currptued !
				//update_config('cookie_path', '');
				unset($_GET['_ajax_']);
			}
		}

		($hook = $plugin->run_hook('after_submit_adm_config')) ? eval($hook) : null; //run hook

		$update_query = array(
								'UPDATE'	=> "{$dbprefix}config",
								'SET'		=> "value='" . $SQL->escape($new[$row['name']]) . "'",
								'WHERE'		=> "name='" . $row['name'] . "'"
							);

		if($current_smt != 'all')
		{
			$query['WHERE'] .= " AND type = '" . $SQL->escape($current_smt) . "'";
		}

		$SQL->build($update_query);
	}
}

$SQL->free($result);
$types = array();

foreach($optionss as $key => $option)
{
	if(empty($types[$option['type']]))
	{
		$types[$option['type']] = '<h2>' . $go_menu[$option['type']]['name'] . '</h2>';
	}
}

foreach($types as $typekey => $type)
{
	$options .= $type;
	foreach($optionss as $key => $option)
	{
		if($option['type'] == $typekey)
		{
			$options .= $option['option'];
		}
	}
	$options .= '</ul>';
}

//after submit
if (isset($_POST['submit']))
{
	($hook = $plugin->run_hook('after_submit_adm_config')) ? eval($hook) : null; //run hook


	#delete cache ..
	$cache->clean('data_config');

	kleeja_admin_info($lang['CONFIGS_UPDATED'], true, '', true,  ADMIN_PATH . '?cp=options', 3);
	//}
}#submit
