<?php
/**
 * Created by ninhle - wiloke team
 * @since 1.0
 */
function pi_get_content_layout()
{
    return piBlogCustomize::pi_refresh_in_customize('pi_options[content][layout]') ? piBlogCustomize::pi_refresh_in_customize('pi_options[content][layout]') : piBlogFramework::$piOptions['content']['layout'];
}

function pi_get_categories()
{
    return piBlogCustomize::pi_refresh_in_customize('pi_options[content][page_template][categories][]') ? piBlogCustomize::pi_refresh_in_customize('pi_options[content][page_template][categories][]') : piBlogFramework::$piOptions['content']['page_template']['categories'][''];
}
?>