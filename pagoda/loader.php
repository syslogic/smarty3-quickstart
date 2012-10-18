<?php
/*
	SmartyPHP Bootstrap v3.49 for PagodaBox
	Copyright 2012 by Martin Zeitler
	http://www.codefx.biz
*/

/* setup the environment */
$protocol = 'http'.((isset($_SERVER['HTTPS']))?'s':'');
$client_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
$hostname = $_SERVER['SERVER_NAME'];
$base_url = $protocol.'://'.$hostname;
$base_dir = str_replace('smarty','',dirname(__FILE__));
ini_set('session.cookie_domain', '.'.$_SERVER['SERVER_NAME']);
set_include_path(get_include_path().PATH_SEPARATOR.$base_dir.'includes');


/* application configuration */
$app_config = array(
	'base_dir' => $base_dir,
	'hostname' => $hostname,
	'protocol' => $protocol,
	'base_url' => $base_url,
	'db_debug' => true,
	'db_charset' => 'utf8',
	'smarty_caching' => false,
	'smarty_caching_type' => 'memcache',
	'smarty_cache_lifetime' => 120,
	'smarty_force_compile' => false,
	'smarty_debugging' => false
);

/* database credentials */
$dsn = array(
	'phptype'  => 'mysqli',
	'username' => $_SERVER['DB1_USER'],
	'password' => $_SERVER['DB1_PASS'],
	'hostspec' => $_SERVER['DB1_HOST'],
	'database' => $_SERVER['DB1_NAME']
);

/* database abstraction */
require('MDB2.php');
$db = MDB2::factory($dsn);
$db->setCharset($app_config['db_charset']);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);
$db->setOption('debug', $app_config['db_debug']);
$db->loadModule('Extended');
$db->setOption('persistent', true);
$db->opened_persistent = true;
if(is_a($db,'PEAR_Error')){
	die($db->getMessage().', '.$db->getUserinfo());
}

/* template engine */
require($base_dir.'libs/Smarty.class.php');
$smarty = new Smarty();
$smarty->setTemplateDir($app_config['base_dir'].'smarty/templates');
$smarty->setCompileDir($app_config['base_dir'].'smarty/templates_c');
$smarty->setCacheDir($app_config['base_dir'].'smarty/cache');
$smarty->setConfigDir($app_config['base_dir'].'smarty/configs');
$smarty->force_compile = $app_config['smarty_force_compile'];
$smarty->debugging = $app_config['smarty_debugging'];
$smarty->cache_lifetime = $app_config['smarty_cache_lifetime'];
$smarty->caching = $app_config['smarty_caching'];
$smarty->caching_type = $app_config['smarty_caching_type'];
?>