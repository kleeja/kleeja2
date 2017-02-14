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
$current_template	= "extensions.php";

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
					'exts_url'	=> ADMIN_PATH . '?cp=users&amp;smt=group_exts&amp;qg=' .$row['group_id'],
					'name'	=> get_group_name($row['group_name'], true),
					'is_default'	=> (int) $row['group_is_default'] ? true : false
			);


		$all_groups[] = $r;

	}
}


$SQL->free($result);
