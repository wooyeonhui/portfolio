<?php

/**
 * Help upload
 * @param $buttonId: it's very important with the widget upload field
 */

function pi_upload_form($multiple=false, $name, $value="", $buttonId="")
{
    $buttonId = $buttonId ? $buttonId : uniqid("pi_upload_button");
    $url = "";
    if ( $multiple )
    {
        $type   =  'pi-multiple-upload';
        $ids    = $value;
        $value  =  explode(",", $value);
        foreach ( $value as $key  )
        {
            $url .= wp_get_attachment_url($key) . ",";
        }
        $url   = rtrim($url, ",");
    }else{
        $type  =  'pi-single-upload';
    }

?>
    <div class="<?php echo esc_attr($type); ?>">
        <?php if ( !$multiple ) : ?>
        <input class="pi-list-image" type="hidden" value="<?php echo !empty($value) ? esc_url($value) : ''; ?>" name="<?php echo esc_attr($name); ?>">
        <input class="pi-list-id" type="hidden" value="" name="">
        <?php else : ?>
        <input class="pi-list-image" type="hidden" value="<?php echo esc_attr($url); ?>" name="">
        <input class="pi-list-id" type="hidden" value="<?php echo esc_attr($ids); ?>" name="<?php echo esc_attr($name); ?>">
        <?php endif; ?>
        <button id="<?php echo esc_attr($buttonId); ?>" type="button" class="button button-primary pi-btn-upload" value="<?php _e('Upload', 'wiloke'); ?>"><?php _e("Upload", "wiloke") ?></button>
        <ul class="pi-wrap-image pi-show-image">
            <?php
                if( !empty($value) )
                {
                    if ( $multiple )
                    {
                        foreach ( $value as $id )
                        {
                            echo '<li class="pi-item-image" style="display: inline-block;">'.wp_get_attachment_image($id, array(75, 75)).'<div class="pi-media-controls"><i class="pi-media-edit" title="Edit"></i><i class="pi-media-remove" title="Remove"></i></div></li>';
                        }
                    }else{
                        echo '<li class="pi-item-image" style="display: inline-block;"><img src="'.esc_url($value).'" alt="" style="width:75px; height:75px;"><div class="pi-media-controls"><i class="pi-media-edit" title="Edit"></i><i class="pi-media-remove" title="Remove"></i></div></li>';
                    }
                }
            ?>
        </ul>
    </div>
<?php
}

function pi_upload_media($name, $value="")
{
    echo '<div class="audio-wrapper pi-wrap-text-upload">';
        echo '<p><textarea class="wiloke_input form-control pi-insert-audio" type="text" name="'.esc_attr($name).'">'.esc_textarea($value).'</textarea></p>';
        echo '<button type="button" class="button button-primary pi-audio-upload" value="'.__('Upload', 'wiloke').'">'.__("Upload", "wiloke").'</button>';
    echo '</div>';
}

?>