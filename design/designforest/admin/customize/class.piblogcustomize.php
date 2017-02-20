<?php

/**
 * The zone of customizer
 * Created by wiloke team
 * Date 07/11/2015
 */

if ( !defined('ABSPATH') )
{
	die();
}

define('PI_BFP_PATH', get_template_directory() . '/admin/customize/plugins/');

class piBlogCustomize extends piBlogFramework
{
    public $piPanelPriority   = 1; // Order of panel
    public $piSectionPriority = 1; // Order of section
    public $piControlPriority = 1; // Order of Control

    public function __construct()
    {
        add_action('customize_register', array($this, 'pi_customize_register'));
        add_action( 'customize_save_after', array($this, 'pi_update_option'), 99);
        add_action('customize_controls_print_scripts', array($this, 'pi_add_customcode_into_header_in_customize_zone'));
    }

    /**
     * Everything of customize will be registerd here
     * @since 1.0
     */
    public  function  pi_customize_register($wp_customize)
    {
        /**
         * Add panels
         */
        include PI_BFP_PATH . 'panel/pipanel.php';


        /**
         *  Settings
         */
        foreach ( piConfigs::$aConfigs['configs']['settings_views'] as $folder => $aControls )
        {
            foreach ( $aControls as $file )
            {
                do_action('pi_hook_before_'.str_replace("pi", "", $file), $wp_customize, $this->piSectionPriority++, $this->piControlPriority++);
                include PI_BFP_PATH . 'section/'.$folder.'/'.$file.'.php';
                do_action('pi_hook_after_'.str_replace("pi", "", $file), $wp_customize, $this->piSectionPriority++, $this->piControlPriority++);
            }
        }

    }

    /**
     *  Customize color
     *
     */
    public function pi_add_customcode_into_header_in_customize_zone()
    {
        $customColor = pi_get_custom_color();
        if ( $customColor !='default' )
        {
            wp_register_style( 'pi_custom_color', PI_FE_CSS_URI . 'colors/' . $customColor . '.css', array(), '1.0' );
            wp_enqueue_style( 'pi_custom_color' );
        }
    }

    /**
     * Make somethings after customize updated
     * If help you can create multi-layout & wmpl
     * @since 1.0
     */
     public function pi_update_option()
     {
         $httpRefere = $_SERVER['HTTP_REFERER'];
         $httpRefere = esc_url_raw($httpRefere);
         $httpRefere = urldecode($httpRefere);
         $aTemplate  = parent::pi_get_page_template_slug();

         $aOptions   = get_option("pi_options");

         $optionKey  = parent::pi_parse_option_key($aTemplate);

         if ( $optionKey != 'pi_options' )
         {
             $latestOptions = get_option("pi_latest_options");

             $aOldData  = get_option($optionKey);
             $aOldData  = $aOldData ? $aOldData : $latestOptions;
             $aNewData  = array_replace_recursive($aOldData, $aOptions);
             $aNewData  = array_replace_recursive($latestOptions, $aOptions);

             update_option($optionKey, $aNewData);

             if ( !empty($latestOptions) )
             {
                 update_option("pi_options", $latestOptions);
             }
         }else{
             update_option("pi_latest_options", $aOptions);
         }
     }

    /**
     * Is Page Template
     */
    public function pi_is_page_template($control)
    {
        if ( 'pi_posts_per_page' === $control->section )
        {
            $pageTemplate = parent::pi_get_page_template_slug();

            if ( isset($pageTemplate['template']) && $pageTemplate['template'] == 'page-template.php' )
            {
                return true;
            }else{
                return false;
            }
        }
    }

    public function pi_is_not_page_template($control)
    {
        if ( 'pi_posts_per_page' === $control->section )
        {
            $pageTemplate = parent::pi_get_page_template_slug();
            if ( isset($pageTemplate['template']) && $pageTemplate['template'] == 'page-template.php' )
            {
                return false;
            }else{
                return true;
            }
        }
    }

    /**
     * Handle when the brower refrest
     * @param $target the name of option that you want to get
     * @since 1.0
     */
    static function pi_refresh_in_customize($target)
    {
        if ( has_action('customize_controls_init') )
        {
            if ( isset($_POST['customized']) )
            {

                $_POST['customized'] = self::pi_escape_json(stripslashes($_POST['customized']));

                $aParse = json_decode($_POST['customized'], true);

                if ( !$aParse )
                {
                    $aParse = json_decode( stripslashes($_POST['customized']), true);
                }

                if ( isset($aParse["$target"]) )
                {

                    if ( $target == "pi_options[limit_character][limit]" && empty($aParse["$target"]) )
                    {
                        return "empty";
                    }else{
                        $return =  self::pi_convert_to_string($aParse["$target"]);
                        if ( $return === "0" )
                        {
                            return "disable";
                        }
                        return $return;
                    }
                }

                return false;
            }

            return false;
        }

        return false;
    }

    /**
     * Userfull: http://test.wiloke.com/escape-json-string/
     * @since 1.0
     */
    static function pi_escape_json($json)
    {
        $search = array('\\',"\n","\r","\f","\t","\b","'") ;
        $replace = array('\\\\',"\\n", "\\r","\\f","\\t","\\b", "&#039");
        $json = str_replace($search,$replace,$json);
        return $json;
    }

    /**
     * @since 1.0
     */
    static function pi_convert_to_string($string)
    {
        $replace = array('\\',"\n","\r","\f","\t","\b","'") ;
        $search = array('\\\\',"\\n", "\\r","\\f","\\t","\\b", "&#039");
        $string = str_replace($search,$replace,$string);
        return $string;
    }
}
