<?php

/**
 * This file contains the settings of post
 */

if ( !defined('ABSPATH') )
{
    die();
}

define('PI_POST_URI', get_template_directory_uri() . '/admin/post/source/');

add_action('edit_form_after_title', 'pi_builder');
add_action('admin_enqueue_scripts',  'pi_post_enqueue_scripts');
add_action('save_post',  'pi_save_data', 10);

function pi_save_data($postID)
{

    if ( !current_user_can('edit_post', $postID) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( !isset($_POST['post_type']) || empty($_POST['post_type']) ) return;

    if  ( $_POST['post_type'] == 'post' )
    {
        $aData = isset($_POST['pi_post']) ? $_POST['pi_post'] : array();

        foreach ( $aData as $key => $data )
        {
            if ( $key == 'video' || $key == 'audio' )
            {
                $aData[$key] = strip_tags($data, '<iframe>');
            }else{
                $aData[$key] = pi_sanitize_data_before_update($data);
            }
        }

        update_post_meta($postID, "pi_post", $aData);
        if ( !pi_get_post_views($postID) )
        {
            pi_set_post_views($postID);
        }
    }
}

function pi_post_enqueue_scripts()
{
    global $typenow;

    if ($typenow == 'post')
    {
        wp_enqueue_media();
        wp_register_script('pi_posts', PI_POST_URI . 'js/pi.posts.js', array(), '1.0', true);
        wp_enqueue_script('pi_posts');
    }
}

function pi_builder()
{
    $screen = get_current_screen();
    $id = get_the_ID();
    if ( $screen->post_type == 'post' )
    {
        $aData = get_post_meta($id, "pi_post", true);

        $aDefault = array('get_image_by'=>'featured_image', 'static'=>'', 'display_as'=>'simple_slideshow', 'slideshow'=>'', 'youtube'=>'', 'link'=>'', 'quote'=>'', 'quote_author'=>'', 'audio'=>'', 'video'=>'', 'linkbg'=>'', 'audio_bg'=>'', 'audio_bg_by'=>'featured_image', 'link_bg_by'=>'featured_image');

        $aData    = wp_parse_args($aData, $aDefault);
        ?>
        <div id="pi-post-head-options"  class="postbox">
            <div class="handlediv" title="Click to toggle"><br></div>
            <h3 class="hndle"><span><?php _e('Post head Options', 'wiloke'); ?></span></h3>
            <div class="inside">
                <div id="pi-header-type" class="form-table panel">
                    <div class="panel-body">
                        <!--Image Slideshow-->
                        <div class="zone-of-slideshow form-group">
                            <div class="tbl-right controls">
                                <div class="pi-wrap-upload">
                                    <?php pi_upload_form(true, 'pi_post[slideshow]', $aData['slideshow']); ?>
                                </div>
                            </div>

                            <div class="tbl-right controls">
                                <label class="form-label">
                                    <b><?php _e('Display as', 'wiloke'); ?></b>
                                </label>

                                <select name="pi_post[display_as]">
                                    <option value="simple_slideshow" <?php selected($aData['display_as'], "simple_slideshow");?>><?php _e("Simple Slideshow", "wiloke") ?></option>
                                    <option value="tiled_gallery" <?php selected($aData['display_as'], "tiled_gallery") ?>><?php _e("Tiled Gallery", "wiloke") ?></option>
                                </select>
                            </div>

                        </div>
                        <!-- End / Image Slideshow -->

                        <!--Image Static-->
                        <div class="zone-of-image form-group">
                            <div class="tbl-right controls">
                                <label class="form-label">
                                    <b><?php _e('Get Image By', 'wiloke'); ?></b>
                                </label>

                                <select name="pi_post[get_image_by]">
                                    <option value="featured_image" <?php selected($aData['get_image_by'], "featured_image");?>><?php _e("Featured image", "wiloke") ?></option>
                                    <option value="upload" <?php selected($aData['get_image_by'], "upload") ?>><?php _e("Upload", "wiloke") ?></option>
                                </select>
                            </div>

                            <div class="tbl-right controls hidden">
                                <div class="pi-wrap-upload">
                                    <?php pi_upload_form(false, 'pi_post[static]', $aData['static']); ?>
                                </div>
                            </div>

                            <script type="text/javascript">
                                jQuery(document).ready(function($){
                                    $('[name="pi_post[get_image_by]"]').change(function(){
                                        if ( $(this).val() != 'featured_image' )
                                        {
                                            $(this).parent().next().removeClass("hidden");
                                        }else{
                                            $(this).parent().next().addClass("hidden");
                                        }
                                    }).trigger("change");
                                })
                            </script>
                        </div>
                        <!--End/Image Static-->

                        <!--Youtube-->
                        <div class="zone-of-youtube form-group">
                            <div class="controls">
                                <label class="form-label"><?php _e('Video', 'wiloke'); ?></label>
                                <textarea class="wiloke_input form-control" type="text" name="pi_post[video]"><?php echo esc_textarea($aData['video']); ?></textarea>
                                <p class="help"><?php _e('Enter video link or embed code', 'wiloke');?></p>
                            </div>
                        </div>
                        <!--End/Youtube-->

                        <!--Audio-->
                        <div class="zone-of-audio form-group">
                            <div class="controls">
                                <?php pi_upload_media('pi_post[audio]', $aData['audio']); ?>
                                <p class="help"><?php _e("Enter embed code or upload  self-hosted audio", "wiloke") ?></p>
                                <p>
                                    <label for="pi-audio-bg"><?php _e('Audio Background', 'wiloke'); ?></label>
                                    <select id="pi-audio-bg" name="pi_post[audio_bg_by]">
                                        <option value="featured_image" <?php selected($aData['audio_bg_by'], "featured_image");?>><?php _e("Use the feature image", "wiloke") ?></option>
                                        <option value="upload" <?php selected($aData['audio_bg_by'], "upload") ?>><?php _e("Upload", "wiloke") ?></option>
                                        <option value="disable" <?php selected($aData['audio_bg_by'], "none") ?>><?php _e("Disable", "wiloke") ?></option>
                                    </select>
                                </p>
                                <div class="hidden">
                                    <label class="form-label"><?php _e('Background', 'wiloke') ?></label>
                                    <?php pi_upload_form(false, 'pi_post[audio_bg]', $aData['audio_bg']); ?>
                                </div>
                                <script type="text/javascript">
                                    jQuery(document).ready(function($){
                                        $('[name="pi_post[audio_bg_by]"]').change(function(){
                                            if ( $(this).val() == 'upload' )
                                            {
                                                $(this).parent().next().removeClass("hidden");
                                            }else{
                                                $(this).parent().next().addClass("hidden");
                                            }
                                        }).trigger("change");
                                    })
                                </script>
                            </div>
                        </div>
                        <!--End/Audio-->

                        <!--Quote-->
                        <div class="zone-of-quote form-group">
                            <div class="controls">
                                <p>
                                    <label class="form-label"><b><?php _e('Quote', 'wiloke') ?></b></label>
                                    <textarea class="form-control" name="pi_post[quote]"><?php echo esc_textarea($aData['quote']); ?></textarea>
                                </p>
                                <p>
                                    <label class="form-label"><b><?php _e('Author', 'wiloke') ?></b></label>
                                    <input class="wiloke_input form-control"  placeholder="Author" type="text" name="pi_post[quote_author]" value="<?php echo esc_attr($aData['quote_author']); ?>">
                                </p>

                            </div>
                        </div>
                        <!--End/Quote-->

                        <!--Link-->
                        <div class="zone-of-link form-group">
                            <div class="controls">
                                <p>
                                    <label class="form-label"><b><?php _e('Link', 'wiloke') ?></b></label>
                                    <input class="wiloke_input form-control" type="text" name="pi_post[link]" value="<?php echo  esc_url($aData['link']); ?>">
                                </p>
                                <p>
                                    <label for="pi-link-bg"><?php _e('Link Background', 'wiloke'); ?></label>
                                    <select id="pi-link-bg" name="pi_post[link_bg_by]">
                                        <option value="featured_image" <?php selected($aData['link_bg_by'], "featured_image");?>><?php _e("Use the feature image", "wiloke") ?></option>
                                        <option value="upload" <?php selected($aData['link_bg_by'], "upload") ?>><?php _e("Upload", "wiloke") ?></option>
                                        <option value="disable" <?php selected($aData['link_bg_by'], "none") ?>><?php _e("Disable", "wiloke") ?></option>
                                    </select>
                                </p>
                                <div class="hidden">
                                    <label class="form-label"><b><?php _e('Background', 'wiloke') ?></b></label>
                                    <?php pi_upload_form(false, 'pi_post[linkbg]', $aData['linkbg']); ?>
                                </div>
                                <script type="text/javascript">
                                    jQuery(document).ready(function($){
                                        $('[name="pi_post[link_bg_by]"]').change(function(){
                                            if ( $(this).val() == 'upload' )
                                            {
                                                $(this).parent().next().removeClass("hidden");
                                            }else{
                                                $(this).parent().next().addClass("hidden");
                                            }
                                        }).trigger("change");
                                    })
                                </script>
                            </div>
                        </div>
                        <!--End/Link-->
                        <!--End/Link-->
                    </div>
                </div>
            </div>
        </div>
    <?php
    }
}

?>
