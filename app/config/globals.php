<?php
defined('BASEDIR') or define('BASEDIR', __DIR__);
defined('BASEURL') or define('BASEURL', 'http'.(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 's' : '').'://'.DOMAIN);
defined('APP_FOLDER') or define('APP_FOLDER', 'app');
defined('CORE_FOLDER') or define('CORE_FOLDER', APP_FOLDER.DIRECTORY_SEPARATOR.'core');
defined('CONFIG_FOLDER') or define('CONFIG_FOLDER', APP_FOLDER.DIRECTORY_SEPARATOR.'config');
defined('CONTROLLERS_FOLDER') or define('CONTROLLERS_FOLDER', APP_FOLDER.DIRECTORY_SEPARATOR.'controllers');
defined('MODELS_FOLDER') or define('MODELS_FOLDER', APP_FOLDER.DIRECTORY_SEPARATOR.'models');
defined('LIBRARIES_FOLDER') or define('LIBRARIES_FOLDER', APP_FOLDER.DIRECTORY_SEPARATOR.'libraries');
defined('VIEWS_FOLDER') or define('VIEWS_FOLDER', APP_FOLDER.DIRECTORY_SEPARATOR.'views');
?>
