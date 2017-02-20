<?php
/**
 * Standard layout
 */

$postFormat = get_post_format($post->ID);
$link       = get_permalink();
$title      = get_the_title();
?>
<!-- Post -->
<article <?php post_class(); ?>>
    <!-- Post Media -->
    <?php echo pi_render_post_head_options($post->ID, $postFormat, $title, $link, $sidebar="pi-standard"); ?>
    <!-- / Post Media -->

    <!-- Post Body -->

    <?php if ( $postFormat !='link' && $postFormat != 'quote' ) : ?>
    <div class="post-body">
        <div class="post-cat">
           <?php echo pi_get_list_of_categories($post->ID); ?>
        </div>

        <div class="post-title text-uppercase">
            <h2><a href="<?php echo esc_url($link); ?>"><?php print $title; ?></a></h2>
        </div>

        <hr class="pi-divider">

        <div class="post-date">
            <span><?php echo pi_get_the_date($post->ID); ?></span>
        </div>


        <div class="post-entry">
            <?php pi_content_limit(); ?>
        </div>

        <div class="post-foot">
            <div class="tb">
                <div class="post-meta tb-cell">
                    <?php pi_post_meta($postFormat, $link, $post->ID); ?>
                </div>

                <div class="post-more tb-cell">
                    <a href="<?php echo esc_url($link); ?>"><?php _e('Read more', 'wiloke'); ?></a>
                </div>

                <?php pi_render_sharing_box_on_post('1'); ?>

            </div>
        </div>
        <?php edit_post_link(); ?>
    </div>
    <?php endif; ?>
    <!-- / Post Body -->

</article>
<!-- / Post -->
