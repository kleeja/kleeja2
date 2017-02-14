<?php
/**
*
* @package install
* @version $Id: install_sqls.php 1187 2009-10-18 23:10:13Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/

// not for directly open
if (!defined('IN_COMMON'))
{
	exit();
}

if(empty($install_sqls) || !is_array($install_sqls))
{
	$install_sqls = array();
}

$install_sqls['ALTER_DATABASE_UTF'] = "
ALTER DATABASE `{$dbname}` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin
";


$install_sqls['messages'] = "
CREATE TABLE `{$dbprefix}messages` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(200) collate utf8_bin NOT NULL,
  `text` varchar(350) collate utf8_bin NOT NULL,
  `mail` varchar(350) collate utf8_bin NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `ip` varchar(40) collate utf8_bin NOT NULL,
  `replied` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$install_sqls['reports'] = "
CREATE TABLE `{$dbprefix}reports` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(350) collate utf8_bin NOT NULL,
  `mail` varchar(350) collate utf8_bin NOT NULL,
  `url` varchar(250) collate utf8_bin NOT NULL,
  `text` varchar(400) collate utf8_bin NOT NULL,
  `time` int(11) unsigned NOT NULL,
  `ip` varchar(40) collate utf8_bin NOT NULL,
  `replied` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";



$install_sqls['users'] = "
CREATE TABLE `{$dbprefix}users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(300) collate utf8_bin NOT NULL,
  `group_id` int(11) unsigned NOT NULL DEFAULT '3',
  `password` varchar(200) collate utf8_bin NOT NULL,
  `password_salt` varchar(250) collate utf8_bin NOT NULL,
  `mail` varchar(350) collate utf8_bin NOT NULL,
  `founder` tinyint(1) NOT NULL default '0',
  `session_id` char(32) COLLATE utf8_bin NOT NULL DEFAULT '',
  `clean_name` varchar(300) collate utf8_bin NOT NULL,
  `last_visit` INT(11)  NOT NULL DEFAULT '0',
  `register_time` int(11) unsigned NOT NULL DEFAULT '0',
  `show_my_filecp` tinyint(1) unsigned NOT NULL default '1',
  `new_password` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  `hash_key` varchar(200) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `clean_name` (`clean_name`(300)),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$install_sqls['files'] = "
CREATE TABLE `{$dbprefix}files` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `last_down` int(11)  unsigned NOT NULL DEFAULT '0',
  `name` varchar(300) collate utf8_bin NOT NULL,
  `real_filename` VARCHAR( 350 ) collate utf8_bin NOT NULL,
  `size` int(11) unsigned  NOT NULL DEFAULT '0',
  `uploads` int(10) unsigned  NOT NULL DEFAULT '0',
  `time` int(11) unsigned NOT NULL,
  `type` varchar(100) collate utf8_bin NOT NULL,
  `folder` varchar(100) collate utf8_bin NOT NULL,
  `report` int(10) unsigned  NOT NULL DEFAULT '0',
  `user` int(10) NOT NULL default '-1',
  `code_del` varchar(150) collate utf8_bin NOT NULL,
  `user_ip` VARCHAR( 250 ) NOT NULL,
  `id_form` VARCHAR( 100 ) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `name` (`name`(300)),
  KEY `user` (`user`),
  KEY `code_del` (`code_del`(150)),
  KEY `time` (`time`),
  KEY `last_down` (`last_down`),
  KEY `type` (`type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$install_sqls['config'] = "
CREATE TABLE `{$dbprefix}config` (
  `name` varchar(255) collate utf8_bin NOT NULL,
  `value` varchar(255) collate utf8_bin NOT NULL,
  `option` mediumtext collate utf8_bin  NOT NULL,
  `display_order` int(10)  NOT NULL DEFAULT '1',
  `type` varchar(20) NULL DEFAULT 'other',
  `plg_id` int(11) unsigned NOT NULL DEFAULT '0',
  `dynamic` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`name`),
  KEY `type` (`type`),
  KEY `plg_id` (`plg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";


$install_sqls['plugins'] = "
CREATE TABLE `{$dbprefix}plugins` (
 `plg_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
 `plg_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
 `plg_ver` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
 `plg_first_run` tinyint(1) unsigned NOT NULL DEFAULT '0',
 `plg_uninstall` mediumtext CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
 `plg_disabled` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`plg_id`),
  KEY `plg_name` (`plg_name`),
	 KEY `plg_first_run` (`plg_first_run`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";


$install_sqls['groups'] = "
CREATE TABLE `{$dbprefix}groups` (
  `group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `group_is_default` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `group_is_essential` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
";

$install_sqls['groups_data'] = "
CREATE TABLE `{$dbprefix}groups_data` (
  `group_id` int(11) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `value` varchar(255) COLLATE utf8_bin NOT NULL,
  KEY `group_id` (`group_id`),
 UNIQUE KEY `group_id_and_name` (`group_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

$install_sqls['groups_acl'] = "
CREATE TABLE `{$dbprefix}groups_acl` (
  `acl_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  `acl_can` tinyint(1) unsigned NOT NULL DEFAULT '0',
  KEY `group_id` (`group_id`),
  UNIQUE KEY `group_id_and_acl_name` (`acl_name`,`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
";

$install_sqls['groups_exts'] = "
CREATE TABLE `{$dbprefix}groups_exts` (
  `ext_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ext` varchar(20) COLLATE utf8_bin NOT NULL,
  `group_id` int(11) unsigned NOT NULL DEFAULT '0',
  `size` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ext_id`),
  KEY `group_id` (`group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
";

$install_sqls['filters'] = "
CREATE TABLE `{$dbprefix}filters` (
  `filter_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `filter_uid` varchar(30) COLLATE utf8_bin  NOT NULL DEFAULT '',
  `filter_type` varchar(20) COLLATE utf8_bin NOT NULL,
  `filter_value` varchar(255) COLLATE utf8_bin NOT NULL,
  `filter_time` int(11) unsigned  NOT NULL DEFAULT '0',
  `filter_user` int(11) unsigned NOT NULL DEFAULT '0',
  `filter_status` varchar(50) COLLATE utf8_bin NOT NULL DEFAULT '',
  PRIMARY KEY (`filter_id`),
  KEY `filter_user` (`filter_user`),
  KEY `filter_uid` (`filter_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
";


$install_sqls['users_insert'] = "INSERT INTO `{$dbprefix}users` (`id`,`name`,`group_id`,`password`,`password_salt`,`mail`,`founder`,`clean_name`) VALUES (1,'" . $user_name . "', 1, '" . $user_pass . "','" . $user_salt . "', '" . $user_mail . "', 1,'" . $clean_name . "')";
$install_sqls['TeamMsg_insert'] = "INSERT INTO `{$dbprefix}messages` (`name`,`text`,`mail`,`time`,`ip`) VALUES ('" . $SQL->escape($lang['KLEEJA_TEAM_MSG_NAME']) . "', '" . $SQL->escape($lang['KLEEJA_TEAM_MSG_TEXT']) . "','', " . time() . ", '127.0.0.1')";
$install_sqls['groups_insert'] = "INSERT INTO `{$dbprefix}groups` (`group_id`, `group_name`, `group_is_default`, `group_is_essential`) VALUES
(1, '{lang.ADMINS}', 0, 1),
(2, '{lang.GUESTS}', 0, 1),
(3, '{lang.USERS}', 1, 1);";
