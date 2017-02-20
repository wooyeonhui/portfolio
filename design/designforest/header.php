<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <link href="//www.google-analytics.com" rel="dns-prefetch">
    <meta name="format-detection" content="telephone=no">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php pi_render_favicon(); ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <div id="wrapper">
        <?php
        /* Preloader */
        pi_render_preloader();
        ?>
        <!-- Header -->
        <?php
            $headerBg = pi_get_headerbg();
            $classAdditional = !empty($headerBg) ?  'background-image' : 'no-background';
        ?>
        <header id="pi-header" class="<?php echo esc_attr($classAdditional); ?>" data-background-image="<?php echo esc_attr($headerBg); ?>">
            <div class="header-top">
                <div class="pi-header-fixed">
                    <?php
                    if( has_nav_menu('pi_menu') )
                    {
                        ?>
                        <!-- Toggle menu -->
                        <div class="toggle-menu">
                            <span class="item item-1"></span>
                            <span class="item item-2"></span>
                            <span class="item item-3"></span>
                        </div>
                        <!-- / Toggle menu -->
                        <?php
                        wp_nav_menu(
                            array(
                                'menu'              => 'pi_menu',
                                'container'         => 'nav',
                                'container_class'   => 'pi-navigation',
                                'container_id'      => '',
                                'menu_class'        => 'pi-menulist',
                                'menu_id'           => 'pi_menu',
                                'items_wrap'        => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                            )
                        );
                    }
                    ?>

                    <div class="header-right">
                        <div class="toggle-social item">
                            <i class="fa fa-share-alt"></i>
                        </div>
                        <div class="toggle-search item">
                            <i class="fa fa-search"></i>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Logo -->
            <?php pi_render_logo(); ?>
            <!-- / Logo -->
        </header>
        <!-- / Header -->

        <?php
            /*Render Follow*/
            pi_render_follow(true);

            /*Render Search*/
            pi_render_search();

            /* Featured Slider */
            if ( is_home() || is_page_template('page-template.php') )
            {
                pi_render_featured_slider();
            }
        ?>
