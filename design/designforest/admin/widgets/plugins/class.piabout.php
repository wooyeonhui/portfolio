<?php
/**
 * Customize about me
 * @since 1.0
 * created by ninhle - wiloke team
 */

class piAbout extends WP_Widget
{
    public $aDef = array('title'=>'About me', 'image'=>'', 'description'=>'', 'name'=>'Richars Winters');
    public function __construct()
    {
        parent::__construct('pi_about', PI_THEMENAME . 'About', array('class'=>'pi_about'));
    }

    public function form($aInstance)
    {
        $aInstance = wp_parse_args($aInstance, $this->aDef);

        piWidgets::pi_text_field('Title', $this->get_field_id('title'), $this->get_field_name('title'), $aInstance['title']);
        piWidgets::pi_upload_field('Image', $this->get_field_id('image'), $this->get_field_id('upload_button'), $this->get_field_name('image'), false, $aInstance['image']);
        piWidgets::pi_text_field('Name', $this->get_field_id('name'), $this->get_field_name('name'), $aInstance['name']);
        piWidgets::pi_textarea_field('Description', $this->get_field_id('description'), $this->get_field_name('description'), $aInstance['description'], 'Allows the html tags: &lt;strong>, &lt;i>, &lt;a>');
    }

    public function update($aNewinstance, $aOldinstance)
    {
        $aInstance = $aOldinstance;
        foreach ( $aNewinstance as $key => $val )
        {
            if ( $key == 'description' )
            {
                $aInstance[$key] = strip_tags($val, '<a><strong><i>');
            }else{
                $aInstance[$key] = strip_tags($val);
            }
        }
        return $aInstance;
    }

    public function widget($atts, $aInstance)
    {
        $aInstance = wp_parse_args($aInstance, $this->aDef);

        print $atts['before_widget'];
            if ( !empty($aInstance['title']) )
            {
                print $atts['before_title'] . esc_html($aInstance['title']) . $atts['after_title'];
            }
            ?>
                <div class="widget-about-content">
                    <?php if ( !empty($aInstance['image']) ) : ?>
                    <div class="images">
                        <img src="<?php echo esc_url($aInstance['image']); ?>" alt="<?php echo esc_attr($aInstance['name']); ?>">
                    </div>
                    <?php endif; ?>
                    <h4 class="text-uppercase fs-14"><?php echo esc_attr($aInstance['name']); ?></h4>
                    <?php if ( !empty($aInstance['description']) ) : ?>
                    <p><?php print wp_kses( wp_unslash($aInstance['description']), array( 'a'=>array('href'=>array(), 'title'=>array()), 'strong'=>array(''), 'i'=>array() ) ); ?></p>
                    <?php endif; ?>
                </div>
            <?php
        print $atts['after_widget'];
    }
}

?>