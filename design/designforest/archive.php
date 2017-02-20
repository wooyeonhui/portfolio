<?php
/**
 * Created by ninhle - wiloke team
 * @since 1.0
 */

get_header();

$layout          = pi_get_archive_layout();

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
?>
<section class="main-content <?php echo esc_attr($sectionClass); ?>">
    <div class="pi-container">
        <div class="pi-row">
            <div class="pi-content">
                <?php

                get_template_part("archivesearch", "header");

                if ( have_posts() ) :
                    $i = 1;
                    while ( have_posts() ) : the_post();
                        get_template_part("layout/".$layout);
                        $i++;
                    endwhile;
                    get_template_part("navigation");
                else:
                    get_template_part("content", "none");
                endif; wp_reset_postdata();
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
<?php
get_footer();
?>