<?php
/**
*
* @package Kleeja
* @version $Id: functions_display.php 2236 2013-11-30 10:07:23Z saanina $
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
 * Print header part of the page
 *
 * @param string $title [optional] The page title
 * @param string $extra_head_code [optional] any extra codes to include it between head tag
 * @return void
 */
function kleeja_header($title = '', $extra_head_code = '')
{
	global $user, $lang, $config, $plugin;

	#is user ? and username
	$username = $user->is_user() ? $user->data['name'] : $lang['GUST'];

	#our default charset
	$charset = 'utf-8';


	($hook = $plugin->run_hook('kleeja_header_links_func')) ? eval($hook) : null; //run hook


	$current_page = ig('go') ? g('go', 'str') : (empty($_GET) ? 'index' : '');


	//kleeja_add_form_key('login');
	//$tpl->assign("action_login", 'ucp.php?go=login' . (isset($_GET['return']) ? '&amp;return=' . htmlspecialchars($_GET['return']) : ''));

	include get_template_path('header.php');

	($hook = $plugin->run_hook('kleeja_header_func')) ? eval($hook) : null; //run hook

	flush();
}


/**
 * Print footer part of the page
 *
 * @return void
 */
function kleeja_footer()
{
	global $SQL, $starttm, $config, $user, $lang, $plugin;

	#show stats ..
	$page_stats = false;
	if ($config['statfooter'] != 0 || DEV_STAGE)
	{
		$hksys			= !defined('STOP_HOOKS') ? 'Enabled' : 'Disabled';
		$endtime		= get_microtime();
		$loadtime		= number_format($endtime - $starttm , 4);
		$queries_num	= $SQL->query_num;
		$time_sql		= round($SQL->query_num / $loadtime) ;
		$time_zone		= (strpos($config['time_zone'], '-') !== false ? '' : '+') . $config['time_zone'];
		$page_stats		= "<strong>[</strong> Generation Time: $loadtime Sec  - Queries: $queries_num - Hook System:  $hksys - Time Zone: UTC$time_zone<strong> ]</strong>  " ;
	}

	#if google analytics is enabled, show it
	$google_analytics = false;
	if (strlen($config['googleanalytics']) > 4)
	{
		$google_analytics .= '<script type="text/javascript">' . "\n";
		$google_analytics .= '<!--' . "\n";
		$google_analytics .= 'var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");' . "\n";
		$google_analytics .= 'document.write("\<script src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'>\<\/script>" );' . "\n";
		$google_analytics .= '-->' . "\n";
		$google_analytics .= '</script>' . "\n";
		$google_analytics .= '<script type="text/javascript">' . "\n";
		$google_analytics .= '<!--' . "\n";
		$google_analytics .= 'var pageTracker = _gat._getTracker("' . $config['googleanalytics'] . '");' . "\n";
		$google_analytics .= 'pageTracker._initData();' . "\n";
		$google_analytics .= 'pageTracker._trackPageview();' . "\n";
		$google_analytics .= '-->' . "\n";
		$google_analytics .= '</script>' . "\n";
	}



	($hook = $plugin->run_hook('kleeja_footer_func')) ? eval($hook) : null; //run hook


	$k = '<div sty' . 'le="di' . 'spl'. 'ay:bl'. 'oc' . 'k !im' . 'po' . 'rt' . 'ant;' . 'backgrou' . 'nd:#ECE' .'CE' . 'C !im' . 'po' . 'rt' .
	'ant;margin:5p' . 'x; padding:2px 3px; position:fi' . 'xed;bottom' . ':0px;left:1%' . ';z-index:9' . '9999;text' . '-align:center;">P' .
	'owe' . 'red b' . 'y <a style="di' . 'spl'. 'ay:in'. 'li' . 'ne  !im' . 'po' . 'rt' . 'ant;' . 'color:#6' .
	'2B4E8 !im' . 'po' . 'rt' . 'ant;" href="http:' . '/' . '/ww' . 'w.kl' . 'ee' . 'ja.c' . 'om/" onclic' . 'k="windo' . 'w.op' . 'en(this.h' .
	'ref,' . '\'_b' . 'lank\');retur' . 'n false;" title' . '="K' . 'lee' . 'ja">K' . 'lee' . 'ja</a></div>' . "\n";

	$v = @unserialize($config['new_version']);
	if((int) $v[strip_tags('co<!--it-->py<!--made-->ri<!--for-->gh<!--you-->ts<!--yub-->')] == /*kleeja is sweety*/0/*SO, be sweety*/)
	{
		echo $k;
	}

	include get_template_path('footer.php');

	#at end, close sql connections & etc
	garbage_collection();
}


/**
 * Get the link of any kleeja page, respect rewrite mod option
 *
 * @param string $name The page you want the url of.
 * @param bool $get_default Get default link despite rewrite mod option
 * @return string The link
 */
function get_url_of($name, $get_default = false)
{
	global $config, $plugin;

	$urls = array(
		'profile' => array('ucp.php?go=profile', 'profile.html'),
		'fileuser' => array('ucp.php?go=fileuser', 'fileuser.html'),
		'logout' => array('ucp.php?go=logout', 'logout.html'),
		'login' => array('ucp.php?go=login', 'login.html'),
		'register' => array('ucp.php?go=register', 'register.html'),
		'get_pass' => array('ucp.php?go=get_pass', 'get_pass.html'),

		'index' => array($config['siteurl'], $config['siteurl']),
		'guide' => array('go.php?go=guide', 'guide.html'),
		'stats' => array('go.php?go=stats', 'stats.html'),
		'report' => array('go.php?go=report', 'report.html'),
		'call' => array('go.php?go=call', 'call.html'),
	);

	($hook = $plugin->run_hook('get_url_of_func')) ? eval($hook) : null; //run hook

	$link = $config['siteurl'];
	if(isset($urls[$name]) && $name != 'index')
	{
		$link .= !$config['mod_writer'] || $get_default ? $urls[$name][0] : $urls[$name][1];
	}

	return $link;
}


/**
 * Return the absoulte path of a template, if not exists get it from the parent style
 *
 * @param string $name The template name
 * @return mixed Template path if exists or false if not
 */
function get_template_path($name)
{
	$folder_path = defined('IN_ADMIN') ? ADMIN_STYLE_PATH_ABS : STYLE_PATH_ABS;

	if(file_exists($folder_path . $name))
	{
		return $folder_path . $name;
	}
	else if(file_exists(PARENT_STYLE_PATH_ABS . $name))
	{
		return PARENT_STYLE_PATH_ABS . $name;
	}
	else
	{
		echo $folder_path;
		global $text;
		$text = 'Requested "' . $name . '" template doesnt exists or an empty!';
		return get_template_path('error.php');
	}
}


/**
 * To return file size in propriate format
 *
 * @param int $size the size to be costumized
 * @return string Size in a readable formate
 */
function readable_size($size)
{
	global $plugin;

	$sizes = array(' B', ' KB', ' MB', ' GB', ' TB', 'PB', ' EB');
	$ext = $sizes[0];
	for ($i=1; (($i < count($sizes)) && ($size >= 1024)); $i++)
	{
		$size = $size / 1024;
		$ext  = $sizes[$i];
	}
	$result	=	 round($size, 2) . $ext;
	($hook = $plugin->run_hook('func_readable_size')) ? eval($hook) : null; //run hook
	return  $result;
}


/**
 * Show an Error message
 *
 * @param string $text Text that will show as error message
 * @param bool|string $redirect [optional] Redirect to given link after message
 * @param string $extra_code_header [optional] extra codes to be included between head tag
 * @param string $style [optional] The template of the inforamtion message
 * @return void
 */
function kleeja_error($text, $redirect = false, $extra_code_header = '', $style = 'error.php')
{
	global $SQL, $plugin;

	($hook = $plugin->run_hook('kleeja_error_func')) ? eval($hook) : null; //run hook

	#header
	kleeja_header('', $extra_code_header);
	#template
	include get_template_path($style);
	#footer
	kleeja_footer();


	if($redirect)
	{
		redirect($redirect, false);
	}
	else
	{
		#at end, close sql connections & etc
		garbage_collection();
		exit();
	}
}


/**
 * Show an inforamtion message
 *
 * @param string $text Text that will show as inforamtion message
 * @param bool|string $redirect [optional] Redirect to given link after message
 * @param string $extra_code_header [optional] extra codes to be included between head tag
 * @return void
 */
function kleeja_info($text, $redirect = false, $extra_code_header = '')
{
	global $SQL, $plugin;

	($hook = $plugin->run_hook('kleeja_info_func')) ? eval($hook) : null; //run hook

	return kleeja_error($text, $redirect, $extra_code_header, 'info.php');
}


/**
 * Show error of a critical problem
 *
 * @param string $error_title Title of the error page
 * @param string $msg_text Text of the error message
 * @param bool $error [optional] if false, error will be shown as inforamtion message
 * @return viod
 */
function big_error($error_title, $msg_text, $error = true)
{
	global $SQL, $plugin;

	if(!empty($plugin))
	{
		($hook = $plugin->run_hook('big_error_func')) ? eval($hook) : null; //run hook
	}

	echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">' . "\n";
	echo '<head>' . "\n";
	echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />' . "\n";
	echo '<title>' . htmlspecialchars($error_title) . '</title>' . "\n";
	echo '<style type="text/css">' . "\n\t";
	echo '* { margin: 0; padding: 0; }' . "\n\t";
	echo '.error {color: #333;background:#ffebe8;float:left;width:73%;text-align:left;margin-top:10px;border: 1px solid #dd3c10;} .info {color: #333;background:#fff9d7;border: 1px solid #e2c822;}' . "\n\t";
	echo '.error,.info {padding: 10px;font-family:"lucida grande", tahoma, verdana, arial, sans-serif;font-size: 12px;}' . "\n";
	echo '</style>' . "\n";
	echo '</head>' . "\n";
	echo '<body>' . "\n\t";
	echo '<div class="' . ($error ? 'error' : 'info') . '">' . "\n";
	echo "\n\t\t<h2>Kleeja " . ($error ? 'error' : 'information message') . " : </h2><br />" . "\n";
	echo "\n\t\t<strong> [ " . $error_title . ' ] </strong><br /><br />' . "\n\t\t" . $msg_text . "\n\t";
	echo "\n\t\t" . '<br /><br /><small>Visit <a href="http://www.kleeja.com/" title="kleeja">Kleeja</a> Website for more details.</small>' . "\n\t";
	echo '</div>' . "\n";
	echo '</body>' . "\n";
	echo '</html>';

	#at end, close sql connections & etc
	garbage_collection();
	exit();
}


/**
 * Redirect to a link after *given* seconds
 *
 * @param string $url The link to redirect to
 * @param bool $header [optional] Redirct using header:location or html meta
 * @return void|string
 */
function redirect($url, $header = true)
{
	global $SQL, $plugin, $go_to;

	($hook = $plugin->run_hook('redirect_func')) ? eval($hook) : null; //run hook

    if (!headers_sent() && $header)
	{
		header('Location: ' . str_replace(array('&amp;'), array('&'), $url));
    }
	else
	{
		$gre = '<script type="text/javascript"> setTimeout("window.location.href = \'' .  str_replace(array('&amp;'), array('&'), $url) . '\'", ' . 4*1000 . '); </script>';
		$gre .= '<noscript><meta http-equiv="refresh" content="' . 4 . ';url=' . $url . '" /></noscript>';

		echo $gre;
	}

	#at end, close sql connections & etc
	garbage_collection();
	exit;

}


/**
 * This will generate security token for GET request, to Prevent CSRF
 *
 * @param string $request Any random unique string
 * @return string Token key
 */
function kleeja_add_form_key_get($request_id)
{
	global $config, $plugin;

	$return = 'formkey=' . substr(sha1($config['h_key'] . date('H-d-m') . $request_id), 0, 20);

	($hook = $plugin->run_hook('kleeja_add_form_key_get_func')) ? eval($hook) : null; //run hook
	return $return;
}


/**
 * This will check the security token of a _get request, to Prevent CSRF
 *
 * @param string $request The token generated by kleeja_add_form_key_get function
 * @return bool
 */
function kleeja_check_form_key_get($request_id)
{
	global $config, $plugin;

	$token = substr(sha1($config['h_key'] . date('H-d-m') . $request_id), 0, 20);

	$return = false;

	if(isset($_GET['formkey']) && $token == $_GET['formkey'])
	{
		$return = true;
	}

	($hook = $plugin->run_hook('kleeja_check_form_key_get_func')) ? eval($hook) : null; //run hook
	return $return;
}

/**
 * This will generate security token for form request, to Prevent CSRF
 *
 * @param string $form_name Any random unique name of the current form
 * @return string Token fileds to be included in the form
 */
function kleeja_add_form_key($form_name)
{
	global $config, $plugin;
	$now = time();
	$return = '<input type="hidden" name="k_form_key" value="' . sha1($config['h_key'] . $form_name . $now) . '" /><input type="hidden" name="k_form_time" value="' . $now . '" />' . "\n";

	($hook = $plugin->run_hook('kleeja_add_form_key_func')) ? eval($hook) : null; //run hook
	return $return;
}

/**
 * This will check the security token of a _post request, to Prevent CSRF
 *
 * @param string $request The unique name of the form given to kleeja_add_form_key function
 * @return bool
 */
function kleeja_check_form_key($form_name, $require_time = 150 /*seconds*/ )
{
	global $config, $plugin;

	if(defined('IN_ADMIN'))
	{
		#we increase it for admin to be a duble
		$require_time *= 2;
	}

	$return = false;
	if (isset($_POST['k_form_key']) && isset($_POST['k_form_time']))
	{
		$key_was = trim($_POST['k_form_key']);
		$time_was = intval($_POST['k_form_time']);
		$different = time() - $time_was;

		#check time that user spent in the form
		if($different && (!$require_time || $require_time >= $different))
		{
			if(sha1($config['h_key'] . $form_name . $time_was) === $key_was)
			{
				$return = true;
			}
		}
	}

	($hook = $plugin->run_hook('kleeja_check_form_key_func')) ? eval($hook) : null; //run hook
	return $return;
}

/**
 * Link generator
 * Files can be many links styles, so this will generate the current style of link.
 *
 * @param string $pid The type of link to return, i.e. thumb or image ...
 * @param array $file_info The file information, like filename, file id
 * @return string The link
 */

function kleeja_get_link($pid, $file_info = array())
{
	global $config, $plugin;

	#to avoid problems, no type specifed so default is id
	$id_form = empty($config['id_form']) ? 'id' : $config['id_form'];
	#type of links
	$link_type = $config['mod_writer'] ? 'html' : 'default';


	#links formats
	$links = array(
		'id' => array(
				'html' => array(
							'thumb' => 'thumb::ID::.html',
							'image' => 'image::ID::.html',
							'del'	=> 'del::CODE::.html',
							'file'	=> 'download::ID::.html',
						),
				'default' => array(
							'thumb' => 'do.php?thmb=::ID::',
							'image' => 'do.php?img=::ID::',
							'del'	=> 'go.php?go=del&amp;cd=::CODE::',
							'file'	=> 'do.php?id=::ID::',
						)
					),

		'filename' => array(

				'html' => array(
							'thumb' => 'thumbf-::NAME::.html',
							'image' => 'imagef-::NAME::.html',
							'del'	=> 'del::CODE::.html',
							'file'	=> 'downloadf-::NAME::.html',
						),
				'default' => array(
							'thumb' => 'do.php?thmbf=::NAME::',
							'image' => 'do.php?imgf=::NAME::',
							'del'	=> 'go.php?go=del&amp;cd=::CODE::',
							'file'	=> 'do.php?filename=::NAME::',
						)
				),

		'direct' => array(
				 'html' => array(
							'thumb' => '::DIR::/thumbs/::NAME::',
							'image' => '::DIR::/::NAME::',
							'file'	=> '::DIR::/::NAME::',
							'del'	=> 'del::CODE::.html',
						),

					'default' => array(
							'del'	=> 'go.php?go=del&amp;cd=::CODE::',
							'thumb' => '::DIR::/thumbs/::NAME::',
							'image' => '::DIR::/::NAME::',
							'file'	=> '::DIR::/::NAME::',
						)
				)
		);

	#file info as params
	$keys2replace = array('name' => '::NAME::', 'real_filename'=>'::FNAME::', 'id'=>'::ID::', 'type'=>'::EXT::', 'folder'=>'::DIR::', 'code_del'=>'::CODE::');

	#add another type of links if you want
	#if $config['id_form']  == 'another things' : do another things ..
	($hook = $plugin->run_hook('kleeja_get_link_d_func')) ? eval($hook) : null; //run hook


	#if the file infos gives as regular params, then change them
	$replace_these = array();
	foreach($keys2replace as $k=>$v)
	{
		if(array_key_exists($k, $file_info))
		{
			$replace_these[$v] = $file_info[$k];
		}
	}

	// print_r($replace_these);

	#Is this file has extension that require to be served as a direct link
	if(!empty($replace_these['::EXT::']) && in_array(strtolower($replace_these['::EXT::']), explode(',', $config['imagefolderexts'])))
	{
		#then change it
		$id_form = 'direct';
	}

	#html urls, use - instead of . in name
	if(!empty($replace_these['::NAME::']) &&  $link_type == 'html' && $id_form != 'direct')
	{
		$replace_these['::NAME::'] = str_replace('.', '-', $replace_these['::NAME::']);
	}

 	#now choose the link
	$link = $links[$id_form][$link_type][$pid];

	// print_r(($file_info));

  	#now subtitue file inforamtion in the link and return it
	$return = $config['siteurl'] . str_replace(array_keys($replace_these), array_values($replace_these), $link);


	($hook = $plugin->run_hook('kleeja_get_link_func_rerun')) ? eval($hook) : null; //run hook

	return $return;
}



/**
 * Gets a style information
 * Extracting the information of a style_name/info.txt and return them
 *
 * @param string $style_name Style name
 * @return array Style information
*/
function kleeja_style_info($style_name)
{
	global $plugin;

	$inf_path = PATH . 'styles/' . $style_name . '/info.txt';

	//is info.txt exists or not
	if(!file_exists($inf_path))
	{
		return false;
	}

	$inf_c = file_get_contents($inf_path);
	//some ppl will edit this file with notepad or even with office word :)
	$inf_c = str_replace(array("\r\n", "\r"), array("\n", "\n"), $inf_c);

	//as lines
	$inf_l = @explode("\n", $inf_c);
	$inf_l = array_map('trim', $inf_l);

	$inf_r = array();
	foreach($inf_l as $m)
	{
		//comments
		if(isset($m[0]) && $m[0] == '#' || trim($m) == '')
		{
			continue;
		}

		$t = array_map('trim', @explode('=', $m, 2));
		# ':' mean ummm, mean something secondary as in sub-array
		if(strpos($t[0], ':') !== false)
		{
			$t_t0 = array_map('trim', @explode(':', $t[0]));
			$inf_r[$t_t0[0]][$t_t0[1]] = $t[1];
		}
		else
		{
			$inf_r[$t[0]] = $t[1];
		}
	}

	($hook = $plugin->run_hook('kleeja_style_info_func')) ? eval($hook) : null; //run hook

	return $inf_r;
}


/**
 * Browser detection
 * returns whether or not the visiting browser is the one specified [part of kleeja style system]
 * i.e. is_browser('ie6') -> true or false
 * i.e. is_browser('ie, opera') -> true or false
 *
 * @param string $b Browser short name like ie, chrome or opera, or both using comma
 * @return bool True if the browser detected, false if not
*/
function is_browser($b)
{
	global $plugin;

	#is there , which mean -OR-
	if(strpos($b, ',') !== false)
	{
		$e = explode(',', $b);
		foreach($e as $n)
		{
			if(is_browser(trim($n)))
			{
				return true;
			}
		}

		return false;
	}

	//if no agent, let's take the worst case
	$u_agent = (!empty($_SERVER['HTTP_USER_AGENT'])) ? htmlspecialchars((string) $_SERVER['HTTP_USER_AGENT']) : (function_exists('getenv') ? getenv('HTTP_USER_AGENT') : '');
	$t = trim(preg_replace('/[^a-z]/', '', $b));
	$r = trim(preg_replace('/[a-z]/', '', $b));

	$return = false;
	switch($t)
	{
		case 'ie':
			$return = strpos(strtolower($u_agent), trim('msie ' . $r)) !== false ? true : false;
		break;
		case 'firefox':
			$return = strpos(str_replace('/', ' ', strtolower($u_agent)), trim('firefox ' . $r)) !== false ? true : false;
		break;
		case 'safari':
			$return = strpos(strtolower($u_agent), trim('safari/' . $r)) !== false ? true : false;
		break;
		case 'chrome':
			$return = strpos(strtolower($u_agent), trim('chrome ' . $r)) !== false ? true : false;
		break;
		case 'flock':
			$return = strpos(strtolower($u_agent), trim('flock ' . $r)) !== false ? true : false;
		break;
		case 'opera':
			$return = strpos(strtolower($u_agent), trim('opera ' . $r)) !== false ? true : false;
		break;
		case 'konqueror':
			$return = strpos(strtolower($u_agent), trim('konqueror/' . $r)) !== false ? true : false;
		break;
		case 'mozilla':
			$return = strpos(strtolower($u_agent), trim('gecko/' . $r)) !== false ? true : false;
		break;
		case 'webkit':
			$return = strpos(strtolower($u_agent), trim('applewebkit/' . $r)) !== false ? true : false;
		break;
		/**
		 * Mobile Phones are so popular those days, so we have to support them ...
		 * This is still in our test lab.
		 * @see http://en.wikipedia.org/wiki/List_of_user_agents_for_mobile_phones
		 **/
		case 'mobile':
			$mobile_agents = array('iPhone;', 'iPod;', 'blackberry', 'Android', 'HTC' , 'IEMobile', 'LG/', 'LG-',
									'LGE-', 'MOT-', 'Nokia', 'SymbianOS', 'nokia_', 'PalmSource', 'webOS', 'SAMSUNG-',
									'SEC-SGHU', 'SonyEricsson', 'BOLT/', 'Mobile Safari', 'Fennec/', 'Opera Mini');
			$return = false;
			foreach($mobile_agents as $agent)
			{
				if(strpos($u_agent, $agent) !== false)
				{
					$return = true;
					break;
				}
			}
		break;
	}

	($hook = $plugin->run_hook('is_browser_func')) ? eval($hook) : null; //run hook
    return $return;
}



/**
 * Create a json code from an array
 * Converting array to JSON format, nested arrays not supported
 *
 * @param array $array Array containing the information that you want to be in json
 * @return string The generated json code
*/
function generate_json($array)
{
	$json = '';
	$json_escape = array(
		array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'),
		array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"')
	);

	foreach($array as $key=>$value)
	{
		$json .= ($json != '' ? ', ' : '') . '"' . $key . '":' .
				(preg_match('^[0-9]+$', $value) ? $v : '"' . str_replace($json_escape[0], $json_escape[1], $value) . '"');
	}

	return '{' . $json . '}';
}


/**
 * Generate a json - ajax response
 * Create an ajax response in a JSON code
 *
 * @param int $code_number Ajax response number
 * @param string $content Content of the ajax response
 * @param string $menu The menu, leave it empty if you don't have it
 * @return string The generated json code
*/
function echo_ajax($code_number, $content, $menu = '')
{
	global $SQL;
	$SQL->close();

	exit(generate_json(array('code' => $code_number, 'content' => $content, 'menu' => $menu)));
}


/**
* show date in a human-readable-text
*/
/**
 * Generate a json - ajax response
 * Create an ajax response in a JSON code
 *
 * @param int $code_number Ajax response number
 * @param string $content Content of the ajax response
 * @param string $menu The menu, leave it empty if you don't have it
 * @return string The generated json code
*/
function kleeja_date($time, $human_time = true, $format = false)
{
	global $lang, $config, $plugin;

	if((time() - $time > (86400 * 9)) || $format || !$human_time)
	{
		$format = !$format ? (isset($config['time_format']) ? $config['time_format'] : 'd-m-Y') : $format;
		$time	= $time + ((int) $config['time_zone']*60*60);
		return str_replace(array('am', 'pm'), array($lang['TIME_AM'], $lang['TIME_PM']), gmdate($format, $time));
	}

	$lengths	= array("60","60","24","7","4.35","12","10");
	$timezone_diff = (int) $config['time_zone']*60*60;
	$now		= time() + $timezone_diff;
	$time		= $time + $timezone_diff;
	$difference	= ($now > $time) ? $now - $time :  $time - $now;
	$tense		= ($now > $time) ? $lang['W_AGO'] : $lang['W_FROM'];
	for($j = 0; $difference >= $lengths[$j] && $j < sizeof($lengths)-1; $j++)
	{
		$difference /= $lengths[$j];
	}
	$difference = round($difference);
	$return = $difference;
	if($difference != 1)
	{
		if($difference == 2)
		{
			$return = $lang['W_PERIODS2'][$j];
		}
		else
		{
			$return = $difference . ' ' . ($difference > 10 ? $lang['W_PERIODS'][$j] :  $lang['W_PERIODS_P'][$j]);
		}
	}
	else
	{
		$return = $lang['W_PERIODS'][$j];
	}

	$return = $lang['W_FROM'] .  ' ' . $return;

	($hook = $plugin->run_hook('kleeja_date_func')) ? eval($hook) : null; //run hook

	return $return;
}


/**
 * World Time Zones
 * Create an ajax response in a JSON code
 *
 * @return array The timezones of the world
*/
function time_zones()
{
	global $plugin;

	$zones = array(
		'Kwajalein' => -12.00,
		'Pacific/Midway' => -11.00,
		'Pacific/Honolulu' => -10.00,
		'America/Anchorage' => -9.00,
		'America/Los_Angeles' => -8.00,
		'America/Denver' => -7.00,
		'America/Tegucigalpa' => -6.00,
		'America/New_York' => -5.00,
		'America/Caracas' => -4.30,
		'America/Halifax' => -4.00,
		'America/St_Johns' => -3.30,
		'America/Argentina/Buenos_Aires' => -3.00,
		'America/Sao_Paulo' => -3.00,
		'Atlantic/South_Georgia' => -2.00,
		'Atlantic/Azores' => -1.00,
		'Europe/Dublin' => 0,
		'Europe/Belgrade' => 1.00,
		'Europe/Minsk' => 2.00,
		'Asia/Riyadh' => 3.00,
		'Asia/Buraydah' => 3.00,
		'Asia/Tehran' => 3.30,
		'Asia/Muscat' => 4.00,
		'Asia/Yekaterinburg' => 5.00,
		'Asia/Kolkata' => 5.30,
		'Asia/Katmandu' => 5.45,
		'Asia/Dhaka' => 6.00,
		'Asia/Rangoon' => 6.30,
		'Asia/Krasnoyarsk' => 7.00,
		'Asia/Brunei' => 8.00,
		'Asia/Seoul' => 9.00,
		'Australia/Darwin' => 9.30,
		'Australia/Canberra' => 10.00,
		'Asia/Magadan' => 11.00,
		'Pacific/Fiji' => 12.00,
		'Pacific/Tongatapu' => 13.00
	);

	($hook = $plugin->run_hook('time_zones_func')) ? eval($hook) : null; //run hook

	return $zones;
}

/**
 * Check if current extension is an image or not
 *
 * @param string $ext The file extension i.e. gif
 * @return bool True if image, False if not
 */
function is_image($ext)
{
	global $plugin;

	$check = in_array(strtolower(trim($ext)), array('gif', 'jpg', 'jpeg', 'bmp', 'png')) ? true : false;

	($hook = $plugin->run_hook('is_image_func')) ? eval($hook) : null; //run hook

	return $check;
}

/**
 * Shorten A string
 *
 * @param string $text The strings to shorten
 * @return string Short string
 */
function shorten_text($text, $until = 30)
{
	global $plugin;

	$until = $until < 4 ? 4 : $until;

	$chars_len = function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);

	if ($chars_len >= $until)
	{
		$return = function_exists('mb_substr') ? (mb_substr($text, 0, $until-4, 'UTF-8'). " ... " . mb_substr($text, -4, null,'UTF-8')) :
				 substr($text, 0, $until-4). " ... " . substr($text, -4);
	}
	else
	{
		$return = $text;
	}

	($hook = $plugin->run_hook('shorten_text_func')) ? eval($hook) : null; //run hook

	return $return;
}


/**
 * Get group name by its ID
 *
 * @param int $gid The group id
 * @param bool $group_name if this is true, then $gid is the group name, not id
 * @return string Group name
 */
function get_group_name($gid, $group_name = false)
{
	global $d_groups, $lang;

	return preg_replace_callback(
							'!{lang.([A-Z0-9]+)}!',
							function($matches){
								 	global $lang;
									return $lang[$matches[1]];
								},
							$group_name ? $gid : (!empty($d_groups[$gid]['data']['group_name']) ? $d_groups[$gid]['data']['group_name'] : '')
						);
}
