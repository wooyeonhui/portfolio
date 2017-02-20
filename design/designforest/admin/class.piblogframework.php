<?php

/**
 * Created by wiloke team. 
 * Date: june/17/2015
 * This file contains core functions of the framework
 */

if ( !defined('ABSPATH') )
{
	die();
}

define ('PI_C_PATH', PI_A_PATH . "customize/");
define ('PI_SOURCE_URI', PI_A_URI . "source/");

class piBlogFramework
{
    static $piOptions = array();
    static $piFeaturedPostsId  = array();
    static $aCurrentLang = "";

	public function __construct()
	{
        $this->pi_refresh();
		$this->pi_plugins_init();

        add_action('after_switch_theme', array($this, 'pi_update_option_defaults'));
        add_action('admin_enqueue_scripts', array($this, 'pi_admin_enqueue_scripts'));

        add_action('before_delete_post', array($this, 'pi_clean_db'));
	}

    /**
     * Enqueue js into admin
     * @since 1.0
     */
    public  function pi_admin_enqueue_scripts()
    {
        wp_register_script('pi_global', PI_SOURCE_URI . 'js/pi.global.js', array('jquery'), '1.0', true);
        wp_register_style('pi_global', PI_SOURCE_URI . 'css/pi.global.css', array(), '1.0', false);

        wp_enqueue_script('pi_global');
        wp_enqueue_style('pi_global');
    }

    /**
     * Get the slug of template page
     * @since 1.0
     */
    static function pi_get_page_template_slug($postID="")
    {
        $aTemplate = false;

        if ( has_action('customize_controls_init') )
        {
            $httpRefere = $_SERVER['HTTP_REFERER'];
            if ( isset($httpRefere) && !empty($httpRefere) )
            {
                $httpRefere = esc_url_raw($httpRefere);
                $httpRefere = urldecode($httpRefere);

                if ( preg_match('/(?:post=)([0-9]*)&/', $httpRefere, $match) )
                {
                    $postID = (int)$match[1];
                }
            }
        }

        if ( !empty($postID) )
        {
            $aTemplate = get_page_template_slug($postID);
            $aTemplate = array("id"=>$postID, "template"=>$aTemplate);
        }

        return $aTemplate;
    }

    /**
     * Parse Option
     * @since 1.0
     */
    static function pi_parse_option_key($aTemplate)
    {
        if ( isset($aTemplate['template']) && $aTemplate['template'] === 'page-template.php' )
        {
            $optionKey = "pi_options".self::$aCurrentLang.'_page_template_'.$aTemplate['id'];
        }else{
            $optionKey = "pi_options".self::$aCurrentLang;
        }

        return $optionKey;
    }

    /**
     * After delete page template, the option of that page will be deleted
     */
    public function pi_clean_db($postID)
    {
        if ( get_page_template_slug($postID) == 'page-template.php' )
        {
            $optionKey = "pi_options".self::$aCurrentLang.'_page_template_'.$postID;
            delete_option($optionKey);
        }
    }


    /**
     * Update the options default in the first time
     * @since 1.0
     */
    public function pi_update_option_defaults()
    {
        if ( !get_option('pi_options') )
        {
            update_option('pi_options', piConfigs::$aConfigs['pi_options']);
            update_option("pi_latest_options", piConfigs::$aConfigs['pi_options']);
        }
    }

    /**
     * Get Options from database
     * @since 1.0
     */
    public function pi_refresh()
    {

        $special = false;

        $aTemplate = self::pi_get_page_template_slug();

        if ( defined('ICL_LANGUAGE_CODE') )
        {
            self::$aCurrentLang = '_' . ICL_LANGUAGE_CODE;
            $special = true;
        }

        if ( $special || $aTemplate )
        {
            $optionKey = self::pi_parse_option_key($aTemplate);

            $aOptionsOfLang = get_option($optionKey);

            if ( !$aOptionsOfLang )
            {
                self::$piOptions = get_option("pi_options");
                update_option($optionKey, self::$piOptions);
            }else{
                if ( has_action('customize_controls_init') )
                {
                    update_option('pi_options', $aOptionsOfLang);
                }
                self::$piOptions =  $aOptionsOfLang;
            }
        }else{
            self::$piOptions = get_option("pi_latest_options");
            if ( has_action('customize_controls_init') )
            {
                update_option('pi_options', self::$piOptions);
            }
        }

        if ( empty(self::$piOptions) )
        {
            self::$piOptions = piConfigs::$aConfigs['pi_options'];
        }else{
            self::$piOptions = array_replace_recursive(piConfigs::$aConfigs['pi_options'], self::$piOptions);
        }
    }

	public function pi_plugins_init()
	{
		/**
		 * This file below contains all off settings of wiloke customize
		 */
		require_once PI_C_PATH . 'class.piblogcustomize.php';
        new piBlogCustomize();

        /**
         * Extend Wordpress Customize
         */
        require_once PI_C_PATH . 'piextendcustomizer.php';
	}

    /**
     * Sanitize data
     * @since 1.0
     */
    public function pi_sanitize_data($data)
    {
        $data = is_array($data) ? array_map( array($this, 'pi_sanitize_data'), $data ) : wp_unslash($data);
        return $data;
    }

    /**
     * Categories
     * @since 1.0
     */
    static function pi_get_categories()
    {
        $categories = get_categories(array('type'=>'post', 'hide_empty'=>1));
        $piCats = array(-1=>'----');
        foreach ($categories as $category)
        {
            $piCats[$category->term_id] = $category->cat_name;
        }
        return $piCats;
    }
}

new piBlogFramework();