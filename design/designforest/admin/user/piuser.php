<?php
/**
 * Created by ninhle - wiloke team.
 * User: wiloke
 * Date: 7/15/15
 * Time: 7:21 PM
 */

add_action( 'personal_options','pi_profile_fields');
add_action( 'personal_options_update', 'pi_profile_update');
add_action( 'edit_user_profile_update', 'pi_profile_update');
add_action( 'admin_enqueue_scripts','pi_author_enqueue_scripts');



function pi_profile_fields( $user )
{
    $aUserInfo  = get_user_meta( $user->ID, 'pi_user_info', true );
    $avatar 	= isset($aUserInfo['avatar']) ? $aUserInfo['avatar'] : '';
    ?>
    <table class="form-table">
        <?php do_action('pi_before_author_info');  ?>
        <tbody>
        <tr class="user-rich-editing-wrap">
            <th scope="row">
                <label  class="form-label"><?php _e('Avatar', 'wiloke'); ?></label>
                <p><i><?php _e('We recommend a avatar size of 132x132 pixels', 'wiloke'); ?></i></p>
            </th>
            <td class="controls pi-wrap-upload">
                <?php pi_upload_form(false, 'pi_user_info[avatar]', $avatar); ?>
            </td>
        </tr>
        </tbody>
        <tbody>

        <tr class="user-rich-editing-wrap pi-flag">
            <th scope="row">
                <label class="form-label"><?php _e('Enable/Disable Social', 'wiloke') ?></label>
            </th>
            <td class="controls">
                <select id="pi_toggle_social" name="pi_user_info[toggle_social]">
                    <option value="1" <?php echo isset($aUserInfo['toggle_social'])  && $aUserInfo['toggle_social'] == 1 ? 'selected' : '';  ?>><?php _e('Enable', 'wiloke') ?></option>
                    <option value="0" <?php echo isset($aUserInfo['toggle_social'])  && $aUserInfo['toggle_social'] == 0 ? 'selected' : '';  ?>><?php _e('Disbale', 'wiloke') ?></option>
                </select>
            </td>
        </tr>

        <tr id="user-rich-editing-wrap" class="user-rich-editing-wrap">
            <th scope="row">
                <label  class="form-label"><?php _e('Social Networks', 'wiloke'); ?></label>
            </th>
            <td class="controls">
                <?php foreach ( piConfigs::$aConfigs['configs']['rest']['follow'] as $key => $aInfo ) : ?>
                    <div class="wrap-user-social">
                        <div class="pi-social pi-parent">
                            <i class="<?php echo esc_attr($aInfo[0]); ?> js_default_icon"></i>
                            <input type="text" value="<?php echo isset($aUserInfo['social_link'][$key]) ? esc_url($aUserInfo['social_link'][$key]) : '';  ?>" name="pi_user_info[social_link][<?php echo esc_attr($key); ?>]" />
                        </div>
                    </div>
                <?php  endforeach; ?>
            </td>
        </tr>

        <script type="text/javascript">
            jQuery(document).ready(function($){
                $("#pi_toggle_social").change(function()
                {
                    if ( $(this).val() == 1 )
                    {
                        $("#user-rich-editing-wrap").fadeIn();
                    }else{
                        $("#user-rich-editing-wrap").fadeOut();
                    }
                }).trigger("change");
            })
        </script>

        </tbody>
        <?php do_action('pi_after_author_info');  ?>
    </table>
<?php
}

function pi_profile_update($user_id)
{
    if ( current_user_can('edit_user',$user_id) )
    {
        if ( isset($_POST['pi_user_info']) && !empty($_POST['pi_user_info']) )
        {
            update_user_meta($user_id, "pi_user_info", $_POST['pi_user_info']);
        }
    }
}

function pi_author_enqueue_scripts()
{
    $screen = get_current_screen();

    if ( isset($screen->id) && ($screen->id == 'profile' || $screen->id == 'user-edit') )
    {
        wp_enqueue_media();
        wp_register_style('pi-fontawesome', get_template_directory_uri() . '/css/lib/font-awesome.min.css');
        wp_enqueue_style('pi-fontawesome');
    }
}
