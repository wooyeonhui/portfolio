<?php
/**
 * Created by ninhle - wiloke team
 * @since 1.0
 */
function pi_get_sidebar_layout()
{
    return piBlogCustomize::pi_refresh_in_customize('pi_options[sidebar_layout]') ? piBlogCustomize::pi_refresh_in_customize('pi_options[sidebar_layout]') : piBlogFramework::$piOptions['sidebar_layout'];
}
?>