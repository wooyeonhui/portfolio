<?php

class piAds extends WP_Widget
{
    public $aDef  =  array("title"=>"Banner", "description"=>"", "image"=>"", "link"=>"");
    public function __construct()
    {
        $args = array('classname'=>'pi_banner',  'description'=>__('Banner', 'wiloke'));
        parent::__construct("pi_banner", __( PI_THEMENAME . 'Banner', 'wiloke' ),  $args);
    }

    /**
    * Outputs the content of the widget
    *
    * @param array $args
    * @param array $instance
    */
    public function widget( $atts, $aInstance )
    {
        $aInstance = wp_parse_args($aInstance, $this->aDef);

        print $atts['before_widget'];
            if( isset($aInstance['title']) )
            {
                print $atts['before_title'] . esc_html($aInstance['title']) . $atts['after_title'];
            }
            echo '<a href="'.esc_url($aInstance['link']).'" target="_blank"><img src="'.esc_url($aInstance['image']).'" alt="'.esc_attr($aInstance['title']).'"></a>';
        print $atts['after_widget'];
    }


    /**
    * Outputs the options form on admin
    *
    * @param array $instance The widget options
    */
    public function form( $aInstance )
    {
        $aInstance = wp_parse_args($aInstance, $this->aDef);

        piWidgets::pi_text_field('Title', $this->get_field_id('title'), $this->get_field_name('title'), $aInstance['title']);
        piWidgets::pi_upload_field('Image', $this->get_field_id('image'), $this->get_field_id('upload_button'), $this->get_field_name('image'), false, $aInstance['image']);
        piWidgets::pi_link_field('Link', $this->get_field_id('link'), $this->get_field_name('link'), $aInstance['link']);
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
    public function update( $aNewInstance, $aOldInstance )
    {
        $aInstance = $aOldInstance;

        foreach ( $aNewInstance as $key => $val )
        {
            if ( $key =='image' || $key == 'link' )
            {
                $aInstance[$key] = esc_url($val);
            }else{
                $aInstance[$key] = strip_tags($val);
            }
        }

        return $aInstance;
    }
}