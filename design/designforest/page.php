<?php
get_header();

$sectionClass   =  pi_get_sidebar_on_page();
?>
    <section class="main-content pi-single <?php echo esc_attr($sectionClass); ?>">
        <div class="pi-container">
            <div class="pi-row">
                <div class="pi-content">
                    <?php
                    while ( have_posts() ) : the_post();

                        $link       = get_permalink();
                        $title      = get_the_title();
                        ?>
                        <article <?php post_class('post'); ?>>
                            <!-- Post Media -->
                            <?php if ( has_post_thumbnail($post->ID) ) : ?>
                            <div class="post-media">
                                <div class="images">
                                    <?php the_post_thumbnail($post->ID, 'full'); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            <!-- / Post Media -->

                            <div class="post-body">

                                <!--Post meta-->
                                <div class="post-title text-uppercase">
                                    <h1><?php the_title(); ?></h1>
                                </div>
                                <hr class="pi-divider">
                                <div class="post-date">
                                    <span><?php echo pi_get_the_date($post->ID); ?></span>
                                </div>
                                <!--End/Post meta-->

                                <!--Content-->
                                <div class="post-entry">
                                    <?php
                                    the_content();
                                    wp_link_pages(
                                        array(
                                            'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'wiloke' ) . '</span>',
                                            'after'       => '</div>',
                                            'link_before' => '<span>',
                                            'link_after'  => '</span>',
                                            'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'wiloke' ) . ' </span>%',
                                            'separator'   => '<span class="screen-reader-text">, </span>',
                                        )
                                    );
                                    ?>
                                </div>
                                <!--End/Content-->

                                <!--Foot-->
                                <?php
                                $status = pi_is_sharing_box_on_page();
                                if ( $status ) :
                                ?>
                                <div class="post-foot">
                                    <div class="tb">
                                        <?php pi_render_sharing_box_on_page(); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <!--End/Foot-->

                            </div>
                        </article>
                        <?php comments_template(); ?>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
                <?php
                if ( $sectionClass != 'no-sidebar' )
                {
                    get_sidebar();
                }
                ?>
            </div>
        </div>
    </section>
<?php get_footer(); ?>