<?php
/**
 * Customize Google Font
 */

add_action('admin_menu', 'pi_add_admin_menu');

function pi_add_admin_menu()
{
    add_theme_page( __('Google Font', 'wiloke'), __('Google Font', 'wiloke'), 'edit_theme_options', 'pi-googlefont', 'pi_set_googlefont' );
}

function pi_set_googlefont()
{
    if ( current_user_can('edit_theme_options') && isset($_POST['pi_googlefont']) && !empty($_POST['pi_googlefont'])  )
    {
        pi_update_googlefont($_POST['pi_googlefont']);
    }

    $rawGoogleFont = get_option("pi_raw_googlefont");
    $rawGoogleFont = $rawGoogleFont ? wp_unslash($rawGoogleFont) : "";

    ?>
    <form action="" name="pi_googlefont" method="POST">
        <table class="form-table">
            <tr>
                <th><?php _e("Enter your googlefont", "wiloke"); ?></th>
                <td>
                    <input name="pi_googlefont"  type="text" value="<?php echo esc_attr($rawGoogleFont); ?>">
                    <p>
                        Ex: &lt;link href='http://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'> <br />
                    </p>
                </td>
            </tr>
            <tr>
                <th></th>
                <td><input type="submit" class="button button-primary" name="pi_save_googlefont" value="<?php _e('Save', 'wiloke'); ?>"></td>
            </tr>
        </table>
    </form>
    <?php
}


/**
 * parse and update googlefont
 * @since 1.0
 */
function pi_update_googlefont($font)
{

    $font = stripslashes($font);
    preg_match('/(?:family=)([^:&\'\"]*)(?:\:?)((?:[^&\'\"]*))/', $font, $match);
    preg_match('/(?:href=)(?:[\'\"])([^\'\"]*)/', $font, $googleFont);
    $fontFamily = str_replace(":", "", $match[1]);

    if ( isset($match[2]) && !empty($match[2]) )
    {
        $parseStyle = explode(",", $match[2]);

        foreach ( $parseStyle as $style )
        {
            $fontStyle = preg_replace("/(\d*)/", "", $style);

            $weight = (int)$style;

            switch ($weight)
            {
                case 100:
                    $weightName = "Thin";
                    break;
                case 300:
                    $weightName = "Light";
                    break;
                case 500:
                    $weightName = "Medium";
                    break;
                case 900:
                    $weightName = "Ultra-Bold";
                    break;
                case 700:
                    $weightName = "Bold";
                    break;
                case 800:
                    $weightName = "Extra-Bold";
                    break;
                default:
                    $weightName = "Normal";
                    break;
            }
            $aFontStyles['normal']  = 'Normal';
            $aFontStyles[$style] 	= $weightName . ' ' . $weight . ' ' . ucfirst($fontStyle);
        }

    }else{
        $aFontStyles = false;
    }


    update_option("pi_raw_googlefont", $font);
    update_option("pi_fontfamily", $fontFamily);
    update_option("pi_fontweight", $aFontStyles);
    update_option("pi_fontsrc", $googleFont[1]);
}