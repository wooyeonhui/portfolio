<?php
/**
 * Created by ninhle - wiloke team
 * @since 1.0
 */
function pi_get_footerbg()
{
    return piBlogCustomize::pi_refresh_in_customize('pi_options[footer][footerbg]') ? piBlogCustomize::pi_refresh_in_customize('pi_options[footer][footerbg]') : piBlogFramework::$piOptions['footer']['footerbg'];
}
?>