<div class="pi-nav-page pi-pagination" style="text-align:center;">
    <?php
    global $wp_query;
    // next_posts_link( (__('Older Posts', 'wiloke')) );
    // previous_posts_link( (__('Newer Posts', 'wiloke')) );
    $big = 999999999; // need an unlikely integer

	echo paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages
	) );
    ?>
</div>
