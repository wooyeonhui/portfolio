<?php
/**
 * Created by ninhle - wiloke team
 * User: wiloke
 * Date: 7/16/15
 * Time: 9:21 PM
 */

add_action('pi_hook_before_copyright', 'pi_add_sidebar_section', 10, 3);
function pi_add_sidebar_section($wp_customize, $piSectionPriority, $piContentPriority)
{
    $wp_customize->add_section(
        'pi_sidebar_section',
        array(
            'title'     => __('Sidebar', 'wiloke'),
            'priority'  => 6
        )
    );

    $wp_customize->add_setting(
        'pi_options[sidebar_layout]',
        array(
            'label'             => __('Layout', 'wiloke'),
            'type'              => 'option',
            'default'           => 'sidebar-right',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'pi_sanitize_data'
        )
    );

    $wp_customize->add_control(
        'pi_option[sidebar_layout]',
        array(
            'type'          => 'select',
            'priority'      => $piContentPriority,
            'section'       => 'pi_sidebar_section',
            'settings'      => 'pi_options[sidebar_layout]',
            'choices'       => array(
                'sidebar-right'  => __('Right', 'wiloke'),
                'left-sidebar'   => __('Left', 'wiloke'),
                'no-sidebar'     => __('No', 'wiloke')
            )
        )
    );
}