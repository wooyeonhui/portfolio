                <footer id="pi-footer" class="background-image clearfix" data-background-image="<?php echo esc_url(pi_get_footerbg()); ?>">
                <div class="pi-container">

                    <div class="footer-content">
                        <?php
                        foreach ( piConfigs::$aConfigs['configs']['widget']['footer'] as $footer )
                        {
                            echo '<div class="footer-column">';
                            if ( is_active_sidebar($footer) )
                            {
                                dynamic_sidebar($footer);
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                    <?php pi_render_footer_social(); ?>
                    <?php pi_render_copyright(); ?>
                </div>
            </footer>
            </div> <!-- End / wrapper -->
        <?php wp_footer(); ?>
    </body>
</html>