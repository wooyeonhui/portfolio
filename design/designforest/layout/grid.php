<?php
/**
 * Standard layout
 */
global $i, $isFirstLarge;
$postFormat = get_post_format($post->ID);
$link       = pi_get_post_link($post->ID, $postFormat);

// $size       = $i == 1 && $isFirstLarge ? 'full' : 'pi-listlayout';

$size       = 'full';

?>
<!-- Post -->
<article <?php post_class(); ?>>
    <!-- Post Media -->

    <div class="post-media">
        <?php if ( has_post_thumbnail($post->ID) ) : ?>
        <div class="images">
            <a href="<?php echo esc_url($link); ?>"><?php the_post_thumbnail($post->ID, $size); ?></a>
        </div>
        <?php endif; ?>
        <div class="post-meta">
            <?php pi_post_meta($postFormat, $link, $post->ID, true); ?>
        </div>
    </div>
    <!-- / Post Media -->

    <!-- Post Body -->
    <div class="post-body">
        <div class="post-cat">
            <?php echo pi_get_list_of_categories($post->ID); ?>
        </div>


        <div class="post-title text-uppercase">
            <h2><a href="<?php echo esc_url($link); ?>"><?php the_title(); ?></a></h2>
        </div>

        <hr class="pi-divider">

        <div class="post-entry">
            <?php the_excerpt(); ?>
        </div>

        <div class="post-foot">
            <div class="tb">
                <div class="post-more tb-cell">
                    <a href="<?php echo esc_url($link); ?>"><?php _e('Read more', 'wiloke'); ?></a>
                </div>
                <?php pi_render_sharing_box_on_post('1'); ?>
            </div>
        </div>
        <?php edit_post_link(); ?>
    </div>
    <!-- / Post Body -->

</article>
<!-- / Post -->
