<?php
/**
 * The views of customize
 * @since 1.0
 */
foreach ( piConfigs::$aConfigs['configs']['settings_views'] as $folder => $aControls )
{
    foreach ( $aControls as $file )
    {
        include PI_BFP_PATH . 'views/'.$folder.'/'.$file.'.php';
    }
}
?>