<?php
if ( !defined('ABSPATH') )
{
    die();
}

class piConfigs
{
    static $aConfigs = array(
        'configs'=>array(
            'settings_views'=>array(
                'basic-settings'=>array('picolor', 'pilimitcharacter', 'pipostsperpage', 'pipagination', 'pipreloader', 'pifavicon'),
                'typography'    =>array('pitypography'),
                'logoheader'    =>array('pilogo', 'piheaderbg', 'piheadercss', 'piheaderjs', 'piheadercode'),
                'featured-posts'=>array('pifeaturedposts'),
                'content'       =>array('piarchive', 'pipost', 'pipage'),
                'footer'        =>array('picopyright', 'pifooterjs', 'pifootercode'),
                'rest'          =>array('pifollow')
            ),
            'hooks'=>array(
                'logoheader'=>array('pisearch'),
                'content'   =>array('pilayout'),
                'sidebar'   =>array('pilayout'),
                'footer'    =>array('pifooterbg')
            ),
            'basic_settings'=>array(
                'custom_color'=>array(
                    'colors'=>array('default'=>'Default', 'brown'=>'Brown', 'green'=>'Green', 'pink'=>'Pink', 'red'=>'Red', 'yellow'=>'Yellow'),
                    'colors_dir'    => 'colors/',
                    'pattern_file'  => '',
                    'pattern_color' => ''
                )
            ),
            'typography'=>array(
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p'
            ),
            'rest'=>array(
                'follow'=>array(
                    'facebook'      => array('fa fa-facebook', 'Facebook'),
                    'twitter'       => array('fa fa-twitter', 'Twitter'),
                    'google_plus'   => array('fa fa-google-plus', 'Google+'),
                    'instagram'     => array('fa fa-instagram', 'Instagram'),
                    'vk'            => array('fa fa-vk', 'Vk'),
                    'youtube'       => array('fa fa-youtube-play', 'Youtube'),
                    'vimeo'         => array('fa fa-vimeo-square', 'Vimeo'),
                    'pinterest'     => array('fa fa-pinterest', 'Pinterest'),
                    'dribbble'      => array('fa fa-dribbble', 'Dribbble'),
                    'behance'       => array('fa fa-behance', 'Behance'),
                    'bloglovin'     => array('fa fa-heart', 'Bloglovin')
                )
            ),
            'widget'=>array(
                'items'         => array('piAbout', 'piFollow', 'piPostsListing', 'piMailchimp', 'piContactInfo', 'piFlickrFeed', 'piInstagram', 'piTwitterFeed', 'piAds', 'piFacebookLikebox'),
                'footer'        => array('pi_footer1', 'pi_footer2', 'pi_footer3')
            ),
            'post_formats'=>array('gallery', 'image', 'quote', 'video', 'audio', 'link')
        ),
        'pi_options'=>array(
            'basic_settings'=>array(
                'custom_color'    => 'default',
                'limit_character' => array(
                                        'display' => 'post_excerpt',
                                        'limit'   => 500
                                    ),
                'pagination'     => 'navigation',
                'preloader'      => 0,
                'favicon'        => ''
            ),
            'typography'=>array(
                'h1'=>array(
                    'fonttype'  =>'default',
                    'fontweight'=>'normal'
                ),
                'h2'=>array(
                    'fonttype'  =>'default',
                    'fontweight'=>'normal'
                ),
                'h3'=>array(
                    'fonttype'  =>'default',
                    'fontweight'=>'normal'
                ),
                'h4'=>array(
                    'fonttype'  =>'default',
                    'fontweight'=>'normal'
                ),
                'h5'=>array(
                    'fonttype'  =>'default',
                    'fontweight'=>'normal'
                ),
                'h6'=>array(
                    'fonttype'  =>'default',
                    'fontweight'=>'normal'
                ),
                'p'=>array(
                    'fonttype'  =>'default',
                    'fontweight'=>'normal'
                )
            ),
            'logoheader'=>array(
                'logo'          => '',
                'retina_logo'   => '',
                'header_code'   => '',
                'custom_css'    => '',
                'custom_js'     => '',
                'search'        => 1,
                'headerbg'      => ''
            ),
            'featuredposts'=>array(
               'toggle'                 => 1,
               'type'                   => 'latest',
               'category'               => -1,
               'number_of_posts'        => 4,
               'exclude_featured_posts' => 0,
               'layout'                 => 'fullwidth'
            ),
            'content'  =>array(
                'related_posts'=>array(
                    'toggle'            =>1,
                    'get_by'            =>'category',
                    'number_of_posts'   =>5,
                    'title'             =>'You may also like'
                ),
                'sharing_box_on_post'=>array(
                    'toggle'=>1
                ),
                'sharing_box_on_page'=>array(
                    'toggle'=>1
                ),
                'layout'=>'standard',
                'sidebar_on_page'=>'default',
                'archive_layout'=>'standard',
                'page_template'=>array(
                    'categories'=>array(''=>'')
                )
            ),
            'sidebar_layout'    => 'right-sidebar',
            'footer'   =>array(
                'copyright'     => 'Copyrights © 2015 all rights reserved by Wiloke',
                'footer_code'   => '',
                'custom_js'     => '',
                'footerbg'      => ''
            ),
            'follow'=>array(
                'facebook'      =>'https://www.facebook.com/wilokewp',
                'twitter'       =>'https://twitter.com/wilokethemes',
                'pinterest'     =>'',
                'dribbble'      =>'',
                'google_plus'   =>'',
                'instagram'     =>'',
                'vk'            =>'',
                'behance'       =>'',
                'vimeo'         =>'',
                'youtube'       =>'',
                'behance'       =>'',
                'bloglovin'     =>''
            )
        )
    );
}

?>