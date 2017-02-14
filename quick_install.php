<?php
/**
 * Minimum PHP version to run Kleeja
 * @see https://secure.php.net/supported-versions.php
 */
define('MIN_PHP_VERSION', '5.6.0');

/**
 * Minimum MySQL version to run Kleeja
 */
define('MIN_MYSQL_VERSION', '5.6.0');

/**
 * Current version of Kleeja Database
 */
define ('LAST_DB_VERSION' , '100');

/**
 * If set false, SQL Errors will be shown
 */
define('MYSQL_NO_ERRORS', true);

define('IN_COMMON', true);
define('PATH', './');

$db_type = 'mysqli';

include_once  PATH . 'includes/functions/functions_alternative.php';

switch ($db_type)
{
	case 'mysqli':
		include PATH . 'includes/classes/mysqli.php';
	break;
	default:
		include PATH . 'includes/classes/mysql.php';
}

//includes
include  'languages/' . getlang() . '/install.php';

?>
<!doctype html>
<html lang="<?=getlang()?>">
<head>
	<meta charset="utf-8">
	<title><?=$lang['INST_INSTALL_WIZARD']?></title>

 	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

	<style>
	  body { text-align: center; padding: 100px;}
	  h1 { font-size: 40px; }
	  h2 { font-size:20px;}
	  h3{
		  line-height:1.8;
		  color: #4c4c4c;
	  }
	  body { font: 15px Helvetica, sans-serif; color: #333; direction: <?=$lang['DIR']?>;}
	  a { color: #dc8100; text-decoration: none; }
	  a:hover { color: #333; text-decoration: none; }
	  .message { border-radius: 25px; font-weight:bold; padding:4px;}
	  .info { color:blue; background-color:;}
	  .error {color:red; background-color:#ffe7f4;}
	  .button { border-radius: 10px; font-weight:bold; padding:10px 40px; background-color:#f2f2f2; color:#666; font-size:20px;}
	  .clearfix:after {
	       clear: both;overflow: auto;
		}
		.languages {
		    position: absolute;
			padding: 20px;
		    width: 100px;
		    height: auto;
		    /*background: blue;*/
		    top: 0;
		    left: 0%;
		}

	 </style>

</head>

<body>
	<div class="">
		<img src="<?=PATH?>images/kleeja.png">
	</div>
	<h1><?=$lang['INST_INSTALL_WIZARD']?></h1>

<?php if(isset($_GET['error'])): ?>
	<div class="message error">
		<?php switch($_GET['error']):
		 case 'no_connection': ?>
		<?=$lang['INST_CONNCET_ERR']?>
		<?php break;?>
		<?php case 'minmysql': ?>
	   <?=sprintf($lang['INST_MYSQL_LESSMIN'], MIN_MYSQL_VERSION)?>
	   <?php break;?>
	   <?php case 'minphp': ?>
	   <?=sprintf($lang['INST_PHP_LESSMIN'], MIN_PHP_VERSION, PHP_VERSION)?>
	  <?php break;?>
		<?php endswitch;?>
	</div>
<?php endif;?>

<?php if(!file_exists('config.php')):?>
	<div class="message error"><?=$lang['INST_MISSING_CONFIG']?></div>
<?php elseif(isset($_GET['do'])):

	#include important files
	$is_there_config = true;
	include PATH . 'config.php';


	//config.php
	if(isset($dbname) && isset($dbuser))
	{
		//connect .. for check
		$SQL = new database($dbserver, $dbuser, $dbpass, $dbname);

		if (!$SQL->is_connected())
		{
			header('Location: ./quick_install.php?error=no_connection');
			exit;
		}
		else
		{
			//try to chmod them
			if(function_exists('chmod'))
			{
				@chmod($_path . 'cache', 0777);
				@chmod($_path . 'uploads', 0777);
				@chmod($_path . 'uploads/thumbs', 0777);
			}

			if (version_compare($SQL->version(), MIN_MYSQL_VERSION, '<'))
			{
				header('Location: ./quick_install.php?error=minmysql');
				exit;
			}

			if (!function_exists('version_compare') || version_compare(PHP_VERSION, MIN_PHP_VERSION, '<'))
			{
				header('Location: ./quick_install.php?error=minphp');
				exit;
			}
		}
	}



	switch ($_GET['do']):
	case 'install':


	include_once  PATH . 'includes/classes/user.php';
	include PATH . 'includes/classes/plugins.php';
	$usrcp = $user	= new user;
	$plugin = new plugins();

	#random password
	//$rand_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'),0 ,10);
	$rand_password = 'admin';

	$user_salt			= substr(base64_encode(pack("H*", sha1(mt_rand()))), 0, 7);
	$user_pass 			= $usrcp->kleeja_hash_password($rand_password . $user_salt);
	$user_name 			= 'admin';
	$user_mail 			= 'user@admin.com';
	$config_sitename	= $lang['WEBSITE_NAME'];
	$config_siteurl		= 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/';
	$config_sitemail	= 'admin@admin.com';
	$config_time_zone	= '0';
	$config_urls_type	= 'id';
	$clean_name			= 'admin';

	#ok .. we will get sqls now ..
	include PATH . 'includes/install/install_sqls.php';
	include PATH . 'includes/install/default_values.php';

	$err = $dots = 0;
	$errors = '';

	#do important alter before
	$SQL->query($install_sqls['ALTER_DATABASE_UTF']);

	$sqls_done = $sql_err = array();
	foreach($install_sqls as $name=>$sql_content)
	{
		if($name == 'DROP_TABLES' || $name == 'ALTER_DATABASE_UTF')
		{
			continue;
		}

		#dreop tabe
		if(strpos($name, 'insert') === false)
		{
			$SQL->query("DROP TABLE IF EXISTS " . $dbprefix . $name .";");
		}

		if($SQL->query($sql_content))
		{
			$sqls_done[] = $name . '...';
		}
		else
		{
			$sql_err[] = $lang['INST_SQL_ERR'] . ' : ' . $name . '[basic]' . '   ' . $SQL->error();
			$err++;
		}
	}#for

	if($err == 0)
	{
		#add configs
		foreach($config_values as $cn)
		{
			if(empty($cn[6]))
			{
				$cn[6] = 0;
			}

			$sql = "INSERT INTO `{$dbprefix}config` (`name`, `value`, `option`, `display_order`, `type`, `plg_id`, `dynamic`) VALUES ('$cn[0]', '$cn[1]', '$cn[2]', '$cn[3]', '$cn[4]', '$cn[5]', '$cn[6]');";
			if(!$SQL->query($sql))
			{
				$sql_err[] =  'SQL insert error : [configs_values] ' . $cn . '   ' . $SQL->error();
				$err++;
			}
		}

		#add groups configs
		foreach($config_values as $cn)
		{
			if($cn[4] != 'groups' or !$cn[4])
			{
				continue;
			}

			$itxt = '';
			foreach(array(1, 2, 3) as $im)
			{
				$itxt .= ($itxt == '' ? '' : ','). "($im, '$cn[0]', '$cn[1]')";
			}

			$sql = "INSERT INTO `{$dbprefix}groups_data` (`group_id`, `name`, `value`) VALUES " . $itxt . ";";
			if(!$SQL->query($sql))
			{
				$sql_err[] =  'SQL insert error : [groups_configs_values] ' . $cn . '  ' . $SQL->error();
				$err++;
			}
		}

		#add exts
		foreach($ext_values as $gid=>$exts)
		{
			$itxt = '';
			foreach($exts as $t=>$v)
			{
				$itxt .= ($itxt == '' ? '' : ','). "('$t', $gid, $v)";
			}

			$sql = "INSERT INTO `{$dbprefix}groups_exts` (`ext`, `group_id`, `size`) VALUES " . $itxt . ";";
			if(!$SQL->query($sql))
			{
				$sql_err[] = 'SQL insert error : [ext_values] ' . $gid . '   ' . $SQL->error();
				$err++;
			}
		}

		#add acls
		foreach($acls_values as $cn=>$ct)
		{
			$it = 1;
			$itxt = '';
			foreach($ct as $ctk)
			{
				$itxt .= ($itxt == '' ? '' : ','). "('$cn', '$it', '$ctk')";
				$it++;
			}


			$sql = "INSERT INTO `{$dbprefix}groups_acl` (`acl_name`, `group_id`, `acl_can`) VALUES " . $itxt . ";";
			if(!$SQL->query($sql))
			{
				$sql_err[] = 'SQL insert error : [acl_values] ' . $cn . '   ' . $SQL->error();
				$err++;
			}
			$it++;
		}
	}



		if($err):?>
			<?php foreach($sql_err as $error_msg):?>
			<div class="message error"><?php echo $error_msg;?></div>
			<?php endforeach;?>
		<?php else:?>
			<div class="message info"><?=$lang['INST_FINISH_SQL']?><br>
				<?=$lang['USERNAME']?>: <b>admin</b><br>
				<?=$lang['PASSWORD']?>: <b><?=$rand_password?></b><br>
				<br>
				<br>
				<small style="color:#999"><?=$lang['INST_END']?></small>
				<br>
				<a href="./"><?=$lang['INDEX']?></a> | <a href="./admin"><?=$lang['ADMINCP']?></a>
			</div>
		<?php endif;

		break; //do = install

		case 'update':
		//get fles
		$s_path = PATH . "includes/install/update_files";
		$dh = opendir($s_path);
		$upfiles = array();

		$CurrentDatabase = inst_get_config('db_version');

		// echo $CurrentDatabase;
		while (($file = readdir($dh)) !== false)
		{
			if(strpos($file, '.php') !== false)
			{
				$db_ver = intval(str_replace('.php','', $file));
				// $db_ver = $order_update_files[$file];

				if((empty($CurrentDatabase) || $db_ver > $CurrentDatabase))
				{
					$upfiles[$db_ver] = $file;
				}
			}
		}
		@closedir($dh);

		ksort($upfiles);
		// print_r($upfiles);

		$err = array();

		if(!sizeof($upfiles)){
			echo '<span style="color:green;">' . $lang['INST_UPDATE_CUR_VER_IS_UP']. '</span>';
			break;
		}else{
			foreach($upfiles as $update_version=>$update_file){
				$file_for_up = 'includes/install/update_files/' . preg_replace('/[^a-z0-9_\-\.]/i', '', $update_file);
				if(!file_exists($file_for_up))
				{
					echo 'can not find ' .$file_for_up;
					continue;
				}
				$update_sqls = $update_functions = array();
				require $file_for_up;

				$SQL->show_errors = false;
				$complete_upate = true;
				if(isset($update_sqls) && sizeof($update_sqls) > 0)
				{

					foreach($update_sqls as $name=>$sql_content)
					{
						$err = '';
						$SQL->query($sql_content);
						$err[] = $SQL->error();
						$complete_upate = true;

						if(strpos($err[1], 'Duplicate') !== false || $err[0] == '1062' || $err[0] == '1060')
						{
							// $update_msgs_arr[] = '<span style="color:green;">' . $lang['INST_UPDATE_CUR_VER_IS_UP']. '</span>';
							$complete_upate = false;
						}
					}
				}
				//
				//is there any functions
				//
				if($complete_upate)
				{
					if(isset($update_functions) && sizeof($update_functions) > 0)
					{
						foreach($update_functions as $n)
						{
							call_user_func($n);
						}
					}
				}

				$sql = "UPDATE `{$dbprefix}config` SET `value` = '" . $update_version . "' WHERE `name` = 'db_version'";
				$SQL->query($sql);
			}
		}

		if(sizeof($err)):?>
			<?php foreach($err as $error_msg):?>
			<div class="message error"><?php echo $error_msg;?></div>
			<?php endforeach;?>
		<?php else:?>
			<div class="message info"><?=$lang['INST_FINISH_UPDATE']?><br>
				<br>
				<small style="color:#999"><?=$lang['INST_END']?></small>
				<br>
				<a href="./"><?=$lang['INDEX']?></a> | <a href="./admin"><?=$lang['ADMINCP']?></a>
			</div>
		<?php endif;


		break; //do = update
	endswitch;

	else:
		include  PATH . 'config.php';
		$install = true;
		$d = inst_get_config('language');
		if(!empty($d))
		{
			$install = false;
		}
		?>
		<h3><?=$lang['KLEEJA_TEAM_MSG_TEXT']?></h3>

		<div class="" style="color:#888"><?=$lang['INST_AGR_LICENSE']?></div>

	<br>
	<br>
	<br>

		<?php if($install):?>
		<div style="height:40px"><a class="button" href="./quick_install.php?do=install&amp;lang=<?=getlang()?>"><?=$lang['INST_INSTALL']?></a></div>
		<div class="clearfix"></div>
		<small style="color:#888"><?=$lang['INST_INSTALL_CLEAN']?></small>
		<?php else:?>
			<?php if(inst_get_config('db_version') >= LAST_DB_VERSION):?>
				<big style="color:green;"><?=$lang['INST_UPDATE_CUR_VER_IS_UP']?></big>
			<?php else:?>
			<div style="height:40px"><a class="button" href="./quick_install.php?do=update&amp;lang=<?=getlang()?>"><?=$lang['INST_UPDATE']?></a></div>
			<div class="clearfix"></div>
			<small style="color:#888">
			<?=sprintf($lang['INST_UPDATE_INFO'], inst_get_config('db_version'), LAST_DB_VERSION)?>
			</small>
			<?php endif;?>
		<?php endif;?>
		<br>
		<br>
		<br>

		<div style="border-top:1px dotted #ccc;padding:3px"></div>
		<small><i><?=$lang['IS_IT_OFFICIAL']?></i></small><br>
		<small style="text-align:left!important;color:#666666"><?=$lang['IS_IT_OFFICIAL_DESC']?></small>
<?php endif;?>

<div class="languages">
	<?php $langs = array_filter(glob(PATH . 'languages/*'), 'is_dir');?>
	<?php foreach($langs as $l):?>
		<a href="./quick_install.php?lang=<?=basename($l)?>"><img src="<?=PATH?>images/<?=basename($l)?>.png"></a>
	<?php endforeach;?>
</div>

</body>
</html>
<?php
/**
 * functions
 */
 function get_cookies_settings()
 {
 	$server_port = !empty($_SERVER['SERVER_PORT']) ? (int) $_SERVER['SERVER_PORT'] : (int) @getenv('SERVER_PORT');
 	$server_name = $server_name = (!empty($_SERVER['HTTP_HOST'])) ? strtolower($_SERVER['HTTP_HOST']) : ((!empty($_SERVER['SERVER_NAME'])) ? $_SERVER['SERVER_NAME'] : @getenv('SERVER_NAME'));

 	// HTTP HOST can carry a port number...
 	if (strpos($server_name, ':') !== false)
 		$server_name = substr($server_name, 0, strpos($server_name, ':'));


 	$cookie_secure	= isset($_SERVER['HTTPS'])  && $_SERVER['HTTPS'] == 'on' ? true : false;
 	$cookie_name	= 'klj_' . strtolower(substr(str_replace('0', 'z', base_convert(md5(mt_rand()), 16, 35)), 0, 5));

 	$name = (!empty($_SERVER['PHP_SELF'])) ? $_SERVER['PHP_SELF'] : getenv('PHP_SELF');
 	if (!$name)
 		$name = (!empty($_SERVER['REQUEST_URI'])) ? $_SERVER['REQUEST_URI'] : @getenv('REQUEST_URI');

 	$script_path = trim(dirname(str_replace(array('\\', '//'), '/', $name)));


 	if ($script_path !== '/')
 	{
 		if (substr($script_path, -1) == '/')
 			$script_path = substr($script_path, 0, -1);

 		$script_path = str_replace(array('../', './'), '', $script_path);
 		if ($script_path[0] != '/')
 			$script_path = '/' . $script_path;
 	}

 	$cookie_domain = $server_name;
 	if (strpos($cookie_domain, 'www.') === 0)
 	{
 		$cookie_domain = str_replace('www.', '.', $cookie_domain);
 	}

 	return array(
 		'server_name'	=> $server_name,
 		'cookie_secure'	=> $cookie_secure,
 		'cookie_name'	=> $cookie_name,
 		'cookie_domain'	=> $cookie_domain,
 		'cookie_path'	=> $script_path,
 	);
 }


function inst_get_config($name)
 {
 	global $SQL, $dbprefix;

 	if(!$SQL)
 	{
 		global $dbserver, $dbuser, $dbpass, $dbname;
 		if(!isset($dbserver))
 		{
 			return false;
 		}
 		$SQL = new database($dbserver, $dbuser, $dbpass, $dbname);
 	}

 	if(!$SQL)
 	{
 		return false;
 	}

 	$sql = "SELECT value FROM `{$dbprefix}config` WHERE `name` = '" . $name . "'";
 	$result	= $SQL->query($sql);
 	if($SQL->num($result) == 0)
 	{
 		return false;
 	}
 	else
 	{
 		$current_ver  = $SQL->fetch($result);
 		return $current_ver['value'];
 	}
 }

 function getlang()
 {
 	if (isset($_GET['lang']))
 	{
 		$_GET['lang'] = empty($_GET['lang']) ? 'en' : preg_replace('/[^a-z0-9]/i', '', $_GET['lang']);
 		return file_exists('languages/' . $_GET['lang'] . '/install.php') ? $_GET['lang'] : 'en';
 	}

 	return 'en';
 }
