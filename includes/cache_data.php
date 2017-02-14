<?php
/**
*
* @package Kleeja
* @version $Id: version.php 2199 2013-11-03 01:38:25Z saanina $
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
 * Data that are used most frequently cached for easing the load on the database.
 * Data like options, baned IPs and groups information.
 */



#get config data from config table
if (!($config = $cache->get('data_config')))
{
	$query = array(
					'SELECT'	=> 'c.name, c.value',
					'FROM'		=> "{$dbprefix}config c",
					'WHERE'		=> 'c.dynamic = 0',
				);

	($hook = $plugin->run_hook('qr_select_config_cache')) ? eval($hook) : null; //run hook

	$result = $SQL->build($query);

	while($row=$SQL->fetch($result))
	{
		$config[$row['name']] = $row['value'];
	}

	$SQL->free($result);

	$cache->save('data_config', $config);
}


#stats to cache
if (!($stats = $cache->get('data_stats')))
{
	$query = array(
					'SELECT'	=> 'f.filter_uid, f.filter_value',
					'FROM'		=> "{$dbprefix}filters f",
					'WHERE'		=> "f.filter_type='stats'"
				);

	$result = $SQL->build($query);

	$stats = array();

	while($row=$SQL->fetch($result))
	{
		$stats[$row['filter_uid']] = $row['filter_value'];

		/*$stats = array(
			'stat_files'		=> $row['files'],
			'stat_imgs'			=> $row['imgs'],
			'stat_sizes'		=> $row['sizes'],
			'stat_users'		=> $row['users'],
			'stat_last_file'	=> $row['last_file'],
			'stat_last_f_del'	=> $row['last_f_del'],
			'stat_last_google'	=> $row['last_google'],
			'stat_last_bing'	=> $row['last_bing'],
			'stat_google_num'	=> $row['google_num'],
			'stat_bing_num'		=> $row['bing_num'],
			'stat_last_user'	=> $row['lastuser']
		);*/

	}

	$SQL->free($result);

	#save the stats for hour and then refresh them
	$cache->save('data_stats', $stats, 3600);


	if(!isset($stats['total_files']) || !isset($stats['total_images']) || !isset($stats['total_sizes']))
	{
		$fill_stats = array('total_files', 'total_images', 'total_sizes');
		foreach ($fill_stats as $key)
		{
			if(!isset($stats[$key]))
			{
				$stats[$key] = 0;
			}
		}
	}

	#also, save the data for the charts later
	$query = array(
					'SELECT'	=> 'f.filter_uid',
					'FROM'		=> "{$dbprefix}filters f",
					'WHERE'		=> "f.filter_type='stats_for_acp' AND f.filter_uid = '" . date('d-n-Y') . "'"
				);

	$result	= $SQL->build($query);

	#if already there is stats for this day, just update it, if not insert a new one
	if($SQL->num($result))
	{
		$f_query	= array(
							'UPDATE'	=> "{$dbprefix}filters",
							'SET'		=> "filter_value='" . implode(':', array($stats['total_files'], $stats['total_images'], $stats['total_sizes']))  . "'",
							'WHERE'		=> "filter_type='stats_for_acp' AND filter_uid = '" . date('d-n-Y') . "'"
					);
	}
	else
	{
		$f_query = array(
							'INSERT'	=> 'filter_uid, filter_type ,filter_value ,filter_time',
							'INTO'		=> "{$dbprefix}filters",
							'VALUES'	=> "'" . date('d-n-Y') . "', 'stats_for_acp', '" . implode(':', array($stats['total_files'], $stats['total_images'], $stats['total_sizes'])) . "', " . time()
					);
	}

	$SQL->build($f_query);

}



#get banned ips data from stats table  ...
if (!($ban_system_values = $cache->get('data_ban')))
{
	$query = array(
					'SELECT'	=> 'f.filter_value, f.filter_status',
					'FROM'		=> "{$dbprefix}filters f",
					'WHERE'		=> "f.filter_type='ban_system'"
				);

	$result = $SQL->build($query);

	$stats = array();

	while($row=$SQL->fetch($result))
	{
		$ban_system_values[$row['filter_value']] = $row['filter_status'];
	}

	$SQL->free($result);

	$cache->save('data_ban', $ban_system_values);
}



#Get groups data
if (!($d_groups = $cache->get('data_groups')))
{
	$d_groups = array();

	#data
	$query = array(
					'SELECT'	=> 'g.*',
					'FROM'		=> "{$dbprefix}groups g",
					'ORDER_BY'	=> 'g.group_id ASC',
					);

	($hook = $plugin->run_hook('qr_select_groups_cache')) ? eval($hook) : null; //run hook
	$result = $SQL->build($query);

	#Initiating
	while($row=$SQL->fetch($result))
	{
		$d_groups[$row['group_id']]['data'] = $row;
		$d_groups[$row['group_id']]['configs'] = array();
		$d_groups[$row['group_id']]['acls'] = array();
		$d_groups[$row['group_id']]['exts'] = array();
	}
	$SQL->free($result);

	#configs
	$query = array(
					'SELECT'	=> 'g.group_id, g.name, g.value',
					'FROM'		=> "{$dbprefix}groups_data g",
					'ORDER_BY'	=> 'g.group_id ASC',
					);

	($hook = $plugin->run_hook('qr_select_groups_data_cache')) ? eval($hook) : null; //run hook
	$result = $SQL->build($query);

	while($row=$SQL->fetch($result))
	{
		$d_groups[$row['group_id']]['configs'][$row['name']] = $row['value'];
	}
	$SQL->free($result);

	#acl
	$query2 = array(
					'SELECT'	=> 'g.group_id, g.acl_name, g.acl_can',
					'FROM'		=> "{$dbprefix}groups_acl g",
					'ORDER_BY'	=> 'g.group_id ASC',
					);

	($hook = $plugin->run_hook('qr_select_groups_acls_cache')) ? eval($hook) : null; //run hook
	$result2 = $SQL->build($query2);

	while($row=$SQL->fetch($result2))
	{
		$d_groups[$row['group_id']]['acls'][$row['acl_name']] = (int) $row['acl_can'];
	}
	$SQL->free($result2);

	#exts
	$query3 = array(
					'SELECT'	=> 'g.group_id, g.ext, g.size',
					'FROM'		=> "{$dbprefix}groups_exts g",
					'ORDER_BY'	=> 'g.group_id ASC',
					);

	($hook = $plugin->run_hook('qr_select_groups_exts_cache')) ? eval($hook) : null; //run hook
	$result3 = $SQL->build($query3);

	while($row=$SQL->fetch($result3))
	{
		$d_groups[$row['group_id']]['exts'][$row['ext']] = (int) $row['size'];
	}
	$SQL->free($result3);

	unset($query, $query2, $query3, $result, $result2, $result3);

	$cache->save('data_groups', $d_groups);
}
