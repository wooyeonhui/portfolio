<!DOCTYPE html>
<html>
<head>
     <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body id="target">
<?php
get_header();

$layout          = pi_get_content_layout();
$isFirstLarge    = false;
if ( strpos($layout, "_") !== false )
{
    $parseLayout  = explode("_", $layout);
    $layout       = $parseLayout[0];
    $isFirstLarge = true;
    $sectionClass = 'pi-'.$layout. ' pi-'.$layout.'-'.$parseLayout[1] . ' ';
}else{
    $sectionClass = 'pi-'.$layout. ' ';
}

$sidebar         =  pi_get_sidebar_layout();
$sectionClass   .= $sidebar;


if ( pi_is_page_template() )
{
    $args = array(
        'post_type'       => 'post',
        'posts_per_page'  =>  pi_posts_per_page_of_page_template(),
        'post_status'     => 'publish',
        'paged'           => $paged
    );

    $cats = pi_get_categories();

    if ( !empty($cats) )
    {
        $args['category__in'] = $cats;
    }
}

if ( !empty(piBlogFramework::$piFeaturedPostsId) )
{
    $args['post__not_in'] = piBlogFramework::$piFeaturedPostsId;
}

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

                $i = 1;
                if ( have_posts() ) : while ( have_posts() ) : the_post();
                    get_template_part("layout/".$layout);
                    $i++;
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
</body>
</html>
