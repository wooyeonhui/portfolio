<?php
/**
 * Archive Header
 * created by ninhle - wiloke team
 * @since 1.0
 */
$title = "";

if ( is_search() )
{
    $title = __('Search Results For: ', 'wiloke');
    $des   = get_search_query();
}elseif( is_category() )
{
    $title = __('Browsing category', 'wiloke');
    $des   = single_cat_title( '', false );
}elseif(is_tag())
{
    $title = __("Browsing tag:", "wiloke");
    $des   = single_tag_title('', false);
}elseif( is_author() )
{
    $title = __('All posts by', 'wiloke');
    $des   = get_the_author();
}elseif( is_archive() )
{
    if ( is_day() ) :
        $title = __("Daily Archives:", "wiloke");
        $des   = get_the_date();
    elseif ( is_month() ) :
        $title = __("Monthly Archives:", "wiloke");
        $des   = get_the_date( _x( 'F Y', 'monthly archives date format', 'wiloke' ) );
    elseif ( is_year() ) :
        $title = __("Yearly Archives:", "wiloke");
        $des   = get_the_date( _x( 'Y', 'yearly archives date format', 'wiloke' )  );
    else :
        $title = __( 'Archives', 'wiloke' );
    endif;
}
if ( !empty($des) )
{
    $title =  $title . ' ' . $des;
}


if ( !empty($title) ) :
    ?>
    <!-- BLOG HEADING -->
    <div class="category-page-title">
        <div class="container">
            <h1><?php echo wp_unslash($title); ?></h1>
        </div>
    </div>
    <!-- END / BLOG HEADING -->
<?php
endif;
?>