<?php
/**
 * Created by ninhle - wiloke team
 */
class piMailchimp extends WP_Widget
{
    public $aDef  = array('title' => 'Mailchimp', 'description'=>'', 'list_id'=>'');
    public function __construct()
    {
        $args = array('classname'=>'pi_mailchimp widget_newsletter', 'description'=>'');
        parent::__construct('pi_mailchimp', PI_THEMENAME.'Mailchimp', $args);
    }

    public function form($aInstance)
    {
        $aInstance = wp_parse_args($aInstance, $this->aDef);

        piWidgets::pi_text_field( 'Title', $this->get_field_id('title'), $this->get_field_name('title'), $aInstance['title']);
        piWidgets::pi_textarea_field( 'Description', $this->get_field_id('description'), $this->get_field_name('description'), $aInstance['description']);

        if ( !get_option('pi_mailchimp_api_key') )
        {
            echo '<p><code>'.sprintf( (__('You haven\'t configured your mailchimp yet. Please go <a href="%s" target="_blank">here</a> to do it.', 'wiloke')), admin_url('themes.php?page=pi-config-mailchimp') ).'</code></p>';
        }
    }

    public function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        foreach ($new_instance as $key => $value)
        {
            $instance[$key] = strip_tags($value);
        }


        update_option("pi_mailchimp_api_key", $instance['api_key']);
        update_option("pi_mailchimp_listid", $instance['list_id']);

        return $instance;
    }

    public function widget($atts, $aInstance)
    {
        $aInstance = wp_parse_args($aInstance, $this->aDef);

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

            if ( !get_option('pi_mailchimp_api_key') )
            {
                if ( is_user_logged_in() && current_user_can('edit_theme_options') )
                {
                    printf( (__('<p>Please <a href="%s" target="_blank" style="color:#ff0771">config</a> your mailchimp</p>', 'wiloke')), admin_url('themes.php?page=pi-config-mailchimp') );
                }
            }else{
            ?>
            <form class="pi_subscribe">
                <div class="form-item form-remove">
                    <input type="text" class="pi-subscribe-email" value="<?php _e('Your mail...', 'wiloke'); ?>"/>
                </div>
                <div class="form-actions form-remove form-submit">
                    <button type="submit" class="pi-btn pi-subscribe"><?php _e('Subscribe', 'wiloke'); ?></button>
                </div>
                <p class="subscribe-status alert-done" style="display: none"><?php _e('Submit success - Bigs thank for you','wiloke');?></p>
                <?php $ajax_nonce = wp_create_nonce( "pi_subscribe_nonce" ); ?>
            </form>
            <script type="text/javascript">
                jQuery("button.pi-subscribe").click(function()
                {
                    var $self = jQuery(this);
                    if ( jQuery("input.pi-subscribe-email").val() != '' )
                    {
                        $self.html('<i class="fa fa-cloud-upload"></i>');
                        jQuery.post(
                            PI_OB.ajaxurl,
                            {
                                action : 'pi_subscribe',
                                // send the nonce along with the request
                                subscribeNonce : '<?php echo esc_js($ajax_nonce); ?>',
                                email: jQuery("input.pi-subscribe-email").val()
                            },
                            function( response ) {
                                var data = JSON.parse(response);
                                if(data.type=='error')
                                {
                                    jQuery(".subscribe-status").html(data.msg).addClass("alert-error").removeClass("alert-done").fadeIn();
                                    $self.html('Subscribe');
                                }
                                else{
                                    jQuery(".subscribe-status").html(data.msg).addClass("alert-done").removeClass("alert-error").fadeIn();
                                    jQuery("form.pi_subscribe").find(".form-remove").remove();
                                }
                            }
                        );
                    }else{
                        jQuery(".subscribe-status").html("Please enter your e-mail").addClass("alert-error").removeClass("alert-done").fadeIn();
                    }
                    return false;
                })
            </script>
            <?php
            }
        print $atts['after_widget'];
    }
}

?>