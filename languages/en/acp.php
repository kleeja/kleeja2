<?php
/**
 * kleeja language file
 * @package Languages
 * @subpackage English
 *
 * @author Kleeja team & (NK, n.k@cityofangelz.com)
 */

/**
 * @ignore
 */
if (!defined('IN_COMMON'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'U_NOT_ADMIN' 			=> 'You do not have the administration permissions',
	'UPDATE_CONFIG' 		=> 'Update Settings',
	'NO_CHANGE' 			=> 'Do NOT change',
	'CHANGE_MD5' 			=> 'Change using MD5',
	'CHANGE_TIME' 			=> 'Change using TIME',
	'SITENAME' 				=> 'Service name',
	'SITEMAIL' 				=> 'Email address',
	'SITEMAIL2' 			=> 'Email address of reports',
	'SITEURL' 				=> 'Service URL with / at the end',
	'FOLDERNAME' 			=> 'Folder name for uploaded files',
	'PREFIXNAME' 			=> 'Files prefix <small>( you can also use {rand:4} , {date:d_Y})</small>',
	'FILESNUM' 				=> 'Number of upload input fields',
	'FILESNUM_SHOW' 		=> 'Show all upload inputs',
	'SITECLOSE' 			=> 'Shutdown service',
	'CLOSEMSG' 				=> 'Shutdown message',
	'DECODE' 				=> 'Change file name',
	'SEC_DOWN' 				=> 'Seconds before download',
	'STATFOOTER' 			=> 'Page statistics in footer',
	'GOOGLEANALYTICS' 		=> '<a href="http://www.google.com/analytics" target="_kleeja"><span style="color:orange">Google</span> Analytics</a>',
	'WELCOME_MSG' 			=> 'Welcome message',
	'USER_SYSTEM' 			=> 'Users system',
	'ENAB_REG' 				=> 'Allow registraion',
	'TOTAL_SIZE' 			=> 'Max service size[Mg]',
	'THUMBS_IMGS' 			=> 'Enable image thumbnails',
	'WRITE_IMGS' 			=> 'Enable image watermark',
	'ID_FORM' 				=> 'Id form',
	'IDF' 					=> 'File id in database (do.php?id=123)',
	'IDFF' 					=> 'File name (do.php?file=filename.pdf)',
	'IDFD' 					=> 'Directly (http://example.com/uploads/filename.pdf)',
	'DEL_URL_FILE' 			=> 'Enable file deletion URL feature',
	'WWW_URL' 				=> 'Enable uploading from URL',
	'ALLOW_STAT_PG' 		=> 'Enable statistics page',
	'ALLOW_ONLINE' 			=> 'Enable Who is Online',
	'MOD_WRITER' 			=> 'Mod Rewrite',
	'MOD_WRITER_EX' 		=> 'HTML links..',
	#'DEL_F_DAY' 			=> 'Delete undownloaded files in',
	'NUMFIELD_S' 			=> 'You can only use numbers with some fields !!',
	'CONFIGS_UPDATED' 		=> 'Settings updated successfully.',
	'UPDATE_EXTS' 			=> 'Update Extensions',
	'EXT_DELETED'			=> 'The extension deleted successfully.',
	'E_EXTS' 				=> 'Note : Sizes are measured in kilobytes .</i>',
	'UPDATED_EXTS' 			=> 'Extensions updated successfully.',
	'UPDATE_REPORTS' 		=> 'Update Reports',
	'E_CLICK' 				=> 'Select one to be viewed here',
	'REPLY' 				=> '[ Reply ]',
	'REPLY_REPORT' 			=> 'Reply on the report',
	'U_REPORT_ON' 			=> 'For your report about ',
	'BY_EMAIL' 				=> 'By email ',
	'ADMIN_REPLIED' 		=> 'Admin Reply',
	'CANT_SEND_MAIL' 		=> 'cannot send reply via email',
	'IS_SEND_MAIL' 			=> 'Reply has been sent.',
	'REPORTS_UPDATED' 		=> 'Reports have been updated.',
	'UPDATE_MESSAGES' 		=> 'Update Messages',
	'REPLY_MESSAGE' 		=> 'Reply on the message',
	'ABOUT_YOUR_MESSAGE' 	=> 'About your Message ',
	'MESSAGES_UPDATED' 		=> 'Messagess updated successfully.',
	'FOUNDER' 				=> 'Founder',
	'UPDATE_USERS' 			=> 'Update Users',
	'USER_UPDATED' 			=> 'User data has been updated successfully.',

	'KLEEJA_CP' 			=> 'Kleeja Administration',
	'GENERAL_STAT' 			=> 'General Stats',
	'OTHER_INFO' 			=> 'Other Info',
	'AFILES_NUM' 			=> 'Total number of files',
	'AFILES_SIZE' 			=> 'Total size of files',
	'AFILES_SIZE_SPACE' 	=> 'Space that has been consumed so far',
	'AUSERS_NUM' 			=> 'Total users',
	'LAST_GOOGLE' 			=> 'Last visit to Google',
	'GOOGLE_NUM' 			=> 'Google visits',
	'LAST_BING' 			=> 'Last visit to Bing',
	'BING_NUM' 			    => 'Bing visits',
	'KLEEJA_CP_W' 			=> 'Hello !, Welcome to ACP',
	'PHP_VER' 				=> 'php version',
	'MYSQL_VER' 			=> 'mysql version',
	'LOGOUT_CP_OK' 			=> 'Your administration session has been cleared ..',
	'R_OPTIONS' 			=> 'General Settings',
	'R_CPINDEX' 			=> 'Kleeja ACP Home',
	'R_EXTENSIONS' 				=> 'Extensions Settings',
	'R_FILES' 				=> 'Files Control',
	'R_REPORTS' 			=> 'Reports',
	'R_MESSAGES' 			=> 'Messages',
	'R_USERS' 				=> 'Users & Groups',
	'R_LGOUTCP' 			=> 'Clear Session',

	# ban.php
	'R_BAN' 				=> 'Ban Control', # ban.php
	'BAN_EXP1' 				=> 'Edit the banned users & IPs and add new ones here ..', # ban.php
	'BAN_EXP2' 				=> 'Use the star (*) symbol to replace numbers if you want a total ban for IPs like 1.*.*.*.', # ban.php
	'ADD_NEW_BAN' 			=> 'Ban an IP or username ', # ban.php
	'BAN_UPDATED' 			=> 'Changes saved successfully.', # ban.php
	'BAN_VALUE_EMPTY'		=> 'IP/Username field is empty!',
	'BAN_IP_NOT_VALID'		=> 'Given IP is not valid!',
	'BAN_USERNAME_NOT_FOUND'=> 'Given username is not existed!',
	'BAN_EXISTS_BEFORE'		=> 'Given username/IP is existed before!',
	'BAN_ADDED_SUCCESSFULLY'=>'Ban rule has been added successfully.',
	'BAN_DELETED_SUCCESSFULLY'=>'Ban rule has been deleted successfully.',


	'R_SEARCH' 				=> 'Advanced search',
	'SEARCH_FILES' 			=> 'Search for files',
	'SEARCH_SUBMIT' 		=> 'Search',
	'LAST_DOWN' 			=> 'Last download',
	'WAS_B4' 				=> 'Last Download Was before',
	'SEARCH_USERS' 			=> 'Search for users',
	'R_IMAGES' 				=> 'Image control only',
	'ENABLE_USERFILE' 		=> 'Enable users files',
	'R_PLUGINS' 					=> 'Plugins',
	'PLGUIN_DISABLED_ENABLED' 	=> 'Plugin Enabled / Disabled',

	# check_update.php
	'R_CHECK_UPDATE' 			=> 'Check for updates', # check_update.php
	'ERROR_CHECK_VER' 			=> 'Error: cannot get any update information at this momoent , try again later !', # check_update.php
	'UPDATE_KLJ_NOW' 			=> 'You Have to update your version now!. visit Kleeja.com for more inforamtion', # check_update.php
	'U_LAST_VER_KLJ' 			=> 'You are using the lastest version of Kleeja...', # check_update.php
	'U_USE_PRE_RE' 				=> 'You are using a Pre-release version, Click <a href="http://www.kleeja.com/bugs/">here</a> to report any bugs or exploits.', # check_update.php
	'UPDATE_NOW_S'				=>	'You are using an old version of Kleeja. Update Now. Your currect version is %1$s and the latest one is %2$s', # check_update.php
	'HOW_UPDATE_KLEEJA'			=> 'How to update Kleeja?', # check_update.php
	'HOW_UPDATE_KLEEJA_STEP1'	=> 'Visit the official website <a target="_blank" href="http://www.kleeja.com/">Kleeja.com</a> then go to the Download page and download the latest version of the script, or download an upgrade copy if available.',  # check_update.php
	'HOW_UPDATE_KLEEJA_STEP2'	=> 'Unzip the file and upload it to your website to replace the old files with the new ones <b>Except config.php</b>.',  # check_update.php
	'HOW_UPDATE_KLEEJA_STEP3'	=> 'When done, go to the following URL to update the database :', # check_update.php

	'MAKE_AS_DEFAULT'			=> 'Set as default',
	'ADD_NEW_EXT'				=> 'Add a new extension',
	'ADD_NEW_EXT_EXP'			=> 'Enter extension to add it to this group.',
	'EMPTY_EXT_FIELD'			=>	'The extension field is blank!',
	'NEW_EXT_ADD'				=>	'New extension added. ',
	'NEW_EXT_EXISTS_B4'			=>	'The extension %s already exists!.',
	'NOT_SAFE_FILE'				=> 'The file "%s" does not look safe !',
	'CONFIG_WRITEABLE'			=> 'The file config.php is currently writeable, We strongly recommend that it be changed to 640 or at least 644.',
	'NO_KLEEJA_COPYRIGHTS'		=> 'you seem to have accidentally removed the copyrights from the footer, please put it back on so we can continue to develop Kleeja free of charge, you can buy a copyright removal license %s .',
	'USERS_NOT_NORMAL_SYS'		=> 'The current users system is not the normal one, which means that the current users cannot be edited from here but from the script that was integrated with Kleeja, those users use the normal membership system.',
	'DIMENSIONS_THMB'			=> 'Thumbs dimensions',
	'THMB_DIM_W'				=> 'Thumbs WIDTH', #2.0
	'THMB_DIM_H'				=> 'Thumbs HEIGHT', #2.0
	'ADMIN_DELETE_FILE_ERR'		=> 'There is error occurred while trying to delete user files . ',
	'ADMIN_DELETE_FILE_OK'		=> 'Done ! ',
	'ADMIN_DELETE_FILES'		=> 'Delete all user files',
	'BCONVERTER' 				=> 'Byte Converter',
	'NO_HTACCESS_DIR_UP'		=> 'No .htaccess file was found in "%s" folder, Which means if malicious codes were injected a hacker can do damage to your website!',
	'NO_HTACCESS_DIR_UP_THUMB'	=> 'No .htaccess file was found in Thumbs folder "%s", Which means if malicious codes were injected a hacker can do damage to your website!',
	'COOKIE_DOMAIN' 			=> 'Cookie domain',
	'COOKIE_NAME' 				=> 'Cookie prefix',
	'COOKIE_PATH' 				=> 'Cookie path',
	'COOKIE_SECURE'				=> 'Cookie secure',
	'ADMINISTRATORS'			=> 'Administrators',
	'DELETEALLRES'				=> 'Delete all results',
	'ADMIN_DELETE_FILES_OK'     => 'File %s successfully deleted',
	'ADMIN_DELETE_FILES_NOF'	=> 'No files to delete',
	'NOT_EXSIT_USER'			=> 'Sorry, the user you are looking for does not exist in our database... perhaps you are trying to reach a deleted membership !!!!',
	'ADMIN_DELETE_NO_FILE'		=> 'This user has no files to delete ! .',
	'CONFIG_KLJ_MENUS_OTHER'	=> 'Other settings',
	'CONFIG_KLJ_MENUS_GENERAL'	=> 'General settings',
	'CONFIG_KLJ_MENUS_ALL'		=> 'Display all the settings',
	'CONFIG_KLJ_MENUS_UPLOAD'	=> 'Upload settings',
	'CONFIG_KLJ_MENUS_INTERFACE'=> 'Interface and design settings',
	'CONFIG_KLJ_MENUS_ADVANCED' => 'Advanced settings',
	'DELF_CAUTION'				=> '<span class="delf_caution">Caution: might be dangerous when using small numbers .</span>',
	'PLUGIN_N_CMPT_KLJ'			=> 'This plugin is not compatible with your current version of Kleeja.',
	'PHPINI_FILESIZE_SMALL'		=> 'Maximum file size allowed for your service is "%1$s" while upload_max_filesize in your hosts PHP settings is set to "%2$s" upload it so that your chosen size can be applied.',
	'PHPINI_MPOSTSIZE_SMALL'	=> 'You have allowed the upload of "%1$s" files at once, You need to use a bigger value for post_max_size in your servers PHP settings, something like "%2$s" for a better performance.',
	'NUMPER_REPORT' 			=> 'Number of reports',
	'NO_UP_CHANGE_S'			=> 'No changes ...',
	'ADMIN_USING_IE6'			=> 'You are using IE6, Please update your browser or use FireFox now!!',
	'FOOTER_TXTS'				=> array('PLUGINS'=> 'Plugins', 'STYLES'=>'Styles', 'BUGS'=>'Bug report'),
	'T_ISNT_WRITEABLE'			=> 'Cannot edit <strong>%s</strong> template. (Unwriteable)',
	'DEPEND_ON_NO_STYLE_ERR'	=> 'This style is based on the "%s" style which you dont seem to have',
	'PLUGINS_REQ_NO_STYLE_ERR'	=> 'This style requires the [ s% ] plugin(s), install it/them and try again.',
	'PLUGIN_REQ_BY_STYLE_ERR'	=> 'The current default style requires this plugin, to remove or disable it you need to change the default style first.',
	'KLJ_VER_NO_STYLE_ERR'		=> 'This style requires Kleeja version %s or above',
	'KLJ_STYLE_INFO'			=> 'Style info',
	'STYLE_NAME'				=> 'Style name',
	'STYLE_COPYRIGHT'			=> 'Copyrights',
	'STYLE_VERSION'				=> 'Style version',
	'STYLE_DEPEND_ON'			=> 'Based on',
	'MESSAGE_NONE'				=> 'No messages yet ...',
	'KLEEJA_TEAM'				=> 'Kleeja development team',
	'ERR_SEND_MAIL'				=> 'Mail sending error, try again later !',
	'FIND_IP_FILES' 			=> 'Found',
	'ALPHABETICAL_ORDER_FILES'	=> 'Sort files by alphabetical order',
	'ORDER_SIZE'				=> 'Sort files by size from largest to smallest',
	'ORDER_TOTAL_DOWNLOADS'		=> 'Sort files by number of downloads',
	'LIVEXTS'					=> 'Live Extensions (Files with these extensions will be downloaded/viewed directly, with <u>No</u> waiting page)',
	'COMMA_X'					=> '<p class="live_xts">separate by comma (<font style="font-size:large"> , </font>)</p>',
	'NO_SEARCH_WORD'			=> 'You didn\'t type anything in the search form !',
	'USERSECTOUPLOAD'			=> 'The seconds between each upload process',
	'ADM_UNWANTED_FILES'		=> 'You seem to have upgraded from a previous version, and because some file names are different now, you\'ll notice duplicated buttons in control panel. </ br> to solve this, remove all the files in "includes/adm" directory and re-upload them.',
	'ADVANCED_SETTINGS_CATUION' => 'Caution : you must know what these settings are in order to edit them!',
	'HTML_URLS_ENABLED_NO_HTCC'	=> 'you have enabled the htaccess URLs, but you seem to have forgot to move the config file from docs/.htaccess.txt to Kleeja\'s root directory. you also need to rename it to ".htaccess" however, if you don\'t know what i\'m talking about, go to Kleeja\'s support forums or simply disable the htaccess function.',
	'LOADING'					=> 'Loading',
	'ERROR'						=> 'There is an error, try again!.',
	'MORE'						=> 'More',
	'LESS'						=> 'Less',
	'MENU'						=> 'Menu',
	'WELCOME'					=> 'Welcome',
	'ENABLE_CAPTCHA'			=> 'Enable Captcha in Kleeja',
	'NO_THUMB_FOLDER'			=> 'It seems you enabled Thumbs but in same time the folder %s does not exist! create it.',
	'DELETE_EARLIER_30DAYS'		=> 'Delete files older than 30 days',
	'DELETE_ALL'				=> 'Delete all',
	'DELETE_PROCESS_QUEUED'		=> 'The delete process has been added to the waiting list to execute it gradually to reduce the load.',
	'DELETE_PROCESS_IN_WORK'	=> 'Currently, the delete process is executing ...',
	'SHOW_FROM_24H'				=> 'Show past 24 hours',

	'THUMB_DIS_LONGTIME'		=> 'Thumbs are disabled, this will force Kleeja to resize every images to be small here, and cost you time and bandwidth!. Enable thumbs now.',
	#1.5
	'R_GROUPS'					=> 'Groups Managment',
	'ESSENTIAL_GROUPS'			=> 'Fundamental Groups',
	'CUSTOM_GROUPS'				=> 'User-defined Groups',
	'EDIT_DATA'					=> 'Edit data',
	'EDIT_ACL'					=> 'Edit ACL',
	'HE_CAN'					=> 'Able',
	'HE_CAN_NOT'				=> 'Unable',
		#ACLS roles
		'ACLS_ENTER_ACP'		=> 'Access ACP',
		'ACLS_ACCESS_FILEUSER'	=> 'Access his own files\' folder',
		'ACLS_ACCESS_FILEUSERS'	=> 'Browse Any user files\' folder',
		'ACLS_ACCESS_CALL'		=> 'Access "call us" page',
		'ACLS_ACCESS_REPORT'	=> 'Show "Report" page',
		'ACLS_ACCESS_STATS'		=> 'Access "statistics" page',

	'GROUP_IS_DEFAULT'			=> 'This group is default at registeration',
	'ADD_NEW_GROUP'				=> 'Add new group',
	'DELETE_GROUP'				=> 'Delete group',
	'GROUP_NAME'				=> 'Group name',
	'COPY_FROM'					=> 'Copy from',
	'USERNAME_NOT_YOU'			=> 'Not you ? %1$slogout%2$s',
	'DEFAULT_GROUP'				=> 'The default group',
	'G_USERS_MOVE_TO'			=> 'Move the group users to',
	'TAKEN_NAMES'				=> 'This name is taken. Choose another name',
	'SUPPORT_MOFFED'			=> 'Support',
	'GROUP_DELETED'				=> 'Group "%1$s" has been deleted and its user moved to group "%2$s".',
	'NO_MOVE_SAME_GRP'			=> 'You can\'t move the users to the same group!.',
	'DEFAULT_GRP_NO_DEL'		=> 'You can\'t delete this group becuase it\'s the current default group, change the default group then try to delete it!.',
	'GROUP_ADDED'				=> 'Group "%s" has been added successfully ...',
	'SEARCH4FILES_BYIP'			=> 'Search files via selected IP',
	'SEARCH4FILES_BYUSER'		=> 'Search files for this user',
	'USER_DELETED'				=> 'User has been deleted successfully ...',
	'USER_ADDED'				=> 'User has been added successfully ...',
	'SELECT_GRP_CHNG_EXTS'		=> 'Select group to change its extensions settings',
	'DIRECT_FILE_NOTE'			=> 'Direct files have no stats.',
	'IMAGEFOLDER'				=> 'live folder',
	'IMAGEFOLDEREXTS'			=> 'Extensions of the live links files',
	'IMAGEFOLDERE'				=> 'Change file name',
	'CONFIG_KLJ_MENUS_KLIVE'	=> 'Kleeja Live links settings',
	'LAST_VIEW'					=> 'Last viewing',
	'HURRY_HURRY'				=> 'Hurry, Hurry',
	'SHOWFILESBYIP'				=> 'This IP files',
	'IP_INFO'					=> 'IP info',
	# maintenance.php
	'R_MAINTENANCE' 			=> 'Maintenance', # maintenance.php
	'RESYNC'					=> 're-sync', # maintenance.php
	'DEL_CACHE'					=> 'Delete Cache [temporary files]', # maintenance.php
	'SYNCING'					=> 'Sync\'ing is going on : (%s), wait ...', # maintenance.php
	'SYNCING_DONE'				=> 'Sync\'ing is done for (%s).', # maintenance.php
	'WHY_SYNCING'				=> 'Kleeja uses auto increment to not perform calculation of total numbers everytime, ' . # maintenance.php
									'this rises Kleeja performance. Use this after upgrade or when Kleeja asks you to.', # maintenance.php
	'REPAIR_DB_TABLES'			=> 'Repair Data base tables', # maintenance.php
	'SUPPORT_TXT_FILE'			=> 'A file that contains information about Kleeja on your website, to be given to the support', # maintenance.php
	'NO_RESULT_USE_SYNC'		=> 'There are no results, if you just installed Kleeja then that ok. <br /> If you just made an upgrade,'. # maintenance.php
	 								'then go to "Mentenance page" then do a "re-sync" for files or images.', # maintenance.php
	'REPAIRED_TABLE' 			=> '[Tables] Repaired. ', # maintenance.php
	'REPAIRED_CACHE' 			=> 'Cache has been deleted/refreshed.', # maintenance.php
	'REPAIRED_F_STAT' 			=> 'Stats has been sync\'ed.', # maintenance.php

	'TIME_FORMAT'				=> 'Time Format', #2.0
));
