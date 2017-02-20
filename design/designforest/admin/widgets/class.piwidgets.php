<?php

if ( !defined('ABSPATH') )
{
    die();
}

/**
 * Wiloke Unlimited Widgets
 * This function contains the general functions, which be used in the each widget item
 */

define('PI_WS_URI', PI_A_URI . 'widgets/source/');

class piWidgets extends piBlogFramework
{
    public function __construct()
    {
        add_action('widgets_init', array($this, 'pi_widgets_init'));
        add_action('admin_enqueue_scripts', array($this, 'pi_widgets_scripts'));
    }

    public function pi_widgets_init()
    {
        foreach (piConfigs::$aConfigs['configs']['widget']['items'] as $widget)
        {
            register_widget( $widget );
        }
    }

    /**
     * Enqueue scripts to the widgets area
     */
    public function pi_widgets_scripts()
    {
        global $pagenow;

        if ( ( $pagenow && $pagenow == 'widgets.php' ) || has_action('customize_controls_init') )
        {
            wp_enqueue_media();
            wp_enqueue_script('jquery-ui-sortable');

            wp_register_script('pi_widgets', PI_WS_URI . 'js/widgets.js', array('jquery'), '1.0', true);
            wp_enqueue_script('pi_widgets');
        }
    }

    /**
     * Creating text field
     */
    static function pi_text_field($label, $id, $name, $val, $des="")
    {
        ?>
        <p>
            <label for="<?php echo esc_attr($id); ?>"><?php printf( (__('%s', 'wiloke')), $label); ?></label>
            <input id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($val); ?>" class="widefat <?php echo isset($args['class']) ? esc_attr($args['class']) : ''; ?>" type="text" />
            <?php
            if ( !empty($des) )
            {
                echo '<p><code>'.$des.'</code></p>';
            }
            ?>
        </p>
    <?php
    }

    /**
     * Creating text field
     */
    static function pi_link_field($label, $id, $name, $val, $des="")
    {
        ?>
        <p>
            <label for="<?php echo esc_attr($id); ?>"><?php printf( (__('%s', 'wiloke')), $label); ?></label>
            <input id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($val); ?>" class="widefat <?php echo isset($args['class']) ? esc_url($args['class']) : ''; ?>" type="text" />
            <?php
            if ( !empty($des) )
            {
                echo '<p><code>'.$des.'</code></p>';
            }
            ?>
        </p>
    <?php
    }

    /**
     * Creating upload field
     * pi_upload_form this function created at help->func.help.php
     */
    static function pi_upload_field($label, $id, $buttonId, $name, $multiple=false, $value, $des="")
    {
        ?>
        <p>
            <label for="<?php echo esc_attr($id); ?>"><?php print $label; ?></label>
            <?php pi_upload_form($multiple, $name, $value, $buttonId); ?>
            <?php
                if ( !empty($des) )
                {
                    print '<code class="help">'. sprintf( (__('%s', 'wiloke')), wp_unslash($des) ) . '</p>';
                }
            ?>
            <script type="text/javascript">
                (function($){
                    if ( $.windowLoad )
                    {
                        var interval = setInterval( function()
                        {
                            var $self = $('.pi-btn-upload', '#widgets-right');
                            $.each($self, function()
                            {
                                if ( $(this).attr('id') != 'widget-pi_about-__i__-upload_button')
                                {
                                    $(this).parent().piMedia();
                                    clearInterval(interval);
                                }
                            });
                        },100);
                    }
                })(jQuery)
            </script>
        </p>
        <?php
    }

    /**
     * Creating select field
     */
    static function pi_select_field($label, $id, $name, $aOptions, $val, $des="")
    {
        ?>
        <p>
            <label for="<?php echo esc_attr($id); ?>"><?php printf( (__('%s', 'wiloke')), $label); ?></label>
            <select id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>">
                <?php
                foreach ( $aOptions as $value => $optionName ) :
                    ?>
                    <option value="<?php echo esc_attr($value); ?>" <?php selected($value, $val); ?>><?php echo esc_html($optionName); ?></option>
                <?php
                endforeach;
                ?>
            </select>
            <?php
            if ( !empty($des) )
            {
                echo '<p><code>'.$des.'</code></p>';
            }
            ?>
        </p>
    <?php
    }

    /**
     * Creating textarea field
     */
    static function pi_textarea_field($label, $id, $name, $val, $des="")
    {
        ?>
        <p>
            <label for="<?php echo esc_attr($id); ?>"><?php printf( (__('%s', 'wiloke')), $label); ?></label>
            <textarea id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($name); ?>" class="widefat" type="text"><?php echo esc_attr($val); ?></textarea>
            <?php
            if ( !empty($des) )
            {
                echo '<p><code>'.$des.'</code></p>';
            }
            ?>
        </p>
    <?php
    }

    /**
     * Showing the list of posts
     * @param $args an array contain all of info of query
     */
    static function pi_list_of_posts($args)
    {
        $query = new WP_Query($args);
        if ( $query->have_posts()  ) : while ( $query->have_posts() ) : $query->the_post();
            $link = get_permalink($query->post->ID);
        ?>
            <div class="item">
                <div class="item-image">
                    <div class="image-cover">
                        <?php if ( has_post_thumbnail($query->post->ID) ) : ?>
                            <a href="<?php echo esc_url($link); ?>">
                                <?php echo get_the_post_thumbnail($query->post->ID, 'pi-postlisting'); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="item-content">
                    <h3 class="item-title" data-number-line="2">
                        <a href="<?php echo esc_url($link); ?>"><?php echo get_the_title($query->post->ID); ?></a>
                    </h3>
                    <span class="item-meta"><?php echo pi_get_the_date($query->post->ID); ?></span>
                </div>
            </div>
        <?php
        endwhile; else:
        _e('<p>There isn\'t post yet</p>', 'wiloke');
        endif;wp_reset_postdata();
    }

    /**
     * Showing the list of commented
     */
    static function pi_list_of_commented($args)
    {
        $query =  get_comments( $args );
        if ( $query )
        {
            foreach( $query as $comment )
            {
                $link = get_permalink($comment->comment_post_ID);
                ?>
                <div class="item">
                    <div class="item-image">
                        <div class="image-cover">
                            <a href="<?php echo esc_url( $link ); ?>">
                                <?php print get_avatar( $comment->user_id, apply_filters('pi_tab_comment', 70)); ?>
                            </a>
                        </div>
                    </div>
                    <div class="item-content">
                        <h3 class="item-title" data-number-line="2">
                            <a href="<?php echo esc_url($link); ?>"><?php echo esc_html($comment->comment_author); ?></a>
                        </h3>
                        <div class="font-size__14 font-style__italic">
                            <p><?php print $comment->comment_content; ?></p>
                        </div>
                        <span class="item-meta"><?php echo pi_get_the_date($comment->comment_post_ID); ?></span>
                    </div>
                </div>
            <?php
            }
        }else{
            _e('<p>There are no any comments yet</p>', 'wiloke');
        }
    }
}

new piWidgets();

?>