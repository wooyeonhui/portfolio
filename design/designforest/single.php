<?php
get_header();

$sectionClass   =  pi_get_sidebar_layout();
?>
    <section class="main-content pi-single <?php echo esc_attr($sectionClass); ?>">
        <div class="pi-container">
            <div class="pi-row">
                <div class="pi-content">
                   <?php
                   while ( have_posts() ) : the_post();
                       $postFormat = get_post_format($post->ID);
                       $link       = get_permalink();
                       $title      = get_the_title();
                   ?>
                   <article <?php post_class(); ?>>
                       <!--Post media-->
                       <?php echo pi_render_post_head_options($post->ID, $postFormat, $title, $link); ?>
                       <!--End/Post media-->
                       <div class="post-body">

                           <!--Post meta-->
                           <div class="post-cat">
                               <?php echo pi_get_list_of_categories($post->ID); ?>
                           </div>
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

                           <!--Tags-->
                           <?php if ( has_tag() ) : ?>
                           <div class="tag-box">
                               <?php the_tags(); ?>
                           </div>
                            <?php endif; ?>
                           <!--End/Tags-->

                           <!--Foot-->
                            <div class="post-foot">
                                <div class="tb">
                                    <div class="post-meta tb-cell">
                                        <?php pi_post_meta($postFormat, $link, $post->ID); ?>
                                    </div>
                                    <?php pi_render_sharing_box_on_post('1'); ?>
                                </div>
                            </div>
                           <!--End/Foot-->

                       </div>
                   </article>
                    <?php get_template_part("author-bio"); ?>
                    <?php pi_render_related_posts(); ?>
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