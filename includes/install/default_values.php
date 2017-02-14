<?php
/**
*
* @package install
* @version $Id: install_sqls.php 1187 2009-10-18 23:10:13Z saanina $
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


//
// Configuration values
//

$config_values = array();

// do it like this :
//$config_values = array('name', 'value', 'option', 'display_order', 'type', 'plg_id', 'dynamic');

// General settings
$config_values[] = array('sitename', $config_sitename, '{text.sitename}', 1, 'general', 0, 0);
$config_values[] = array('siteurl', $config_siteurl, '{ltr.siteurl}', 2, 'general', 0, 0);
$config_values[] = array('sitemail', $config_sitemail,'{ltr.sitemail}', 3, 'general', 0, 0);
$config_values[] = array('sitemail2', $config_sitemail, '{ltr.sitemail2}', 4, 'general', 0, 0);
$config_values[] = array('time_zone', $config_time_zone, '{select.time_zone}', 10, 'general', 0, 0);
$config_values[] = array('time_format', 'd-m-Y h:i a', '{ltr.time_format}', 11, 'general', 0, 0);
$config_values[] = array('siteclose', '0', '{yesno.siteclose}', 7, 'general', 0, 0);
$config_values[] = array('closemsg', 'The website is closed now, sorry for the inconvenience, come back later.', '{text.closemsg}', 8, 'general', 0, 0);
$config_values[] = array('register', '1', '{yesno.register}', 12, 'general', 0, 0);


//$config_values[] = array('del_f_day', '0', '<input type=\"text\" id=\"del_f_day\" name=\"del_f_day\" value=\"{con.del_f_day}\" size=\"6\" style=\"text-align:center\" />{lang.DELF_CAUTION}', 5, 'advanced', 0, 0);


$config_values[] = array('enable_userfile', '1', '{yesno.enable_userfile}', 11, 'groups', 0, 0);
$config_values[] = array('filesnum', '3', '{ltr.filesnum}', 22, 'groups', 0, 0);
$config_values[] = array('sec_down', '5', '{ltr.sec_down}', 23, 'groups', 0, 0);
$config_values[] = array('usersectoupload', '10', '{ltr.usersectoupload}', 44, 'groups', 0, 0);
$config_values[] = array('write_imgs', '0' , '{yesno.write_imgs} ', 29, 'groups', 0, 0);


// advanced settings
$config_values[] = array('mod_writer', '0', '{yesno.mod_writer.MOD_WRITER_EX}', 12, 'advanced', 0, 0);
$cookie_data = get_cookies_settings();
$config_values[] = array('cookie_name', $cookie_data['cookie_name'], '{ltr.cookie_name}', '13', 'advanced', 0, 0);
$config_values[] = array('cookie_path', $cookie_data['cookie_path'], '{ltr.cookie_path}', '14', 'advanced', 0, 0);
$config_values[] = array('cookie_domain', $cookie_data['cookie_domain'], '{ltr.cookie_domain}', '15', 'advanced', 0, 0);
$config_values[] = array('cookie_secure', ($cookie_data['cookie_secure'] ? '1' : '0'), '{yesno.cookie_secure}', '16', 'advanced', 0, 0);

// Upload settings
$config_values[] = array('total_size', '10000000000', '{ltr.total_size}', 17, 'upload', 0, 0);
$config_values[] = array('foldername', 'uploads', '{ltr.foldername}', 18, 'upload', 0, 0);
$config_values[] = array('prefixname', '', '{ltr.prefixname}', 19, 'upload', 0, 0);
$config_values[] = array('decode', '1', '{select.decode}', 20, 'upload', 0, 0);
$config_values[] = array('id_form', $config_urls_type, '{select.id_form}', 21, 'upload', 0, 0);

$config_values[] = array('del_url_file', '1', '{yesno.del_url_file}', 24, 'upload', 0, 0);
$config_values[] = array('safe_code', '0', '{yesno.safe_code}', 25, 'upload', 0, 0);


$config_values[] = array('thmb_dim_w', '100', '{ltr.thmb_dim_w}', 28, 'upload', 0, 0);
$config_values[] = array('thmb_dim_h', '100', '{ltr.thmb_dim_h}', 28, 'upload', 0, 0);

#TODO
$config_values[] = array('thumbs_imgs', '1', '{yesno.thumbs_imgs}', 27, 'upload', 0, 0);
$config_values[] = array('livexts', 'swf', '{ltr.livexts.COMMA_X}', '29', 'upload', 0, 0);
$config_values[] = array('filesnum_show', '1', '{yesno.filesnum_show}', 22, 'upload', 0, 0);

//KLIVE
$config_values[] = array('imagefolder', 'uploads', '{ltr.imagefolder}', '10', 'KLIVE', '0', '0');
$config_values[] = array('imagefolderexts', '', '{ltr.imagefolderexts}', '20', 'KLIVE', '0', '0');
$config_values[] = array('imagefoldere', '1', '{yesno.imagefoldere}', '30', 'KLIVE', '0', '0');

// Interface settings
$config_values[] = array('welcome_msg', $lang['INST_MSGINS'], '{text.welcome_msg}', 30, 'interface', 0, 0);
$config_values[] = array('allow_stat_pg', '1', '{yesno.allow_stat_pg}', 31, 'interface', 0, 0);
$config_values[] = array('allow_online', '0', '{yesno.allow_online}', 32, 'interface', 0, 0);
$config_values[] = array('statfooter', '0' , '{yesno.statfooter}', 33, 'interface', 0, 0);
$config_values[] = array('googleanalytics', '', '{ltr.googleanalytics}', 35, 'interface', 0, 0);
$config_values[] = array('enable_captcha', '1', '{yesno.enable_captcha}', 36, 'interface', 0, 0);
$config_values[] = array('language', getlang(), '{select.language}', 6, 'interface', 0, 0);

// System settings [ invisible configs ]
$config_values[] = array('thmb_dims', '100*100', '', 0, 0, 0);
$config_values[] = array('style', 'default', '', 0, '0', 0, 0);
$config_values[] = array('new_version', '', '', 0, 0, 0);
$config_values[] = array('db_version', LAST_DB_VERSION, '', 0, 0, 0);
$config_values[] = array('last_online_time_update', time(), '', 0, 0, 1);
$config_values[] = array('klj_clean_files_from', '0', '', 0, 0, 1);
$config_values[] = array('style_depend_on', '', '', 0, 0, 0);
$config_values[] = array('most_user_online_ever', '', '', 0, 0, 1);
$config_values[] = array('expand_menu', '0', '', 0, 0, 1);
$config_values[] = array('firstime', '0', '', 0, 0, 1);
$config_values[] = array('ftp_info', '', '', 0, 0, 0);
$config_values[] = array('queue', '', '', 0, 0, 1);
$config_values[] = array('default_group', '3', '', 0, 0, 1);
$config_values[] = array('user_system', '1', '', 0, 0, 0, 0);

//
// Extensions
//

// do it like this :
//$ext_values[group_id] = array('ext'=>sizeInKB);
$ext_values = array();

#admins
$ext_values[1] = array(
			'gif' => 2097152,
			'png' => 2097152,
			'jpg' => 2097152,
			'jpeg' => 2097152,
			'bmp' => 2097152,
			'zip' => 2097152,
			'rar' => 2097152,
);
#guests
$ext_values[2] = array(
			'gif' => 2097152,
			'png' => 2097152,
			'jpg' => 2097152,
			'jpeg' => 2097152,
			'bmp' => 2097152,
			'zip' => 2097152,
			'rar' => 2097152,
);
#users
$ext_values[3] = array(
			'gif' => 2097152,
			'png' => 2097152,
			'jpg' => 2097152,
			'jpeg' => 2097152,
			'bmp' => 2097152,
			'zip' => 2097152,
			'rar' => 2097152,
);


//
// ACLs
//

$acls_values = array();

//$acls_values['name of acl'] = array(admins, guests, users);
$acls_values['enter_acp'] = array(1, 0, 0);
$acls_values['access_fileuser'] = array(1, 0, 1);
$acls_values['access_fileusers'] = array(1, 1, 1);
$acls_values['access_stats'] = array(1, 1, 1);
$acls_values['access_call'] = array(1, 1, 1);
$acls_values['access_report'] = array(0, 0, 0);
