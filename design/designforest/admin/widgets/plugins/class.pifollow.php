<?php
/**
 * Follow Us
 * Created by ninhle - wiloke team
 */
class piFollow extends WP_Widget
{
    public $aDef = array('title'=>'', 'description'=>'');
    public function __construct()
    {
        parent::__construct('pi_follow', PI_THEMENAME . 'Follow', array('classname'=>'pi_follow'));
    }

    public function form($aInstance)
    {
        $this->aDef = array_merge($this->aDef, piConfigs::$aConfigs['pi_options']['follow']);
        $aInstance  = wp_parse_args($aInstance, $this->aDef);
        piWidgets::pi_text_field( 'Title', $this->get_field_id('title'), $this->get_field_name('title'), $aInstance['title']);
        piWidgets::pi_textarea_field( 'Description', $this->get_field_id('description'), $this->get_field_name('description'), $aInstance['description']);
        foreach ( piConfigs::$aConfigs['configs']['rest']['follow'] as $key => $aInfo )
        {
            piWidgets::pi_text_field( $aInfo[1], $this->get_field_id($key), $this->get_field_name($key), $aInstance[$key], '', true);
        }
    }

    public function update($aNewinstance, $aOldinstance)
    {
        $aInstance = $aOldinstance;
        foreach ( $aNewinstance as $key => $val )
        {
            if ( $key == 'title' || $key == 'description' )
            {
                $aInstance[$key] = strip_tags($val);
            }else{
                $aInstance[$key] = esc_url($val);
            }
        }

        return $aInstance;
    }

    public function widget($atts, $aInstance)
    {
        $this->aDef = array_merge($this->aDef, piConfigs::$aConfigs['pi_options']['follow']);
        $aInstance  = wp_parse_args($aInstance, $this->aDef);

        print $atts['before_widget'];
            if ( !empty($aInstance['title']) )
            {
                print $atts['before_title'] . esc_html($aInstance['title']) . $atts['after_title'];
            }

            if ( !empty($aInstance['description']) )
            {
                echo '<div class="text-italic">';
                    print '<p>'.wp_unslash($aInstance['description']).'</p>';
                echo '</div>';
            }
            echo '<div class="pi-social-square">';
            foreach ( piConfigs::$aConfigs['configs']['rest']['follow'] as $key => $aInfo )
            {
                if ( !empty($aInstance[$key]) )
                {
                    echo '<a target="_blank" href="'.esc_url($aInstance[$key]).'"><i class="'.esc_attr($aInfo[0]).'"></i></a>';
                }
            }
            echo '</div>';
        print $atts['after_widget'];
    }
}
?>