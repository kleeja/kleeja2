<?php
/**
*
* @package adm
* @version $Id: images.php 2240 2013-12-07 23:22:54Z phpfalcon@gmail.com $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/

// not for directly open
if (!defined('IN_ADMIN'))
{
	exit();
}

#number of images in each page
if(!isset($images_cp_perpage) || !$images_cp_perpage)
{
	#you can add this varibale to config.php
	$images_cp_perpage = 25;
}

#style template
$current_template	= 'images.php';
$action	= ADMIN_PATH . '?cp=' . basename(__file__, '.php')  . (ig('page') ? '&amp;page=' . g('page', 'int') : '') .
			(ig('last_visit') ? '&amp;last_visit=' . g('last_visit', 'int') : '');
$action_search	= ADMIN_PATH . "?cp=search";
$H_FORM_KEYS	= kleeja_add_form_key('adm_img_ctrl');
$is_search		= false;

# after submit
if (ip('submit'))
{
	#check form key
	if(!kleeja_check_form_key('adm_img_ctrl'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], $action);
	}

	foreach ($_POST as $key => $value)
    {
        if(preg_match('/del_(?P<digit>\d+)/', $key))
        {
            $del[$key] = intval($value);
        }
    }

	$sizes = $num = 0;
    foreach ($del as $key => $id)
    {
        $query	= array(
						'SELECT'	=> '*',
						'FROM'		=> "{$dbprefix}files",
						'WHERE'		=> '`id` = ' . intval($id),
					);

		$result = $SQL->build($query);

		while($row=$SQL->fetch($result))
		{
			#delete image from folder ..
			@kleeja_unlink(PATH . $row['folder'] . '/' . $row['name']);
			#delete thumb
			if (file_exists(PATH . $row['folder'] . '/thumbs/' . $row['name']))
			{
				@kleeja_unlink(PATH . $row['folder'] . '/thumbs/' . $row['name']);
			}

			$ids[] = $row['id'];
			$num++;
			$sizes += $row['size'];
		}
	}

	$SQL->free($result);

	#no files to delete
	if(isset($ids) && sizeof($ids))
	{
		$query_del = array(
								'DELETE'	=> "{$dbprefix}files",
								'WHERE'	=> "`id` IN (" . implode(',', $ids) . ")"
							);

		$SQL->build($query_del);

		$affected = $SQL->affected();

		#update number of stats
		update_stats('total_images', $num, '-');
		update_stats('total_sizes', $sizes, '-');

		$cache->clean('data_stats');
	}

    #after submit
	$text	= ($affected ? $lang['FILES_UPDATED'] : $lang['NO_UP_CHANGE_S']) .
				'<script type="text/javascript"> setTimeout("get_kleeja_link(\'' . ADMIN_PATH . '?cp=' . basename(__file__, '.php') .
				'&page=' . (g('page', 'int', 1)) . '\');", 2000);</script>' . "\n";

	$current_template = "info.php";
}
else # else of if submit
{

$query	= array(
					'SELECT'	=> 'COUNT(f.id) AS total_images',
					'FROM'		=> "{$dbprefix}files f",
					'ORDER BY'	=> 'f.id DESC'
					);

#if user system is default, we use users table
if((int) $config['user_system'] == 1 && defined('SHOW_USERNAMES_IN_SQL_FILES_ACP'))
{
	$query['JOINS']	=	array(
								array(
									'LEFT JOIN'	=> "{$dbprefix}users u",
									'ON'		=> 'u.id=f.user'
								)
							);
}

$img_types = array('gif','jpg','png','bmp','jpeg','GIF','JPG','PNG','BMP','JPEG');

#
# There is a bug with IN statment in MySQL and they said it will solved at 6.0 version
# forums.mysql.com/read.php?10,243691,243888#msg-243888
# $query['WHERE']	= "f.type IN ('" . implode("', '", $img_types) . "')";
#

$query['WHERE'] = "(f.type = '" . implode("' OR f.type = '", $img_types) . "')";

$do_not_query_total_files = false;

if(ig('last_visit'))
{
	$query['WHERE']	.= " AND f.time > " . g('last_visit', 'int');
}
else
{
	$do_not_query_total_files = true;
}

$nums_rows = 0;
if($do_not_query_total_files)
{
	$nums_rows = get_actual_stats('total_images');
}
else
{
	$result_p = $SQL->build($query);
	$n_fetch = $SQL->fetch($result_p);
	$nums_rows = $n_fetch['total_images'];
	$SQL->free($result_p);
}

#pagination
$currentPage= g('page', 'int', 1);
$Pager		= new pagination($images_cp_perpage, $nums_rows, $currentPage);
$start		= $Pager->get_start_row();


$no_results = $affected = $sizes = false;
if ($nums_rows > 0)
{

	$query['SELECT'] = 'f.*' . ((int) $config['user_system'] == 1 && defined('SHOW_USERNAMES_IN_SQL_FILES_ACP') ? ', u.name AS username' : '');
	$query['LIMIT']	= "$start, $images_cp_perpage";
	$result = $SQL->build($query);

	$tdnum = $num = 0;
	#if Kleeja integtared we dont want make alot of queries
	$ids_and_names = array();

	while($row=$SQL->fetch($result))
	{
		#thumb ?
		#this might slow things
		$is_there_thumb = file_exists(PATH . $row['folder'] . '/thumbs/' . $row['name']) ? true : false;

		#for username in integrated user system
		$row['username'] = '';
		if($row['user'] != '-1' && (int) $config['user_system'] != 1 &&  defined('SHOW_USERNAMES_IN_SQL_FILES_ACP'))
		{
			if(!in_array($row['user'], $ids_and_names))
			{
				$row['username'] = $usrcp->usernamebyid($row['user']);
				$ids_and_names[$row['user']] = $row['username'];
			}
			else
			{
				$row['username'] = $ids_and_names[$row['user']];
			}
		}

		#make a list of the images
		$images_list[$row['id']]	= array(
						'id'		=> $row['id'],
						'tdnum'		=> $tdnum == 0 ? true : false,
						'tdnum2'	=> $tdnum == 4 ? true : false,
						'name'		=> ($row['real_filename'] == '' ? ((strlen($row['name']) > 25) ? substr($row['name'], 0, 20) . '...' : $row['name']) : ((strlen($row['real_filename']) > 20) ? str_replace('\'', "\'", substr($row['real_filename'], 0, 20)) . '...' : str_replace('\'', "\'", $row['real_filename']))),
						'ip' 		=> htmlspecialchars($row['user_ip']),
						'href'		=> PATH . $row['folder'] . '/' . $row['name'],
						'size'		=> readable_size($row['size']),
						'ups'		=> $row['uploads'],
						'time'		=> date('d-m-Y h:i a', $row['time']),
						'user'		=> (int) $row['user'] == -1 ? $lang['GUST'] : $row['username'],
						'is_user'	=> (int) $row['user'] == -1 ? 0 : 1,
						'is_thumb'	=> $is_there_thumb,
						'thumb_link'=> $is_there_thumb ? PATH . $row['folder'] . '/thumbs/' . $row['name'] :  PATH . $row['folder'] . '/' . $row['name'],
					);

		#fix for template
		$tdnum = $tdnum == 4 ? 0 : $tdnum+1;

		$del[$row['id']] = p('del_' . $row['id'], 'int', '');

	}

	$SQL->free($result);

} #/if num > 0
else
{
	$no_results = true;
}

#update i_lastvisit
if(!$is_search)
{
	if(filter_exists('i_lastvisit', 'filter_uid', 'lastvisit', $user->data['id']))
	{
		update_filter('i_lastvisit', time(), 'lastvisit', false, $user->data['id']);
	}
	else
	{
		insert_filter('i_lastvisit', time(), 'lastvisit', time(), $user->data['id']);
	}
}

#pages
$total_pages 	= $Pager->get_total_pages();
$page_nums 		= $Pager->print_nums(ADMIN_PATH. '?cp=' . basename(__file__, '.php') . (ig('last_visit') ? '&last_vists=' . g('last_visit', 'int') : '')
						, 'onclick="javascript:get_kleeja_link($(this).attr(\'href\'), \'#content\'); return false;"');
$current_page	= g('page', 'int', 1);

} # end of else of if submit
