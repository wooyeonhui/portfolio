<?php
/*Template Name: Page Template */
$aTemplate = piBlogFramework::pi_get_page_template_slug($post->ID);
$optionKey = piBlogFramework::pi_parse_option_key($aTemplate);
piBlogFramework::$piOptions = get_option($optionKey);
if ( empty(piBlogFramework::$piOptions) )
{
    piBlogFramework::$piOptions = piConfigs::$aConfigs['pi_options'];
}else{
    piBlogFramework::$piOptions = array_replace_recursive(piConfigs::$aConfigs['pi_options'], piBlogFramework::$piOptions);
}

get_template_part("index");
