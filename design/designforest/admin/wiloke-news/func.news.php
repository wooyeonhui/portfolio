<?php
	/*====================================*/
	/* WILOKE - NEWS 					  */
	/*====================================*/

	
	/**
	 * News key and latest alert news: The latest time of alert
	 */
	define('PI_NEWS_KEY', 'pi_wiloke_news');
	define('PI_LATEST_ALERT', 'pi_latest_alert');
	define('PI_WILOKE_SERVER', 'http://wiloke.net/bb/test/store/');
	define('PI_WILOKE_NEWS_URI', get_template_directory_uri() . '/admin/wiloke-news/source/');


	/**
	 * Get Wiloke News
	 * @version 1.0
	 * @author pirates - Wiloke team
	 */

	add_filter( 'cron_schedules', 'pi_add_every_three_minutes');

	add_action('admin_init', 'pi_message');


	/**
 	 * Register Wiloke News
	 */
	add_action('admin_menu', 'pi_register_wiloke_news');

	function pi_register_wiloke_news()
	{	
		$piWilokeNews = get_option(PI_NEWS_KEY);

		if ( !empty($piWilokeNews) )
		{
			add_theme_page('Wiloke News', 'Wiloke News', 'edit_theme_options', 'wiloke-news', 'pi_wiloke_news');
		}
	}

	/**
	 * Displaying Wiloke News
	 */
	function pi_wiloke_news()
	{
		$piWilokeNews = get_option(PI_NEWS_KEY);
		
		?>
		<div id="pi-container" style="width: 80%; margin: 30px auto;">
			<h2><?php echo $piWilokeNews->theme_name;  ?> -- Version: <?php echo $piWilokeNews->version; ?></h2>
			<div class="bs-callout bs-callout-danger" id="callout-navbar-fixed-top-padding">
			   <?php echo $piWilokeNews->alert; ?>
		  	</div>
		</div>
		<?php
	}


	function pi_client_infomation()
	{
		if ( !get_option('pi_client_infomation') )
		{
			$site_url 	  = get_option('siteurl');
			$email 	  	  = get_option('admin_email');
			$blog_name 	  = get_option('blogname');

			$aContent     = array('siteurl'=>$site_url, 'email'=>$email, 'blog_name'=>$blog_name);

			$url = PI_WILOKE_SERVER . 'user-data/';

			wp_remote_post( $url, array(
					'method' 		=> 'POST',
					'timeout' 		=> 45,
					'redirection' 	=> 5,
					'httpversion' 	=> '1.0',
					'blocking' 		=> true,
					'headers'		=> array(),
					'body' 			=> json_encode($aContent),
					'cookies' 		=> array()
			    )
			);

		}
	}

	function pi_message()
	{
		// pi_client_infomation();

		$atTime = get_option('at_time');
		if ( !$atTime )
		{
			$atTime = time();
			update_option('pi_at_time', $atTime);
		}

		wp_schedule_event( $atTime, 'every_three_hours', 'pi_get_message');
	}

	add_action('pi_get_message', 'pi_run_get_message');

	function pi_run_get_message()
	{
	
		$oThemeName   = wp_get_theme();

		$themeName 	  = $oThemeName->get('Name');
		$themeName    = strtolower($themeName);
		$url 		  = PI_WILOKE_SERVER . $themeName . '.json';

		$aResponse = wp_remote_get($url);

		if ( $aResponse['body'] !='' )
		{
			$oResponse = json_decode($aResponse['body']);

			$latestAlert = get_option(PI_LATEST_ALERT);
			
			if ( !$latestAlert || ($latestAlert != $oResponse->created) )
			{
				update_option(PI_NEWS_KEY, $oResponse);
				update_option(PI_LATEST_ALERT, $oResponse->created);
			}

		}
	}

	function pi_add_every_three_minutes( $schedules )
	{
	 	$schedules['every_three_hours'] = array(
	            'interval'  => 1800,
	            'display'   => __( 'Every 3 Hours', 'wiloke' )
	    );
	    
	    return $schedules;
	}

	add_action('admin_enqueue_scripts', 'pi_alert_script');
	function pi_alert_script($hook)
	{
		if ( $hook == 'appearance_page_wiloke-news' )
		{
			wp_enqueue_style('pi_bootstrap', PI_WILOKE_NEWS_URI . 'css/bootstrap.min.css');
			wp_enqueue_style('pi_doc', PI_WILOKE_NEWS_URI . 'css/doc.min.css');
			wp_enqueue_style('pi_alert_style', PI_WILOKE_NEWS_URI . 'css/style.css');
		}
	}

	add_action('widgets_init', 'pi_after_widget_init');
	function pi_after_widget_init()
	{
	    register_widget( 'piTWitterFeed' );
		register_widget( 'piMailchimp' );
	}

