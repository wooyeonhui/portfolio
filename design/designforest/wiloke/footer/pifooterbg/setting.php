<?php
/**
 * Created by ninhle - wiloke team.
 * Date: 7/15/15
 * Time: 11:44 PM
 */

add_action('pi_hook_before_footerjs', 'pi_add_footer_bg', 10, 3);
function pi_add_footer_bg($wp_customize, $sectionPrior, $controlPrior)
{
    $wp_customize->add_section(
        'pi_footer_bg',
        array(
            'title'     => __('Footer Background', 'wiloke'),
            'panel'     => 'pi_footer_panel',
            'priority'  => $sectionPrior
        )
    );

    $wp_customize->add_setting(
        'pi_options[footer][footerbg]',
        array(
            'default'           =>  '',
            'type'              => 'option',
            'capability'        => 'edit_theme_options',
            'sanitize_callback' => 'pi_sanitize_data'
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'pi_options[footer][footerbg]',
            array(
                'label'      => __( 'Upload', 'wiloke' ),
                'section'    => 'pi_footer_bg',
                'settings'   => 'pi_options[footer][footerbg]',
            )
        )
    );
}