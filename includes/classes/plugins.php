<?php
/**
*
* @package Kleeja
* @version $Id: plugins.php 2220 2013-11-12 00:36:17Z saanina $
* @copyright (c) 2007 Kleeja.com
* @license http://www.kleeja.com/license
*
*/
//no for directly open
if (!defined('IN_COMMON'))
{
	exit();
}


# We are in the plugin system, plugins files won't work outside here
define('IN_PLUGINS_SYSTEM', true);


/**
* Kleeja Plugins System
* @package plugins
*/
class plugins
{
	/**
	 * List of loaded plugins
	 */
	private $plugins = array();

	/**
	 * All hooks from all plugins listed in this variable
	 */
	private $all_plugins_hooks = array();


	private $plugin_path = '';

	/**
	 * Initating the class
	 */
	public function __construct()
	{
		#if plugins system is turned off, then stop right now!
		if(defined('STOP_PLUGINS'))
		{
			return false;
		}

		$this->plugin_path = PATH . 'plugins';

		$this->load_plugins();
	}

	/**
	 * Load the plugins from root/plugins folder
	 */
	private function load_plugins()
	{
		$dh  = opendir($this->plugin_path);
		$i = 0;
		while (false !== ($filename = readdir($dh)))
		{
			if(strpos($filename, '.php') !== false)
			{
		    	$this->plugins[$i] = str_replace('.php', '', $filename);
				$this->fetch_plugin($this->plugins[$i]);
				$i++;
			}
		}

		#sort the plugins from high to low priority
		krsort($this->plugins);


		//print_r($this->plugins);
	}


	/**
	 * Get the plugin information and other things
	 */
	private function fetch_plugin($plugin_name)
	{
		#load the plugin
		include $this->plugin_path . '/' . $plugin_name . '.php';
		$priority = $kleeja_plugin[$plugin_name]['information']['plugin_priority'];

		#bring the real priority of plugin and replace current one
		$plugin_current_priority = array_search($plugin_name, $this->plugins);
		unset($this->plugins[$plugin_current_priority]);
		$this->plugins[$priority] = $plugin_name;

		#get the information...

		#add plugin hooks to global hooks, depend on its priority
		if(!empty($kleeja_plugin[$plugin_name]['hooks'])){
			foreach($kleeja_plugin[$plugin_name]['hooks'] as $hook_name=>$hook_value){
				if(empty($this->all_plugins_hooks[$hook_name][$priority])){
					$this->all_plugins_hooks[$hook_name][$priority] = array();
				}
				array_push($this->all_plugins_hooks[$hook_name][$priority], $hook_value);
				krsort($this->all_plugins_hooks[$hook_name]);
			}
		}


		// print_r($this->plugins);
		// print_r($this->all_plugins_hooks);

	}

	/**
	 * Check if this is the first run of a plugin
	 */
	private function check_first_run($plugin_name)
	{
		global $SQL;


	}

	/**
	 * Delete a plugin
	 */
	public function unistall_plugin($plugin_name)
	{

	}


	/**
	 * Insert plugin hooks into
	 */
	private function include_plugin_hooks_into_kleeja($plugin_name)
	{

	}

	/**
	 * Bring all codes of this hook
	 * This function scattered all over kleeja files
	 */
	public function run_hook($hook_name)
	{
		// $this->all_plugins_hooks[$hook_name] = array(
		// 		10 => array('print "hi10"; print "<br>";', 'print "hi10-2"; print "<br>";','print "hi10-3"; print "<br>";') ,
		// 		30 => array('print "hi30"; print "<br>";'),
		// 		-1 => array('print "hi30"; print "<br>";'),
		// );
		// krsort($this->all_plugins_hooks[$hook_name]);

		// print_r(array_values($this->all_plugins_hooks[$hook_name]));

		if(!empty($this->all_plugins_hooks[$hook_name]))
		{
			$this->all_plugins_hooks[$hook_name] = array_map(function($x){ return implode("\n", $x); }, $this->all_plugins_hooks[$hook_name]);

			// print_r($this->all_plugins_hooks[$hook_name]);
			return implode("\n", array_values($this->all_plugins_hooks[$hook_name]));
		}
	}
}
