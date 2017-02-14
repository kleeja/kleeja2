<?php
/**
*
* @package adm
* @version $Id: files.php 2240 2013-12-07 23:22:54Z phpfalcon@gmail.com $
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
$current_template	= 'files.php';
$url_lst	= ig('last_visit') ? '&amp;last_visit=' . g('last_visit', 'str') : '';
$url_sea	= ig('search_id') ? '&amp;search_id=' . g('search_id', 'str') : '';
$url_pg		= ig('page') ? '&amp;page=' . g('page', 'int') : '';
$page_action	= ADMIN_PATH . '?cp=' . basename(__file__, '.php')  . $url_sea . $url_lst;
$ord_action		= ADMIN_PATH . '?cp=' . basename(__file__, '.php') . $url_pg . $url_sea . $url_lst;
$page2_action	= ADMIN_PATH . '?cp=' . basename(__file__, '.php') .  $url_sea . $url_lst;
$action			= $page_action . $url_pg;
$is_search		= $affected = false;
$H_FORM_KEYS	= kleeja_add_form_key('adm_files');

//
// after submit
//
if (ip('submit'))
{
	#wrong form key
	if(!kleeja_check_form_key('adm_files'))
	{
		kleeja_admin_err($lang['INVALID_FORM_KEY'], $action);
	}

	#gather to-be-deleted file ids
	foreach ($_POST as $key => $value)
    {
        if(preg_match('/del_(?P<digit>\d+)/', $key))
        {
            $del[$key] = $value;
        }
    }

   #delete them once by once
   $ids = array();
   $files_num = $imgs_num = $sizes = 0;

    foreach ($del as $key => $id)
    {
        $query	= array(
						'SELECT'	=> 'f.id, f.name, f.folder, f.size, f.type',
						'FROM'			=> "{$dbprefix}files f",
						'WHERE'			=> 'f.id = ' . intval($id),
					);

		$result = $SQL->build($query);

		while($row=$SQL->fetch($result))
		{
			#delete file from folder
			@kleeja_unlink(PATH . $row['folder'] . '/' . $row['name']);
			#delete thumb
			if (file_exists(PATH . $row['folder'] . '/thumbs/' . $row['name'] ))
			{
				@kleeja_unlink(PATH . $row['folder'] . '/thumbs/' . $row['name'] );
			}

			$is_image = in_array(strtolower(trim($row['type'])), array('gif', 'jpg', 'jpeg', 'bmp', 'png')) ? true : false;

			$ids[] = $row['id'];
			if($is_image)
			{
				$imgs_num++;
			}
			else
			{
				$files_num++;
			}
			$sizes += $row['size'];
		}
	}

	$SQL->free($result);

	$affected = $SQL->affected();

	#delete files from the database
	if(isset($ids) && sizeof($ids))
	{
		$query_del = array(
								'DELETE'	=> "{$dbprefix}files",
								'WHERE'	=> "`id` IN (" . implode(',', $ids) . ")"
							);

		$SQL->build($query_del);

		#update number of stats
		update_stats('total_files', $files_num, '-');
		update_stats('total_images', $imgs_num, '-');
		update_stats('total_sizes', $sizes, '-');

		$cache->clean('data_stats');
	}

	#show msg now
	$text	= ($affected ? $lang['FILES_UPDATED'] : $lang['NO_UP_CHANGE_S']) .
				'<script type="text/javascript"> setTimeout("location.href=\'' . str_replace('&amp;', '&', $action) .  '\';", 2000);</script>' . "\n";
	$current_template	= 'info.php';
}
else
{

//
//Delete all user files [only one user]
//
if(ig('deletefiles'))
{
	$query	= array(
					'SELECT'	=> 'f.id, f.size, f.name, f.folder',
					'FROM'		=> "{$dbprefix}files f",
				);

	#get search filter
	$filter = get_filter(g('deletefiles', 'str'), 'filter_uid');

	if(!$filter)
	{
		kleeja_admin_err($lang['ADMIN_DELETE_FILES_NOF'], ADMIN_PATH . '?cp=' . basename(__file__, '.php'));
	}

	$query['WHERE'] = build_search_query(unserialize(htmlspecialchars_decode($filter['filter_value'])));

	if($query['WHERE'] == '')
	{
		kleeja_admin_err($lang['ADMIN_DELETE_FILES_NOF'], ADMIN_PATH . '?cp=' . basename(__file__, '.php'));
	}

	$result = $SQL->build($query);
	$sizes  = false;
	$ids = array();
	$files_num = $imgs_num = 0;
	while($row=$SQL->fetch($result))
	{
		#delete file from folder ..
		@kleeja_unlink(PATH . $row['folder'] . "/" . $row['name']);

		#delete thumb
		if (file_exists(PATH . $row['folder'] . "/thumbs/" . $row['name']))
		{
			@kleeja_unlink(PATH . $row['folder'] . "/thumbs/" . $row['name']);
		}

		$is_image = in_array(strtolower(trim($row['type'])), array('gif', 'jpg', 'jpeg', 'bmp', 'png')) ? true : false;

		$ids[] = $row['id'];
		if($is_image)
		{
			$imgs_num++;
		}
		else
		{
			$files_num++;
		}
		$sizes += $row['size'];
	}

	$SQL->free($result);

	if(($files_num + $imgs_num) == 0)
	{
		kleeja_admin_err($lang['ADMIN_DELETE_FILES_NOF'], ADMIN_PATH . '?cp=' . basename(__file__, '.php'));
	}
	else
	{
		#update number of stats
		update_stats('total_files', $files_num, '-');
		update_stats('total_images', $imgs_num, '-');
		update_stats('total_sizes', $sizes, '-');

		$cache->clean('data_stats');

		#delete all files in just one query
		$query_del	= array(
							'DELETE'	=> "{$dbprefix}files",
							'WHERE'	=> "`id` IN (" . implode(',', $ids) . ")"
						);

		$SQL->build($query_del);

		kleeja_admin_info(sprintf($lang['ADMIN_DELETE_FILES_OK'], $num), ADMIN_PATH . '?cp=' . basename(__file__, '.php'));
	}
}

//
//begin default files page
//
$query	= array(
				'SELECT'	=> 'COUNT(f.id) AS total_files',
				'FROM'		=> "{$dbprefix}files f",
				'ORDER BY'	=> 'f.id DESC'
		);


$do_not_query_total_files = false;

#posts search ..
if(ig('search_id'))
{
	#get search filter
	$filter = get_filter(g('search_id', 'str'), 'file_search', false, 'filter_uid');
	$deletelink = ADMIN_PATH . '?cp=' . basename(__file__, '.php') . '&deletefiles=' . g('search_id', 'str');
	$is_search	= true;
	$query['WHERE'] = build_search_query(unserialize($filter['filter_value']));

	if(strpos($query['WHERE'], 'u.name') !== false)
	{
		define('SHOW_USERNAMES_IN_SQL_FILES_ACP', true);
	}

	#for now, performace boost
	unset($query['ORDER BY']);
}
#list files as ordered by last visit
else if(ig('last_visit'))
{
	$query['WHERE']	= "f.time > " . g('last_visit', 'int', 0);
}
#list files as ordered by chosen field
#to-be-deleted
#it's becoming a headache for a big websites. we dont't have the time to figure out a solution
#else if(isset($_REQUEST['order_by']) && in_array($_REQUEST['order_by'], array('size', 'uploads', 'time', 'report')))
#{
#	$query['ORDER BY'] = "f." . $SQL->escape($_REQUEST['order_by']);
#}
else
{
	#list files as default, no need to request total files number
	$do_not_query_total_files = true;
}

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



#if not a search, show only files, not images
if(!ig('search_id'))
{
	#display files or display pics and files only in search
	$img_types = array('gif','jpg','png','bmp','jpeg','GIF','JPG','PNG','BMP','JPEG');
	$query['WHERE'] = (empty($query['WHERE']) ? '' : $query['WHERE'] . ' AND ') . "f.type NOT IN ('" . implode("', '", $img_types) . "')";
}

#get total files number or not
$nums_rows = 0;
if($do_not_query_total_files)
{
	$nums_rows = get_actual_stats('total_files');
}
else
{
	$result_p = $SQL->build($query);
	$n_fetch = $SQL->fetch($result_p);
	$nums_rows = $n_fetch['total_files'];
	$SQL->free($result_p);
}


#pagination
$currentPage= g('page', 'int', 1);
$Pager		= new pagination($perpage, $nums_rows, $currentPage);
$start		= $Pager->get_start_row();

$no_results = false;

if ($nums_rows > 0)
{
	$query['SELECT'] = 'f.*' . ((int) $config['user_system'] == 1 && defined('SHOW_USERNAMES_IN_SQL_FILES_ACP') ? ', u.name AS username' : '');
	$query['LIMIT']	= "$start, $perpage";
	$result = $SQL->build($query);
	$sizes = false;
	$num = 0;
	#if Kleeja is integtared with other user system,  we dont want make alot of queries
	$ids_and_names = $files_list = array();

	while($row=$SQL->fetch($result))
	{
		$userfile =  $config['siteurl'] . ($config['mod_writer'] ? 'fileuser-' . $row['user'] . '.html' : 'ucp.php?go=fileuser&amp;id=' . $row['user']);

		#for username from integrated user system
		if($row['user'] != '-1' and (int) $config['user_system'] != 1)
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

		$file_name = $row['real_filename'] == '' ? $row['name'] : $row['real_filename'];

		#files array
		$files_list[$row['id']]	= array(
						'id'		=> $row['id'],
						'name'		=> "<a title=\"" . $file_name . "\" href=\"./" . PATH . $row['folder'] . "/" . $row['name'] . "\" target=\"blank\">" .
												shorten_text($file_name, 25) . "</a>",
						'fullname'	=> $file_name,
						'size'		=> readable_size($row['size']),
						'ups'		=> $row['uploads'],
						'direct'	=> $row['id_form'] == 'direct' ? true : false,
						'time_human'=> kleeja_date($row['time']),
						'time'		=> kleeja_date($row['time'], false),
						'type'		=> $row['type'],
						'typeicon'	=> file_exists(PATH . "images/filetypes/".  $row['type'] . ".png") ? PATH . "images/filetypes/" . $row['type'] . ".png" : PATH. 'images/filetypes/file.png',
						'folder'	=> $row['folder'],
						'report'	=> ($row['report'] > 4) ? "<span style=\"color:red;font-weight:bold\">" . $row['report'] . "</span>":$row['report'],
						'user'		=> ($row['user'] == '-1') ? $lang['GUST'] :  '<a href="' . $userfile . '" target="_blank">' . (empty($row['username']) ? '' : $row['username']) . '</a>',
						'ip_info_link'	=> 'http://www.ripe.net/whois?form_type=simple&amp;full_query_string=&amp;searchtext=' . $row['user_ip'] . '&amp;do_search=Search',
						'ip'		=> $row['user_ip'],
						'showfilesbyip' => ADMIN_PATH . '?cp=search&amp;s_input=1&amp;s_value=' . $row['user_ip']
					);

		$del[$row['id']] = p('del_' . $row['id'], 'int', '');
	}

	$SQL->free($result);
}
else
{
	//no result ..
	$no_results = true;
}


#update f_lastvisit
if(!$is_search)
{
	if(filter_exists('f_lastvisit', 'filter_uid', 'lastvisit', $user->data['id']))
	{
		update_filter('f_lastvisit', time(), 'lastvisit', false, $user->data['id']);
	}
	else
	{
		insert_filter('f_lastvisit', time(), 'lastvisit', time(), $user->data['id']);
	}
}

#some vars
$total_pages	= $Pager->get_total_pages();
$total_pages	= $total_pages == 0 ? 1 : $total_pages;
$page_nums 		= $Pager->print_nums($page_action, '');
$current_page	= g('page', 'int', 1);;
}
