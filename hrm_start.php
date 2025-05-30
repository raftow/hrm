<?php

$file_dir_name = dirname(__FILE__);


set_time_limit(8400);
ini_set('error_reporting', E_ERROR | E_PARSE | E_RECOVERABLE_ERROR | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR);

require_once("$file_dir_name/../lib/afw/afw_autoloader.php");

require_once("$file_dir_name/../config/global_config.php");



AfwAutoLoader::addMainModule("hrm");

include_once ("$file_dir_name/../hrm/ini.php");
include_once ("$file_dir_name/../hrm/module_config.php");
include_once ("$file_dir_name/../hrm/application_config.php");
AfwSession::initConfig($config_arr, "system", "$file_dir_name/../hrm/application_config.php");

AfwSession::startSession();