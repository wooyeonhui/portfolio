<?php
/**
 * Instagram Feed
 * Created by ninhle - wiloke team
 */

class piInstagram extends WP_Widget
{
    public $aDef = array( 'title' =>'Instagram', 'user_id'=>'', 'number_of_photos' => 6, 'access_token' => '', 'cache_interval'=>86400);
    public function __construct()
    {
        $args = array('classname'=>'pi_instagram', 'description'=>'');
        parent::__construct("pi_instagram", PI_THEMENAME . 'Instagram Feed ', $args);
    }

    public function form($aInstance)
    {
        $aInstance = wp_parse_args( $aInstance, $this->aDef );

        piWidgets::pi_text_field( 'Title', $this->get_field_id('title'), $this->get_field_name('title'), $aInstance['title']);
        piWidgets::pi_text_field( 'User Id', $this->get_field_id('user_id'), $this->get_field_name('user_id'), $aInstance['user_id']);
        piWidgets::pi_text_field( 'Access Token', $this->get_field_id('access_token'), $this->get_field_name('access_token'), $aInstance['access_token']);
        echo '<p>';
            echo '<code><a target="_blank" href="http://blog.wiloke.com/find-instagram-user-id-access-token/">Find My instagram access token</a></code>';
        echo '</p>';
        piWidgets::pi_text_field( 'Number Of Photos', $this->get_field_id('number_of_photos'), $this->get_field_name('number_of_photos'), $aInstance['number_of_photos']);
        piWidgets::pi_text_field( 'Cache Interval', $this->get_field_id('cache_interval'), $this->get_field_name('cache_interval'), $aInstance['cache_interval']);
    }

    public function update($aNewinstance, $aOldinstance)
    {
        $aInstance = $aOldinstance;
        foreach ( $aNewinstance as $key => $val )
        {
            if ( $key == 'number_of_photos' )
            {
                $aInstance[$key] = (int)$val;
            }else{
                $aInstance[$key] = strip_tags($val);
            }
        }

        return $aInstance;
    }

    public function widget( $atts, $aInstance )
    {
        $aInstance    = wp_parse_args($aInstance, $this->aDef);

        print $atts['before_widget'];

        if ( !empty($aInstance['title']) )
        {
            print $atts['before_title'] . esc_html($aInstance['title']) . $atts['after_title'];
        }
        ?>
        <div class='pi-instagram-feed widget-grid'>
            <?php
            if ( empty($aInstance['user_id']) || empty($aInstance['access_token']) )
            {
                _e('Please config your instagram', 'wiloke');
            }else{
                $getInstagram = true;

                if ( !empty($aInstance['cache_interval']) )
                {
                    $instagramCaching = get_transient('wiloke_instagram_caching_'.$aInstance['user_id']);
                    if ( !empty($instagramCaching) )
                    {
                        print $instagramCaching;
                        $getInstagram = false;
                    }
                }

                if ( $getInstagram )
                {
                    $content = $this->pi_get_instagram_feed($aInstance['user_id'], $aInstance['access_token'], $aInstance['number_of_photos']);
                    print $content;

                    if ( !empty($aInstance['cache_interval']) )
                    {
                        delete_transient('wiloke_instagram_caching_'.$aInstance['user_id']);
                        set_transient('wiloke_instagram_caching_'.$aInstance['user_id'], $content, $aInstance['cache_interval']);
                    }
                }

            }
            ?>
        </div>
    <?php
    //endif;
        print $atts['after_widget'];
    }

    public function pi_get_instagram_feed($userid, $accessToken, $count=6)
    {
        $url 	 = 'https://api.instagram.com/v1/users/'.$userid.'/media/recent?access_token='.$accessToken.'&count='.$count;
        $getInstagram = wp_remote_get( esc_url_raw( $url ) );

        if ( !is_wp_error($getInstagram) )
        {
            $blankGif     = PI_URI.'images/blank.gif';
            $getInstagram = wp_remote_retrieve_body($getInstagram);
            $getInstagram = json_decode($getInstagram);

            $out = '';
            for ( $i=0; $i<$count; $i++ )
            {
                $caption = isset($getInstagram->data[$i]->caption->text) ? $getInstagram->data[$i]->caption->text : 'Instagram';
                $out .= '<div class="item"><a href="'.esc_url($getInstagram->data[$i]->link).'" target="_blank"><img class="lazy" data-original="'.esc_url($getInstagram->data[$i]->images->thumbnail->url).'" alt="'.esc_attr($caption).'" width="'.esc_attr($getInstagram->data[$i]->images->thumbnail->width).'" height="'.esc_attr($getInstagram->data[$i]->images->thumbnail->height).'" src="'.esc_url($blankGif).'" /></a></div>';
            }

            return $out;
        }
    }
}

?>