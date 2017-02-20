<?php
/**
 * Search
 * Created by ninhle - wiloke team
 * @since 1.0
 */

add_action('pi_hook_before_logo', 'pi_add_search_section_to_logoheader_panel', 10, 3);
function pi_add_search_section_to_logoheader_panel($wp_customize, $piSectionPriority, $piControlPriority)
{
	$wp_customize->add_section(
		'pi_logoheader_search',
		array(
			'title' 	 => __('Search', 'wiloke'),
			'priority'	 => $piSectionPriority,
			'panel'		 => 'pi_logoheader_panel',
            'description'=> __('Displaying the search form at the top menu or no', 'wiloke'),
		)
	);

	$wp_customize->add_setting(
        'pi_options[logoheader][search]',
        array(
            'default'           =>  1,
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'pi_sanitize_data'
        )
    );

    $wp_customize->add_control(
        'pi_options[logoheader][search]',
        array(
            'label'     => __('Enable/Disable', 'wiloke'),
            'type'      => 'select',
            'priority'  => $piControlPriority,
            'section'   => 'pi_logoheader_search',
            'settings'  => 'pi_options[logoheader][search]',
            'choices'   => array(
                1 => __('Enable', 'wiloke'),
                0 => __('Disable', 'wiloke')
            )
        )
    );
}