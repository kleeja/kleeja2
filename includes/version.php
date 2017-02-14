<?php
/**
*
* @package Kleeja
* @version $Id: version.php 2219 2013-11-10 21:44:11Z saanina $
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



$dev_m = '';
if(defined('DEV_STAGE'))
{
	$dev_m = '-dev';
}

/**
 * Kleeja current version
 */
define('KLEEJA_VERSION' , '2.0.0 alpha 1' . $dev_m);


/**
 * Kleeja Database current version
 */
define('KLEEJA_DB_VERSION' , '100');
