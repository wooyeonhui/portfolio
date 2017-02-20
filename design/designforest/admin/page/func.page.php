<?php
/**
 * Created by ninhle - wiloke team.
 * Date: 7/18/15
 * Time: 4:42 PM
 */


add_action('save_post', 'pi_save_page_template_data');
add_action('edit_form_after_title', 'pi_add_page_builder_button');

function pi_add_page_builder_button()
{
    if ( isset($_GET['post']) )
    {
        $_GET['post'] = (int)$_GET['post'];
        $getTemplateName = get_page_template_slug($_GET['post']);

        if ( $getTemplateName == 'page-template.php' )
        {
            $pageUrl = admin_url('post.php') . '?post='.$_GET['post'].'&amp;action=edit';
            $pageUrl = urlencode($pageUrl);
            $url  = admin_url('customize.php') . '?return='.$pageUrl;
            ?>
            <a class="button button-primary pi-page-templates-button button-large" href="<?php echo esc_url( $url ) ; ?>"><?php _e('Content Builder', 'wiloke'); ?></a>
        <?php
        }
    }
}


function pi_save_page_template_data($postID)
{
    if ( isset($_POST['post_type']) && $_POST['post_type'] == 'page'  )
    {
        if ( isset($_POST['pi_page_template']) )
        {
            $_POST['pi_page_template'] = pi_unslashed_before_update($_POST['pi_page_template']);
            update_post_meta($postID, "_pi_page_template", $_POST['pi_page_template']);
        }
    }
}

