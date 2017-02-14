<?php
/**
*
* @package Kleeja
* @version $Id: index.php 2195 2013-10-30 02:19:34Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/


/**
 * We are in queue.php file, useful for exceptions
 */
define('IN_QUEUE', true);

/**
 * @ignore
 */
define('IN_KLEEJA', true);
define('PATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
include PATH . 'includes/common.php';


($hook = $plugin->run_hook('begin_queue_page')) ? eval($hook) : null; //run hook

#img header and print spacer gif
header('Cache-Control: no-cache');
header('Content-type: image/gif');
header('Content-length: 43');
echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');

#do some of the queue ..
if(preg_match('!:del_[a-z0-9]{0,3}messages:!i', $config['queue']))
{
	$table = 'messages';
	$for = strpos($config['queue'], ':del_allmessages:') !== false ? 'all' : 30;
}
elseif(preg_match('!:del_[a-z0-9]{0,3}reports:!i', $config['queue']))
{
	$table = 'reports';
	$for = strpos($config['queue'], ':del_allreports:') !== false ? 'all' : 30;
}


$days = intval(time() - 3600 * 24 * intval($for));

$query = array(
				'SELECT'	=> 'f.id, f.time',
				'FROM'		=> "`{$dbprefix}" . $table . "` f",
				'ORDER BY'	=> 'f.id ASC',
				'LIMIT'		=> '20',
				);


if($for != 'all')
{
	$query['WHERE']	= "f.time < $days";
}



($hook = $plugin->run_hook('qr_select_klj_clean_old_queue')) ? eval($hook) : null; //run hook

$result	= $SQL->build($query);
$num_to_delete = $SQL->num($result);
if($num_to_delete == 0)
{
	update_config('queue', preg_match('!:del_' . $for . $table . ':!i', '', $config['queue']));
	$SQL->freeresult($result);
	return;
}

$ids = array();
$num = 0;
while($row=$SQL->fetch($result))
{
	$ids[] = $row['id'];
	$num++;
}

$SQL->free($result);

$query_del	= array(
						'DELETE'	=> "`" . $dbprefix . $table . "`",
						'WHERE'	=> "id IN (" . implode(',', $ids) . ")"
					);

($hook = $plugin->run_hook('qr_del_delf_old_table')) ? eval($hook) : null; //run hook

$SQL->build($query_del);

#end
garbage_collection();
exit;
