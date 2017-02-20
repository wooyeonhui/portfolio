<?php
/**
 * Widgets Bootstrap
 */

if ( !defined('ABSPATH') )
{
    die();
}

define('PI_WIDGETS_PATH', PI_A_PATH . 'widgets/');

require_once PI_WIDGETS_PATH . "class.piwidgets.php";
foreach ( piConfigs::$aConfigs['configs']['widget']['items'] as $className )
{
    include PI_WIDGETS_PATH . 'plugins/class.'.strtolower($className).'.php';
}

?>