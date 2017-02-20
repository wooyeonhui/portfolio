<?php
/**
 * Hook into before  panels
 */
do_action('pi_hook_before_panels', $wp_customize, array('panel_order'=>$this->piPanelPriority, 'section_order'=>$this->piSectionPriority, 'control_order'=>$this->piControlPriority));


do_action('pi_hook_before_panels', $wp_customize, $this->piPanelPriority);

/*Basic Settings*/
$wp_customize->add_panel( 'pi_basic_settings_panel', array(
    'priority'		 => $this->piPanelPriority++,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __('Basic Settings', 'wiloke')
) );
/*End/Basic Settings*/

/*Typography*/
$wp_customize->add_panel( 'pi_typography_panel', array(
    'priority'		 => $this->piPanelPriority++,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __('Typography', 'wiloke')
) );
/*End/Typography*/

/*Logo & Header*/
$wp_customize->add_panel( 'pi_logoheader_panel', array(
    'priority'		 => $this->piPanelPriority++,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __('Logo &amp; Header', 'wiloke')
) );
/*End/Logo & Header*/

/*Featured Posts*/
$wp_customize->add_panel( 'pi_featured_posts_panel', array(
    'priority'		 => $this->piPanelPriority++,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __('Featured Slider', 'wiloke')
) );
/*End/Featured Posts*/

/*Content*/
do_action('pi_hook_before_content_panel', $wp_customize, $this->piPanelPriority);
$wp_customize->add_panel( 'pi_content_panel', array(
    'priority'		 => $this->piPanelPriority++,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __('Content', 'wiloke')
) );
do_action('pi_hook_after_content_panel', $wp_customize, $this->piPanelPriority);
/*End/Content*/

/*Archive*/
$wp_customize->add_panel( 'pi_archive_panel', array(
    'priority'		 => $this->piPanelPriority++,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __('Archive', 'wiloke')
) );
/*End/Archive*/

/*Footer*/
$wp_customize->add_panel( 'pi_footer_panel', array(
    'priority'		 => $this->piPanelPriority++,
    'capability'     => 'edit_theme_options',
    'theme_supports' => '',
    'title'          => __('Footer', 'wiloke')
) );
/*End/Footer*/

do_action('pi_hook_after_panels', $wp_customize, $this->piPanelPriority);

/**
 * Hook into after panels
 */
do_action('pi_hook_after_panels', $wp_customize, array('panel_order'=>$this->piPanelPriority, 'section_order'=>$this->piSectionPriority, 'control_order'=>$this->piControlPriority));

?>