<?php
# Kleeja Plugin
# print_hi_in_all_pages
# Version: 1.0
# Developer: Kleeja team

# Prevent illegal run
if (!defined('IN_PLUGINS_SYSTEM'))
{
	exit();
}


# Plugin Basic Information
$kleeja_plugin['print_hi_in_all_pages']['information'] = array(
	# The casucal name of this plugin, anything can a human being understands
	'plugin_title' => 'Print Hi in all pages',
	# Who wrote this plugin?
	'plugin_developer' => 'Kleeja team, kleeja.com',
	# This plugin version
	'plugin_version' => '1.0',
	# Explain what is this plugin, why should I use it?
	'plugin_description' => 'This is a demo plugin to show you how to use the plugin system, it will print Hi in all Kleeja pages.',
	# Min version of Kleeja that's requiered to run this plugin
	'plugin_kleeja_version_min' => '2.0',
	# Max version of Kleeja that's requiered to run this plugin
	'plugin_kleeja_version_max' => '2.0',
	# Should this plugin run before others?, 0 is normal, and higher number has high priority
	'plugin_priority' => 10
);


# Plugin Options, to be used in $config['....']
$kleeja_plugin['print_hi_in_all_pages']['first_run']['options'] = array(

);

# Plugin Installation Queries, alter the database, add table or a new column
$kleeja_plugin['print_hi_in_all_pages']['first_run']['database'] = array(
	'SQL query is here!',
	'Another SQL query is here'
);

# Plugin Uninstallation, queries only, options to be deleted automatically
$kleeja_plugin['print_hi_in_all_pages']['uninstall'] = array(
	'SQL query is here!',
	'Another SQL query is here'
);



# Plugin Hooks
$kleeja_plugin['print_hi_in_all_pages']['hooks'] = array(
	// 'end_common' => 'print "hi";'
);
