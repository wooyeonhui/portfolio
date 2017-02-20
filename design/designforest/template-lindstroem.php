<?php
/*
Template Name: Static Page
*/

get_header();

$sidebar = get_post_meta( $post->ID, '_pi_aresive_static_page_layout', true );
$sectionClass	 = "pi-grid ";
$sectionClass   .= $sidebar;



$args = array(
    'post_type'       => 'lindstroem',
    'posts_per_page'  =>  pi_posts_per_page_of_page_template(),
    'post_status'     => 'publish',
    'paged'           => $paged
);


?>
<section class="main-content <?php echo esc_attr($sectionClass); ?>">
    <div class="pi-container">
        <div class="pi-row">
            <div class="pi-content">
                <?php
                if ( isset($args) )
                {
                    if ( pi_is_static_front_page($post->ID) )
                    {
                        $paged =  get_query_var('page');
                    }else{
                        $paged =  get_query_var('paged');
                    }
                    $paged =  $paged ? $paged : 1;
                    $args['paged'] = $paged;

                    /**/
                    query_posts($args);
                }

                if ( have_posts() ) : while ( have_posts() ) : the_post();
				
				$link 		= get_post_meta( $post->ID, '_pi_link_to', true );

				$size       = 'pi-listlayout';

				?>
				<!-- Post -->
				<article <?php post_class("post"); ?>>
				    <!-- Post Media -->

				    <div class="post-media">
				        <?php if ( has_post_thumbnail($post->ID) ) : ?>
				        <div class="images">
				            <a href="<?php echo esc_url($link); ?>"><?php the_post_thumbnail($post->ID, $size); ?></a>
				        </div>
				        <?php endif; ?>
				    </div>
				    <!-- / Post Media -->

				    <!-- Post Body -->
				    <div class="post-body">
				      	
				        <div class="post-title text-uppercase">
				            <h2><a href="<?php echo esc_url($link); ?>"><?php the_title(); ?></a></h2>
				        </div>

				        <hr class="pi-divider">

				        <div class="post-entry">
				            <?php the_content(); ?>
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
				<?php
                endwhile;
                    get_template_part("navigation");
                else:
                    get_template_part("content", "none");
                endif; 
                wp_reset_postdata();
                ?>
            </div>
            <?php
            if ( $sidebar != 'no-sidebar' )
            {
                get_sidebar();
            }
            ?>
        </div>
    </div>
</section>
<?php get_footer(); ?>