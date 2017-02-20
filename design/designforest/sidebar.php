<?php
/**
 * Created by ninhle - wiloke team.
 * Date: 7/14/15
 * Time: 3:07 PM
 */
?>
<!-- Sidebar -->
<div class="pi-sidebar">
    <?php
        if ( is_active_sidebar('pi_sidebar') )
        {
            dynamic_sidebar('pi_sidebar');
        }
    ?>
</div>
<!-- / Sidebar -->