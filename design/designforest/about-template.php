<?php
/* Template Name: About */

get_header();
?>
    <section class="main-content pi-page no-sidebar pi-about">
        <div class="pi-container">
            <div class="pi-row">
                <div class="pi-content">
                        <div class="category-page-title">
                            <h1><?php the_title(); ?></h1>
                        </div>
                        <?php
                        if ( have_posts() ) :
                            while ( have_posts() ) : the_post();
                                ?>
                                <article class="post">
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
                                        <!--Content-->
                                        <div class="post-entry">
                                            <?php
                                            the_content();
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
                            <?php
                            endwhile;
                        else:
                            get_template_part("content", "none");
                        endif; wp_reset_postdata();
                        ?>
                </div>
            </div>
        </div>
    </section>
<?php
get_footer();
?>