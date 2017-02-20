<?php
/**
 * Created by ninhle - wiloke team
 * @since 1.0
 */
function pi_render_search()
{
    $search = piBlogCustomize::pi_refresh_in_customize('pi_options[logoheader][search]') ? piBlogCustomize::pi_refresh_in_customize('pi_options[logoheader][search]') : piBlogFramework::$piOptions['logoheader']['search'];

    if ( $search != 'disable' && !empty($search) )
    {
        do_action('pi_before_render_search');
        ?>
        <!-- Page search -->
        <div class="page-search">
            <div class="tb">
                <span class="page-search-close">&times;</span>
                <div class="tb-cell">
                    <form action="<?php echo home_url(); ?>" method="get" >
                        <input name="s" type="text" value="<?php _e('Search and hit enter', 'wiloke'); ?>">
                    </form>
                </div>
            </div>
        </div>
        <!-- / Page search -->
        <?php
        do_action('pi_after_render_search');
    }
}
?>