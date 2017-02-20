<?php

define('PI_THEMENAME', 'Aresivel ');
define('PI_PATH', get_template_directory() . '/');
define('PI_URI', get_template_directory_uri() . '/');
define('PI_A_PATH', get_template_directory() . '/admin/');
define('PI_A_URI', get_template_directory_uri() . '/admin/');
define('PI_LIB_PATH', PI_A_PATH . 'lib/');
define('PI_FE_CSS_URI', get_template_directory_uri() . '/css/');
define('PI_FE_JS_URI', get_template_directory_uri() . '/js/');


/**
 * Wiloke Blog Framework
 */
require_once PI_A_PATH . "class.piConfigs.php";
require_once PI_A_PATH . "class.piblogframework.php";
require_once PI_A_PATH . "customize/func.views.php";
require_once PI_A_PATH . "theme-settings/func.googlefont.php";
require_once PI_A_PATH . "theme-settings/func.mailchimp.php";
require_once PI_A_PATH . "widgets/piBootstrap.php";
require_once PI_A_PATH . "help/func.help.php";
require_once PI_A_PATH . "post/func.post.php";
require_once PI_A_PATH . "page/func.page.php";
require_once PI_A_PATH . "user/piuser.php";
require_once PI_A_PATH . "plugins/plugin-activation.php";
require_once PI_A_PATH . "wiloke-news/func.news.php";

/*Header Scripts*/
add_action('wp_head', 'pi_add_header_code');
function pi_add_header_code()
{
    ?>
    <script type="text/javascript">
        window.PI_OB  = {};
        PI_OB.ajaxurl = "<?php echo esc_js(admin_url('admin-ajax.php')); ?>";
        PI_OB.imageuri = "<?php echo esc_js(PI_URI.'images/'); ?>";
    </script>
    <?php
    /*Add favicon to your website*/
    $favicon = pi_render_favicon();
    if ( !empty($favicon) )
    {
        echo '<link rel="shortcut icon" href="'.esc_url($favicon).'"/>';
    }

    $customColor = pi_get_custom_color();

    if ( $customColor !='default' )
    {
        wp_register_style( 'pi_custom_color', PI_FE_CSS_URI . 'colors/' . $customColor . '.css', array(), '1.0' );
        wp_enqueue_style( 'pi_custom_color' );
    }

    pi_render_header_custom_css();
    pi_render_header_custom_js();
    pi_render_header_custom_code();
}

/*Footer Scripts*/
add_action('wp_footer', 'pi_add_footer_code');
function pi_add_footer_code()
{
    pi_render_footer_custom_js();
    pi_render_footer_custom_code();
}

/*Add theme support*/

function pi_wiloke_setup()
{
    add_theme_support('html5', array( 'search-form', 'comment-form', 'comment-form' ));
    add_theme_support( 'title-tag' );
    add_theme_support('menus');
    add_theme_support('widgets');
    add_theme_support('post-thumbnails');
    add_theme_support( 'post-formats', piConfigs::$aConfigs['configs']['post_formats'] );
    add_theme_support('automatic-feed-links');

    add_image_size('pi-postlisting', 425, 255, true);
    add_image_size('pi-listlayout', 320, 256, true);
    add_image_size('pi-standard', 1000, '', true);
    add_image_size('pi-featuredslider', 1800, '', true);
//    add_image_size('pi-listlayout', array(384, 256), true);

    load_theme_textdomain('wiloke', get_template_directory() . '/languages');

    if (!isset($content_width))
    {
        $content_width = 900;
    }

}
add_action('after_setup_theme', 'pi_wiloke_setup');

/*Sanatize data before update*/
function pi_sanitize_data_before_update($data)
{
    $data =  ( is_array($data) ) ? array_map('pi_sanitize_data_before_update', $data) : sanitize_text_field($data);
    return $data;
}

/**
 * the useful functions of wiloke
 */

/*Get date*/
function pi_get_the_date($postID, $format="")
{
    $format     = $format ? $format : get_option('date_format');
    $date       = get_the_date($format, $postID);
    return $date;
}

/*Post views*/
function pi_set_post_views($postID)
{
    $count_key = 'pi_post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if( !$count )
    {
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, 1);
    }else{
        $count = (int)$count + 1;
        update_post_meta($postID, $count_key, $count);
    }
}

function pi_get_post_views($postID)
{
    $count_key = 'pi_post_views_count';
    return get_post_meta($postID, $count_key, true);
}

/*Get the post format*/
function pi_get_the_post_format($postID)
{

    if ( has_post_format(piConfigs::$aConfigs['configs']['post_formats'], $postID) )
    {
        $icon = "";
        switch (get_post_format($postID))
        {
            case 'image':
                $icon = 'fa fa-image';
                break;

            case 'gallery':
                $icon = 'fa fa-picture-o';
                break;

            case 'video';
                $icon = 'fa fa-youtube-play';
                break;

            case 'audio':
                $icon = 'fa fa-music';
                break;

            case 'quote':
                $icon = 'fa fa-quote-right';
                break;

            case 'link':
                $icon = 'fa fa-link';

            default:
                $icon = '';
                break;
        }

        return $icon;
    }
}

/*Get author page*/
function pi_get_author_page()
{
    return get_author_posts_url( get_the_author_meta('ID') );
}

/*Check email*/
function pi_is_valid_email($email)
{
    $isValid = true;
    $atIndex = strrpos($email, "@");
    if (is_bool($atIndex) && !$atIndex)
    {
        $isValid = false;
    }
    else
    {
        $domain = substr($email, $atIndex+1);
        $local = substr($email, 0, $atIndex);
        $localLen = strlen($local);
        $domainLen = strlen($domain);
        if ($localLen < 1 || $localLen > 64)
        {
            // local part length exceeded
            $isValid = false;
        }
        else if ($domainLen < 1 || $domainLen > 255)
        {
            // domain part length exceeded
            $isValid = false;
        }
        else if ($local[0] == '.' || $local[$localLen-1] == '.')
        {
            // local part starts or ends with '.'
            $isValid = false;
        }
        else if (preg_match('/\\.\\./', $local))
        {
            // local part has two consecutive dots
            $isValid = false;
        }
        else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
        {
            // character not valid in domain part
            $isValid = false;
        }
        else if (preg_match('/\\.\\./', $domain))
        {
            // domain part has two consecutive dots
            $isValid = false;
        }
        else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
            str_replace("\\\\","",$local)))
        {
            // character not valid in local part unless
            // local part is quoted
            if (!preg_match('/^"(\\\\"|[^"])+"$/',
                str_replace("\\\\","",$local)))
            {
                $isValid = false;
            }
        }
        if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
        {
            // domain not found in DNS
            $isValid = false;
        }
    }
    return $isValid;
}

/*Detect user agent*/
function pi_lt_ie9()
{
    if ( preg_match('/(?i)msie [1-8]/',$_SERVER['HTTP_USER_AGENT']) )
    {
        return true;
    }

    return false;
}

/*Parse Video*/
function pi_parse_video($link)
{
    $getId  = "";
    $type   = "";
    if ( preg_match("#youtube#", $link) )
    {
        $parse = strpos($link, '=');
        $getId = substr($link, $parse);
        $getId = str_replace("=", "", $getId);
        $type  = "youtube";
    }elseif (preg_match("#vimeo#", $link)) {
        $getId = preg_replace("/https?:\/\/vimeo.com\//", "", $link);
        $type  = "vimeo";
    }

    return array('type'=>$type, 'id'=>$getId);
}

/*Is Page Template*/
function pi_is_page_template()
{
    if ( has_action('customize_controls_init') )
    {
        $pageTemplate = piBlogFramework::pi_get_page_template_slug();
        if ( isset($pageTemplate['template']) && $pageTemplate['template'] == 'page-template.php' )
        {
            return true;
        }
    }else{
        if ( is_page_template('page-template.php') )
        {
            return true;
        }
    }

    return false;
}

/**
 * Post Meta
 */
function pi_post_meta($postFormat, $link, $postID, $postDate=false)
{
     if ( $postFormat && $postFormat != 'standard' ) :
     ?>
    <div class="post-format item">
        <a href="<?php echo esc_url($link); ?>"><i class="<?php echo esc_attr( pi_get_the_post_format($postID) ); ?>"></i></a>
    </div>
    <?php endif; ?>
    <div class="post-author item">
        <span><?php _e('By ', 'wiloke');?><a href="<?php echo pi_get_author_page($postID); ?>"><?php echo get_the_author(); ?></a></span>
    </div>
    <?php if ( $postDate ) : ?>
    <div class="post-date item">
        <span><?php echo pi_get_the_date($postID); ?></span>
    </div>
    <?php endif; ?>
    <div class="post-comment item">
        <a href="<?php echo esc_url($link); ?>"><?php echo pi_get_comments_number($postID); ?></a>
    </div>
    <?php
}

/*is static front page*/
function pi_is_static_front_page($postID)
{
    if ( get_option('show_on_front') == 'page' )
    {
        if ( get_option('page_on_front') == $postID )
        {
            return true;
        }
    }

    return false;
}

/**
 * Get post link
 */
function pi_get_post_link($postID, $postFormat)
{
    $isGetLink = true;
    $link      = "";
    if ( $postFormat == 'link' )
    {
        $content = get_the_content($postID);
        if ( $content == '' )
        {
            $aData = get_post_meta($postID, 'pi_post', true);
            $link  = $aData['link'];
            $isGetLink = false;
        }
    }

    if ( $isGetLink )
    {
        $link = get_permalink($postID);
    }
    return $link;
}

/**
 * Render the post head option
 * @param $postID : the id of post
 * $getFormat: the post format, which you want to get it.
 */
function pi_render_post_head_options($postID,  $getFormat="", $title="", $link="", $layout="")
{
    if ( empty($getFormat) )
    {
        if ( !has_action('pi_show_featured_image') )
        {
            $getFormat = get_post_format($postID);
        }else{
            $getFormat = 'image';
            $aFormatData['get_image_by'] = 'featured_image';
        }
    }

    $aFormatData = get_post_meta($postID, "pi_post", true);
    $link        = $link ? $link : get_permalink($postID);
    $title       = $title ? $title : get_the_title($postID);
    $render      = "";
    $blankGif    = PI_URI . 'images/blank.gif';
    switch ( $getFormat )
    {
        case 'gallery':
            if ( isset($aFormatData['slideshow']) && !empty($aFormatData['slideshow']) )
            {
                if ( isset($aFormatData['display_as']) && $aFormatData['display_as'] == 'tiled_gallery' )
                {
                    $class = 'tiled-gallery lazy';
                    $size  = 'pi-standard';
                }else{
                    $class = 'images-slider';
                    // $size  = $layout == 'no-sidebar' ? 'full' : 'pi-standard';
                    $size  = 'pi-standard';
                }

                $render .= '<div class="'.esc_attr($class).'">';
                $aSlideData = explode(",", $aFormatData['slideshow']);
                foreach ( $aSlideData as $id )
                {
                    $aImg    = wp_get_attachment_image_src($id, $size);

                    $render .= '<a class="item" href="'.esc_url($aImg[0]).'">';
                    $render .= '<img src="'.esc_url($aImg[0]).'" width="'.esc_attr($aImg[1]).'" height="'.esc_attr($aImg[2]).'" alt="'.esc_attr($title).'">';

                    $render .= '</a>';
                }
                $render .= '</div>';
            }
            break;
        case 'image':
                $img = "";
                if( isset($aFormatData['get_image_by']) && $aFormatData['get_image_by'] != 'featured_image' )
                {
                    if ( !empty($aFormatData['static']) )
                    {
                        $img = '<img class="lazy" src="'.esc_url($blankGif).'" data-original="'.esc_url($aFormatData['static']).'" alt="'.esc_attr(get_the_title($postID)).'">';
                    }
                }else{
                    if ( has_post_thumbnail($postID) )
                    {
                        $img = get_the_post_thumbnail($postID, 'pi-standard');
                    }
                }
                if ( !empty($img) )
                {
                    $render .= '<div class="images">';
                    $render .= apply_filters('pi_filter_wrapper_image_in_the_post_media', $img, $link, $postID);
                    $render .= '</div>';
                }

            break;
        case 'video':
            if ( isset($aFormatData['video']) && !empty($aFormatData['video']) )
            {
                $render .= '<div class="embed-responsive embed-responsive-16by9">';
                if ( !preg_match('/^<(iframe|object)/', $aFormatData['video']) )
                {
                    $parseVideo = pi_parse_video($aFormatData['video']);

                    if ($parseVideo['type'] == "youtube")
                    {
                        $url     = '//www.youtube.com/embed/'.$parseVideo['id'];
                        $render .= '<iframe class="embed-responsive-item" src="'.esc_url($url).'" class="embed-responsive-item"></iframe>';
                    }else{
                        $url     = '//player.vimeo.com/video/'.$parseVideo['id'].'?title=0&amp;byline=0&amp;portrait=0';
                        $render .= '<iframe class="embed-responsive-item" src="'.esc_url($url).'" class="embed-responsive-item"></iframe>';
                    }
                }else{
                    $render     .= $aFormatData['video'];
                }
                $render .= '</div>';
            }
            break;
        case 'quote':
            if ( isset($aFormatData['quote']) && !empty($aFormatData['quote']) )
            {
                $render .=  '<div class="post-quote">';
                    $render .=  '<blockquote>';
                        $render .=  '<span class="quote-icon">&rdquo;</span>';
                        if ( isset($aFormatData['quote']) && !empty($aFormatData['quote']) )
                        {
                            $render .= sprintf("<p>%s</p>", wp_unslash($aFormatData['quote']));
                        }
                        if ( isset($aFormatData['quote_author']) && !empty($aFormatData['quote_author']) )
                        {
                            $render .= sprintf("<footer>%s</footer>", wp_unslash($aFormatData['quote_author']));
                        }
                    $render .=  '</blockquote>';
                    if ( current_user_can('edit_theme_options', $postID) )
                    {
                        $render .= '<a href="'.get_edit_post_link($postID).'" class="post-edit-link">'.__('Edit This', 'wiloke').'</a>';
                    }
                $render .=  '</div>';
            }
            break;
        case 'audio':
            if ( isset($aFormatData['audio']) && !empty($aFormatData['audio']) )
            {

                if ( $aFormatData['audio_bg_by'] != 'disable' )
                {
                    $bg = '';
                    if ( $aFormatData['audio_bg_by'] == 'featured_image' )
                    {
                        if ( has_post_thumbnail($postID) )
                        {
                            $bg = get_the_post_thumbnail($postID, 'pi-standard');
                        }
                    }else{
                        $bg = isset($aFormatData['audio_bg']) && !empty($aFormatData['audio_bg']) ? '<img src="'.esc_url($aFormatData['audio_bg']).'" alt="'.esc_attr($title).'">' : '';
                    }

                    if ( !empty($bg) )
                    {
                        $render .= '<div class="images">';
                            if ( is_single() )
                            {
                                $render .= $bg;
                            }else{
                                $render .= '<a href="'.esc_url($link).'">'.$bg.'</a>';
                            }

                        $render .= '</div>';
                    }
                }
                $render .= '<div class="post-audio">';
                    if ( preg_match('/(iframe|object|embed)/', $aFormatData['audio']) )
                    {
                        $render .= wp_kses($aFormatData['audio'], array('iframe'=>array('src'=>array()), 'object'=>array('src'=>array()), 'embed'=>array('src'=>array())));
                    }else{
                        $render .= do_shortcode('[audio src="'.esc_url($aFormatData['audio']).'"]');
                    }
                $render .= '</div>';
            }
            break;
        case 'link':
            if ( isset($aFormatData['link']) && !empty($aFormatData['link']) )
            {
                $content = get_the_content($postID);

                if ( $content == '' || is_single($postID) )
                {
                    $link   = $aFormatData['link'];
                    $target = 'target="_blank"';
                }else{
                    $target = '';
                }

                $render .= '<div class="post-link"><div class="tb"><div class="tb-cell"><i class="fa fa-link"></i>';
                    $render .= '<h2><a '.$target.' href="'.esc_url($link).'">'.$title.'</a></h2>';
                    $render .= '<a '.$target.' href="'.esc_url($link).'">'.$aFormatData['link'].'</a>';
                $render .= '</div></div></div>';

                if ( $aFormatData['link_bg_by'] !='disable'  )
                {
                    $render .= '<div class="images">';
                    if ( $aFormatData['link_bg_by'] == 'featured_image' )
                    {
                        if ( has_post_thumbnail($postID) )
                        {
                            $render .= get_the_post_thumbnail($postID, 'pi-standard');
                        }
                    }else{
                        $render .= '<img src="'.esc_url($aFormatData['linkbg']).'" alt="'.esc_attr($title).'">';
                    }

                    $render .= '</div>';
                }
                if ( current_user_can('edit_theme_options', $postID) )
                {
                    $render .= '<a href="'.get_edit_post_link($postID).'" class="post-edit-link">'.__('Edit This', 'wiloke').'</a>';
                }
            }
            break;
    }

    if ( !empty($render) )
    {
        $render = '<div class="post-media">'.$render.'</div>';
    }

    return $render;
}

/**
 * Aresivel Settings - This is individual settings of this theme
 * Created ninhle - wiloke team
 */

/*Front-end scripts*/
add_action('wp_enqueue_scripts', 'pi_front_end_scripts');
function pi_front_end_scripts()
{
    $typography = pi_render_typography();
    if ( !empty($typography) )
    {
        $yourgooglefont = get_option('pi_fontsrc');
        wp_register_style('pi_yourgooglefont',  $yourgooglefont, array(), '1.0', false);
        wp_enqueue_style('pi_yourgooglefont');

        wp_add_inline_style('pi-style', $typography);
    }

    /*Css*/
    wp_register_style('pi-fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css', array(), '4.3');
    wp_register_style('pi-googlefont', 'http://fonts.googleapis.com/css?family=Lora:400,400italic%7CMontserrat:400,700', array(), '4.3');
    wp_register_style('pi-justifiedGallery', PI_FE_CSS_URI . 'lib/justifiedGallery.min.css', array(), '3.5.1');
    wp_register_style('pi-magnific', PI_FE_CSS_URI . 'lib/magnific-popup.css', array(), '1.0');
    wp_register_style('pi-owlcarousel', PI_FE_CSS_URI . 'lib/owl.carousel.css', array(), '1.18');
    wp_register_style('pi-main', PI_FE_CSS_URI . 'style.css', array(), '1.0');
    wp_register_style('pi-aresivel', get_stylesheet_uri(), array(), '1.0');

    wp_enqueue_style('pi-fontawesome');
    wp_enqueue_style('pi-googlefont');
    wp_enqueue_style('pi-justifiedGallery');
    wp_enqueue_style('pi-magnific');
    wp_enqueue_style('pi-owlcarousel');
    wp_enqueue_style('pi-main');
    wp_enqueue_style('pi-aresivel');

    /*Js*/
    $detectscript = WP_DEBUG ? 'scripts.min.js' : 'scripts.js';
    wp_register_script('pi-plugins', PI_FE_JS_URI . 'lib/plugins.min.js', array('jquery'), '1.0', true);
    wp_register_script('pi-main', PI_FE_JS_URI . $detectscript, array('jquery'), '1.0', true);


    if ( is_singular() ) {
        wp_enqueue_script( "comment-reply" );
    }
    wp_enqueue_script('pi-plugins');
    wp_enqueue_script('pi-main');


    if ( pi_lt_ie9() )
    {
        wp_register_script('pi-html5shim', 'http://html5shim.googlecode.com/svn/trunk/html5.js', array(), '1.0');
        wp_register_script('pi-css3mediaqueries', 'http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js', array(), '1.0');

        wp_enqueue_script('pi-html5shim');
        wp_enqueue_script('pi-css3mediaqueries');
    }
}


/*get comments number*/
function pi_get_comments_number($postID)
{
    $num = get_comments_number($postID);

    if ( $num == 0 )
    {
        return __('No Comment', 'wiloke');
    }elseif($num == 1)
    {
        return __('01 Comment', 'wiloke');
    }elseif($num < 10){
        return 0 . $num . __(' Comments', 'wiloke');
    }else{
        return $num . __(' Comments', 'wiloke');
    }
}


/*Create a sidebar*/
add_action('widgets_init', 'pi_register_sidebar');
function pi_register_sidebar()
{
    register_sidebar(
        array(
            'name'          => __('Sidebar', 'wiloke'),
            'description'   => __('Displaying your sidebar', 'wiloke'),
            'id'            => 'pi_sidebar',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>'
        )
    );
    register_sidebar(
        array(
            'name'          => __('Footer1', 'wiloke'),
            'id'            => 'pi_footer1',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>'
        )
    );
    register_sidebar(
        array(
            'name'          => __('Footer2', 'wiloke'),
            'id'            => 'pi_footer2',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>'
        )
    );
    register_sidebar(
        array(
            'name'          => __('Footer3', 'wiloke'),
            'id'            => 'pi_footer3',
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h4 class="widget-title">',
            'after_title'   => '</h4>'
        )
    );
}

/* Register menu */
add_action('init', 'pi_register_nav_menu');
function pi_register_nav_menu()
{
    register_nav_menus( array('pi_menu' => 'Aresivel Menu') );
}

function pi_sanitize_data($data)
{
   return $data;
}

/**
 * The list of categories
 */
function pi_get_list_of_categories($postID)
{
    $aCats = get_the_category($postID);
    $list  = '';

    if ( !empty($aCats) )
    {
        foreach ( $aCats as $cat )
        {
            $list .="<li><a href='".get_category_link( $cat->term_id )."'>".esc_html($cat->name)."</a></li>";
        }
    }

    if ( !empty($list) )
    {
        $list = '<ul>'.$list.'</ul>';
    }

    return $list;
}

/* Logo */
add_action('pi_before_logo', 'pi_open_logo_wrapper');
add_action('pi_after_logo', 'pi_close_logo_wrapper');

function pi_open_logo_wrapper()
{
    echo '<div class="header-logo text-center">';
}

function pi_close_logo_wrapper()
{
    echo '</div>';
}


/*Slug title. If this wordpress version is smaller than 4.1*/
if ( ! function_exists( '_wp_render_title_tag' ) ) {
    function pi_slug_render_title() {
?>
<title><?php wp_title( '|', true, 'right' ); ?></title>
<?php
    }
    add_action( 'wp_head', 'pi_slug_render_title' );
}


function pi_wp_title( $title, $sep ) 
{
    if ( !function_exists('_wp_render_title_tag') )
    {
        global $paged, $page;
        if ( is_feed() )
            return $title;

        // Add the site name.
        $title .= get_bloginfo( 'name' );

        // Add the site description for the home/front page.
        $site_description = get_bloginfo( 'description', 'display' );
        if ( $site_description && ( is_home() || is_front_page() ) )
            $title = "$title $sep $site_description";

        // Add a page number if necessary.
        if ( $paged >= 2 || $page >= 2 )
            $title = "$title $sep " . sprintf( __( 'Page %s', 'wiloke'), max( $paged, $page ) );
    }
    return $title;
}
add_filter( 'wp_title', 'pi_wp_title', 10, 2 );


/*Comment*/
if ( ! function_exists( 'pi_comment' ) )
{
    function pi_comment( $comment, $args, $depth )
    {
        $GLOBALS['comment'] = $comment;
        switch ( $comment->comment_type ) :
            case 'pingback' :
            case 'trackback' :
                // Display trackbacks differently than normal comments.
                ?>
                <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
                <p><?php _e( 'Pingback:', 'wiloke' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'wiloke' ), '<span class="edit-link">', '</span>' ); ?></p>
                <?php
                break;
            default :
                // Proceed with normal comments.
                global $post;
                ?>
                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
                    <div id="comment-<?php comment_ID(); ?>" class="comment-box">
                        <div class="comment-author">
                            <?php
                            $image = '';
                            $commentID    = get_comment_ID();
                            $authorInfo   = get_comment($commentID);

                            if (  isset($authorInfo->user_id) )
                            {
                                $userData = get_user_meta($authorInfo->user_id);
                                if ( isset($userData['pi_user_info'][0]) )
                                {
                                    $aAuthorData = unserialize($userData['pi_user_info'][0]);
                                    $image = isset($aAuthorData['avatar']) && !empty($aAuthorData['avatar']) ? $aAuthorData['avatar'] : '';
                                    if ( !empty($image) )
                                    {
                                        echo '<img src="'.esc_url($image).'" class="pi-comment-avatar"  alt="'. esc_attr( $userData['nickname'][0] ) .'">';
                                    }
                                }
                            }
                            if ( empty($image) )
                            {
                                echo get_avatar( $comment, 100 );
                            }
                            ?>
                        </div><!-- .comment-meta -->
                        <div class="comment-body">
                            <?php
                            printf( '<cite class="fn text-uppercase">%1$s</cite>',get_comment_author_link());
                            ?>
                            <?php
                            printf( '<div class="comment-meta"><span>%1$s</span></div>',
                                /* translators: 1: date, 2: time */
                                sprintf( __( '%1$s at %2$s', 'wiloke' ), get_comment_date(), get_comment_time() )
                            );
                            ?>


                            <?php if ( '0' == $comment->comment_approved ) : ?>
                                <p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'wiloke' ); ?></p>
                            <?php endif; ?>

                            <?php comment_text(); ?>

                            <div class="comment-edit-reply">
                                <?php edit_comment_link( __( 'Edit', 'wiloke' ), '', '' ); ?>
                                <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'wiloke' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                            </div><!-- .reply -->
                        </div>
                    </div><!-- #comment-## -->
                <?php
                break;
        endswitch; // end comment_type check
    }
}
if ( ! function_exists( 'pi_comment_nav' ) )
{
    /**
     * Display navigation to next/previous comments when applicable.
     *
     */
    function pi_comment_nav() {
        // Are there comments to navigate through?
        if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
            ?>
            <nav class="navigation comment-navigation" role="navigation">
                <h2 class="screen-reader-text"><?php _e( 'Comment navigation', 'wiloke' ); ?></h2>
                <div class="nav-links">
                    <?php
                    if ( $prev_link = get_previous_comments_link( __( 'Older Comments', 'wiloke' ) ) ) :
                        printf( '<div class="nav-previous">%s</div>', $prev_link );
                    endif;

                    if ( $next_link = get_next_comments_link( __( 'Newer Comments', 'wiloke' ) ) ) :
                        printf( '<div class="nav-next">%s</div>', $next_link );
                    endif;
                    ?>
                </div><!-- .nav-links -->
            </nav><!-- .comment-navigation -->
        <?php
        endif;
    }
}

/* Follow */
add_filter('pi_filter_panel_of_follow', 'pi_change_the_panel_of_follow', 10, 1);
add_filter('pi_filter_follow_wrapper_class', 'pi_change_follow_wrapper_class', 10, 1);
add_filter('pi_filter_render_follow', 'pi_change_render_follow_structure', 10, 1);

function pi_change_the_panel_of_follow($panel)
{
    return 'pi_basic_settings_panel';
}

function pi_change_follow_wrapper_class($class)
{
    return 'page-social';
}

function pi_change_render_follow_structure($follow)
{
    $newStructure = "";
    $newStructure .= '<div class="tb"><span class="page-social-close">&times;</span><div class="tb-cell">';
    $newStructure .= $follow;
    $newStructure .= '</div></div>';

    return $newStructure;
}

/*Filter sharingbox wrapper*/
add_filter('pi_filter_before_sharingbox_wrapper', 'pi_change_before_sharingbox_wrapper', 10, 1);
function pi_change_before_sharingbox_wrapper($div)
{
    return '<div class="post-social tb-cell">';
}

/*Remove readmore button*/
add_filter('pi_more_link', 'pi_remove_readmore_button', 10, 1);
function pi_remove_readmore_button($button)
{
    return;
}

/*Filter Next & Prev post link*/
add_filter('next_posts_link_attributes', 'pi_add_a_class_to_next_posts_link', 10, 1);
add_filter('previous_posts_link_attributes', 'pi_add_a_class_to_previous_posts_link', 10, 1);

function pi_add_a_class_to_next_posts_link($atts) {
    return 'class="old-post"';
}

function pi_add_a_class_to_previous_posts_link($atts) {
    return 'class="new-post"';
}

/*Excerpt*/
function pi_excerpt_more( $more ) {
    return '';
}
add_filter('excerpt_more', 'pi_excerpt_more', 10, 1);

/*Filter image wrapper in the post media*/
add_filter('pi_filter_wrapper_image_in_the_post_media', 'pi_wrapper_or_no', 10, 3);
function pi_wrapper_or_no($img, $link, $postID)
{
    if ( !is_single($postID) )
    {
        $img = '<a href="'.esc_url($link).'">'.$img.'</a>';
    }

    return $img;
}

/*Removing Some Customizers*/
add_action( 'customize_register', 'pi_remove_customizer' );

function pi_remove_customizer($wp_customize)
{
    // Remove Sections
    $wp_customize->remove_section( 'title_tagline');
    $wp_customize->remove_section( 'nav');
    $wp_customize->remove_section( 'static_front_page');
    $wp_customize->remove_section( 'colors');
    $wp_customize->remove_section( 'background_image');
}


/**
 * Hook into Customize
 */
add_action('init', 'pi_hook_into_customize');

function pi_hook_into_customize()
{
    $isCustomize = has_action('customize_controls_init') ? true : false;
    if ( isset(piConfigs::$aConfigs['configs']['hooks']) && !empty(piConfigs::$aConfigs['configs']['hooks']) )
    {
        foreach ( piConfigs::$aConfigs['configs']['hooks'] as $section => $aFolder )
        {
            foreach ( $aFolder as $folder )
            {
                if ( $isCustomize )
                {
                    include PI_PATH . 'wiloke/'.$section.'/'.$folder.'/setting.php';
                }
                include PI_PATH . 'wiloke/'.$section.'/'.$folder.'/view.php';
            }
        }
    }
}

/**
 * Enqueue script for custom customize control.
 */
function pi_custom_customize_enqueue()
{
    wp_enqueue_style( 'custom-customize', PI_URI . 'wiloke/source/css/customize.css', array(), false );
}
add_action( 'customize_controls_enqueue_scripts', 'pi_custom_customize_enqueue' );

/** Add Facebook Tags **/
if ( !function_exists('pi_facebook_tags') )
{
    add_action('wp_head', 'pi_facebook_tags');

    function pi_facebook_tags()
    {
      
      if ( is_single() )
      {
        global $post;

        if ( has_post_thumbnail($post->ID) ) :
         
        ?>
        <meta property="og:image" content="<?php echo esc_url(wp_get_attachment_url(get_post_thumbnail_id($post->ID))); ?>"/>
        <?php
        endif;
        ?>
        <meta property="og:site_name" content="<?php echo esc_attr(get_option('blogname')); ?>"/>
        <meta property="og:type" content="blog"/>
        <meta property="og:title" content="<?php echo esc_attr(get_the_title($post->ID)); ?>"/>
        <meta property="og:description" content="<?php echo esc_attr(pi_cut_string($post->post_content, 100)); ?>"/>
        <?php
      }
    }

    if ( !function_exists('pi_cut_string') )
    {
      function pi_cut_string($content, $length = 100)
      {
        $content  = strip_tags($content);
        $content  = substr($content, 0, $length);
        $lpos     = strripos($content, ' ');
        $content  = substr($content, 0, $lpos);

        return $content;
      }
    }
}