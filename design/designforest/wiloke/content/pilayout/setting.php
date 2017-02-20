<?php
/**
 * Content layout
 * Created by ninhle - wiloke team
 * @since 1.0
 */

add_action('pi_hook_before_post', 'pi_add_content_layout_settings', 10, 3);
function pi_add_content_layout_settings($wp_customize, $piSectionPriority, $piControlPriority)
{
	$wp_customize->add_section(
		'pi_content_layout',
		array(
			'title' 	 => __('Homepage', 'wiloke'),
			'priority'	 => $piSectionPriority++,
			'panel'		 => 'pi_content_panel'
		)
	);

	$wp_customize->add_setting(
        'pi_options[content][layout]',
        array(
            'default'           => 'standard',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'pi_sanitize_data'
        )
    );

    $wp_customize->add_control(
        'pi_options[content][layout]',
        array(
            'label'     => 'Layout',
            'type'      => 'select',
            'priority'  => $piControlPriority,
            'section'   => 'pi_content_layout',
            'settings'  => 'pi_options[content][layout]',
            'choices'   => array(
                'standard'              => __('Standard', 'wiloke'),
                'grid' 	                => __('Grid', 'wiloke'),
                'grid_first-large' 	    => __('Grid - 1st large', 'wiloke'),
                'list'                  => __('List', 'wiloke'),
                'list_first-large'      => __('List - 1st large', 'wiloke')
            )
        )
    );

    $aCategories = get_categories();
    $aCats = array();
    if ( !empty($aCategories) )
    {
        foreach ( $aCategories as $term )
        {
            $aCats[$term->term_id] = $term->name; 
        }
    }


    $wp_customize->add_setting(
        "pi_options[content][page_template][categories][]",
        array(
            'default'               => '',
            'type'                  => 'option',
            'capability'            => 'edit_theme_options',
            'sanitize_callback'     => 'pi_sanitize_data'
        )
    );

    $wp_customize->add_control( new piMultipleSelect(
            $wp_customize,
            "pi_options[content][page_template][categories][]",
            array(
               "priority"   => $piControlPriority++,
               "type"       => "pi_multiple_select",
               "label"      => __( 'Get posts from: ', 'wiloke' ),
               "section"    => "pi_content_layout",
               "options"    => $aCats,
               "settings"   => "pi_options[content][page_template][categories][]"
            )
        )
    );

    $wp_customize->add_setting(
        "pi_options[content][page_template_des]",
        array(
            'default'               => '',
            'type'                  => 'option',
            'capability'            => 'edit_theme_options',
            'sanitize_callback'     => 'pi_sanitize_data'
        )
    );

    $wp_customize->add_control( new piDescription(
            $wp_customize,
            "pi_options[content][page_template_des]",
            array(
               "priority"   => $piControlPriority++,
               "label"      => __( 'Note that: This setting is only available with the <a target="_blank" href="https://nimbus.everhelper.me/client/notes/share/276720/28qkQKaJthw7xT2GeEq2HE0f0bVheicK/">Page Template</a>', 'wiloke' ),
               "section"    => "pi_content_layout",
               "settings"   => "pi_options[content][page_template_des]",
            )
        )
    );

}

?>