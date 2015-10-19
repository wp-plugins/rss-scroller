<?php
/*
Plugin Name: RSS scroller
Description: This plug-in will display RSS feed with simple scroller or ticker. It gradually reveals each item into view from left to right.
Author: Gopi Ramasamy
Version: 6.8
Plugin URI: http://www.gopiplus.com/work/2010/07/18/rss-scroller/
Author URI: http://www.gopiplus.com/work/2010/07/18/rss-scroller/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: rss-scroller
Domain Path: /languages
*/

function rss_scr_show()
{
	$rss_scr = "";
	$rss_scr_width 	= get_option('rss_scr_width');
	$rss_scr_height = get_option('rss_scr_height');
	$rss_scr_delay 	= get_option('rss_scr_delay');
	$rss_scr_speed 	= get_option('rss_scr_speed');
	$siteurl 		= get_option('siteurl');
	$rss_scr_num 	= get_option('rss_scr_num');
	$rss_scr_url 	= get_option('rss_scr_url');
	
	if(!is_numeric($rss_scr_delay)){ $rss_scr_delay = 3000;} 
	if(!is_numeric($rss_scr_speed)){ $rss_scr_speed = 5;} 
	if(!is_numeric($rss_scr_num)){ $rss_scr_num 	= 5;} 
	
	if(!is_numeric($rss_scr_width))
	{ 
		$rss_scr_width = "";
	}
	else 
	{
		$rss_scr_width = "width:".$rss_scr_width."px;";
	}
	
	if(!is_numeric($rss_scr_height))
	{ 
		$rss_scr_height = "";
	}
	else 
	{
		$rss_scr_height = "height:".$rss_scr_height."px;";
	}
	
	$cnt=0;
	$rss_scr = "";
	//$content = @file_get_contents($rss_scr_url);
	//if (strpos($http_response_header[0], "200")) 
	//{
		$maxitems = 0;
		include_once( ABSPATH . WPINC . '/feed.php' );
		$rss = fetch_feed( $rss_scr_url );
		if ( ! is_wp_error( $rss ) )
		{
			$cnt = 0;
			$maxitems = $rss->get_item_quantity( $rss_scr_num ); 
			$rss_items = $rss->get_items( 0, $maxitems );
			if ( $maxitems > 0 )
			{
				foreach ( $rss_items as $item )
				{
					$link = $item->get_permalink();
					$text = $item->get_title();
					$content = '<a target="_blank" href="'.$link.'" title="'.esc_sql( $text ).'">'.esc_sql( $text ).'</a>';
					$rss_scr = $rss_scr . "rss_scr_contents[$cnt]='$content';";
					$cnt++;
				}
				?>
				<div style="padding-top:5px;"> <span id="rss_scr_spancontant" style="position:absolute;<?php echo $rss_scr_width.$rss_scr_height; ?>"></span> </div>
				<script src="<?php echo $siteurl; ?>/wp-content/plugins/rss-scroller/rss-scroller.js" type="text/javascript"></script>
				<script type="text/javascript">
					var rss_scr_contents=new Array()
					<?php echo $rss_scr; ?>
					var rss_scr_delay=<?php echo $rss_scr_delay; ?> 
					var rss_scr_speed=<?php echo $rss_scr_speed; ?> 
					rss_scr_start();
					</script>
				<?php
			}
			else
			{
				_e('Invalid or Broken rss link.', 'rss-scroller');
			}
		}
		else
		{
			_e('Invalid or Broken rss link.', 'rss-scroller');
		}
	//}
	//else
	//{
	//	_e('Invalid or Broken rss link.', 'rss-scroller');
	//}
}

add_shortcode( 'rss-scroller', 'rss_scr_shortcode' );

function rss_scr_shortcode( $atts ) 
{
	global $wpdb;

	//[rss-scroller url="url1" width="200" height="60" delay="3000" speed="5"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$url = $atts['url'];
	$rss_scr_width = $atts['width'];
	$rss_scr_height = $atts['height'];
	$rss_scr_delay = $atts['delay'];
	$rss_scr_speed = $atts['speed'];
	
	$siteurl = get_option('siteurl');
	$rss_scr_num = get_option('rss_scr_num');
	
	$rss_scr_url = get_option('rss_scr_url');
	
	if( $url == "url1" ){ $rss_scr_url = get_option('rss_scr_url'); }
	elseif( $url == "url2" ){ $rss_scr_url = get_option('rss_scr_url2'); }
	elseif( $url == "url3" ){ $rss_scr_url = get_option('rss_scr_url3'); }
	else { $rss_scr_url = get_option('rss_scr_url');  }
	
	if(!is_numeric($rss_scr_delay)){ $rss_scr_delay = 3000;} 
	if(!is_numeric($rss_scr_speed)){ $rss_scr_speed = 5;} 
	if(!is_numeric($rss_scr_num)){ $rss_scr_num = 5;} 
	
	if(!is_numeric($rss_scr_width))
	{ 
		$rss_scr_width = "";
	}
	else 
	{
		$rss_scr_width = "width:".$rss_scr_width."px;";
	}
	
	if(!is_numeric($rss_scr_height))
	{ 
		$rss_scr_height = "";
	}
	else 
	{
		$rss_scr_height = "height:".$rss_scr_height."px;";
	}
	
	$cnt=0;
	$xml = "";
	$rss = "";
	$rss_scr = "";
	$rss_contents = "";
	//$content = @file_get_contents($rss_scr_url);
	//if (strpos($http_response_header[0], "200")) 
	//{
		$maxitems = 0;
		include_once( ABSPATH . WPINC . '/feed.php' );
		$rss = fetch_feed( $rss_scr_url );
		if ( ! is_wp_error( $rss ) )
		{
			$cnt = 0;
			$maxitems = $rss->get_item_quantity( $rss_scr_num ); 
			$rss_items = $rss->get_items( 0, $maxitems );
			if ( $maxitems > 0 )
			{
				foreach ( $rss_items as $item )
				{
					$link = $item->get_permalink();
					$text = $item->get_title();
					$content = '<a target="_blank" href="'.$link.'" title="'.esc_sql( $text ).'">'.esc_sql( $text ).'</a>';
					$rss_contents = $rss_contents . "rss_scr_contents[$cnt]='$content';";
					$cnt++;
				}
				$rss_scr = $rss_scr .'<div style="padding-top:5px;"> <span id="rss_scr_spancontant" style="position:absolute;'.$rss_scr_width.$rss_scr_height.'"></span> </div>';
				$rss_scr = $rss_scr .'<script src="'.$siteurl.'/wp-content/plugins/rss-scroller/rss-scroller.js" type="text/javascript"></script>';
				$rss_scr = $rss_scr .'<script type="text/javascript">';
				$rss_scr = $rss_scr .'var rss_scr_contents=new Array(); ';
				$rss_scr = $rss_scr . $rss_contents;
				$rss_scr = $rss_scr .'var rss_scr_delay='.$rss_scr_delay.'; ';
				$rss_scr = $rss_scr .'var rss_scr_speed='.$rss_scr_speed.'; '; 
				$rss_scr = $rss_scr .'rss_scr_start();';
				$rss_scr = $rss_scr .'</script>';
			}
			else
			{
				$rss_scr = __('Invalid or Broken rss link.', 'rss-scroller');
			}
		}
		else
		{
			$rss_scr = __('Invalid or Broken rss link.', 'rss-scroller');
		}

	//}
	//else
	//{
	//	$rss_scr = __('Invalid or Broken rss link.', 'rss-scroller');
	//}
	return $rss_scr;
}

function rss_scr_install() 
{
	add_option('rss_scr_title', "rss scroller");
	add_option('rss_scr_width', '175');
	add_option('rss_scr_height', '60');
	add_option('rss_scr_delay', '5000');
	add_option('rss_scr_speed', '5');
	add_option('rss_scr_num', '10');
	add_option('rss_scr_url', 'http://www.gopiplus.com/work/category/word-press-plug-in/feed/');
	add_option('rss_scr_url2', 'http://www.gopiplus.com/work/category/word-press-plug-in/feed/');
	add_option('rss_scr_url3', 'http://www.gopiplus.com/work/category/word-press-plug-in/feed/');
}

function rss_scr_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('rss_scr_title');
	echo $after_title;
	rss_scr_show();
	echo $after_widget;
}
	
function rss_scr_control() 
{
	echo '<p><b>';
	_e('RSS scroller', 'rss-scroller');
	echo '.</b> ';
	_e('Check official website for more information', 'rss-scroller');
	?> <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/rss-scroller/"><?php _e('click here', 'rss-scroller'); ?></a></p><?php
}

function rss_scr_admin() 
{
	?>
	<div class="wrap">
	  <div class="form-wrap">
		<div id="icon-edit" class="icon32 icon32-posts-post"><br>
		</div>
		<h2><?php _e('RSS scroller', 'rss-scroller'); ?></h2>	
		<?php
		$rss_scr_title = get_option('rss_scr_title');
		$rss_scr_width = get_option('rss_scr_width');
		$rss_scr_height = get_option('rss_scr_height');
		$rss_scr_delay = get_option('rss_scr_delay');
		$rss_scr_speed = get_option('rss_scr_speed');
		$rss_scr_num = get_option('rss_scr_num');
		$rss_scr_url = get_option('rss_scr_url');
		$rss_scr_url2 = get_option('rss_scr_url2');
		$rss_scr_url3 = get_option('rss_scr_url3');
			
		if (isset($_POST['rss_scr_form_submit']) && $_POST['rss_scr_form_submit'] == 'yes')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('rss_scr_form_setting');
		
			$rss_scr_title = stripslashes($_POST['rss_scr_title']);
			$rss_scr_width = stripslashes($_POST['rss_scr_width']);
			$rss_scr_height = stripslashes($_POST['rss_scr_height']);
			$rss_scr_delay = stripslashes($_POST['rss_scr_delay']);
			$rss_scr_speed = stripslashes($_POST['rss_scr_speed']);
			$rss_scr_num = stripslashes($_POST['rss_scr_num']);
			$rss_scr_url = stripslashes($_POST['rss_scr_url']);
			$rss_scr_url2 = stripslashes($_POST['rss_scr_url2']);
			$rss_scr_url3 = stripslashes($_POST['rss_scr_url3']);
			
			update_option('rss_scr_title', $rss_scr_title );
			update_option('rss_scr_width', $rss_scr_width );
			update_option('rss_scr_height', $rss_scr_height );
			update_option('rss_scr_delay', $rss_scr_delay );
			update_option('rss_scr_speed', $rss_scr_speed );
			update_option('rss_scr_num', $rss_scr_num );
			update_option('rss_scr_url', $rss_scr_url );
			update_option('rss_scr_url2', $rss_scr_url2 );
			update_option('rss_scr_url3', $rss_scr_url3 );
			
			?>
			<div class="updated fade">
				<p><strong><?php _e('Details successfully updated.', 'rss-scroller'); ?></strong></p>
			</div>
			<?php
		}
		?>
		<form name="rss_scr_form" method="post" action="">
			<h3><?php _e('Plugin management', 'rss-scroller'); ?></h3>
			
			<label for="tag-width"><?php _e('Title (For widget)', 'rss-scroller'); ?></label>
			<input name="rss_scr_title" type="text" value="<?php echo $rss_scr_title; ?>"  id="rss_scr_title" size="50" maxlength="100">
			<p><?php _e('Please enter your widget title.', 'rss-scroller'); ?></p>
			
			<label for="tag-width"><?php _e('Scroller speed', 'rss-scroller'); ?></label>
			<input name="rss_scr_speed" type="text" value="<?php echo $rss_scr_speed; ?>"  id="rss_scr_speed" maxlength="5" />
			<p><?php _e('Please enter your scroller speed, this option only for widget.', 'rss-scroller'); ?> (Example: 5)</p>
			
			<label for="tag-width"><?php _e('Scroller delay', 'rss-scroller'); ?></label>
			<input name="rss_scr_delay" type="text" value="<?php echo $rss_scr_delay; ?>"  id="rss_scr_delay" maxlength="5" />
			<p><?php _e('Please enter your scroller delay, this option only for widget.', 'rss-scroller'); ?> (Example: 5000)</p>
			
			<label for="tag-width"><?php _e('Width', 'rss-scroller'); ?></label>
			<input name="rss_scr_width" type="text" value="<?php echo $rss_scr_width; ?>"  id="rss_scr_width" maxlength="5" />
			<p><?php _e('Please enter your width, this option only for widget.', 'rss-scroller'); ?> (Example: 300)</p>
			
			<label for="tag-width"><?php _e('Height', 'rss-scroller'); ?></label>
			<input name="rss_scr_height" type="text" value="<?php echo $rss_scr_height; ?>"  id="rss_scr_height" maxlength="5">
			<p><?php _e('Please enter your height, this option only for widget.', 'rss-scroller'); ?> (Example: 200)</p>
			
			<label for="tag-width"><?php _e('No of items to display', 'rss-scroller'); ?></label>
			<input name="rss_scr_num" type="text" value="<?php echo $rss_scr_num; ?>"  id="rss_scr_num" maxlength="3" />
			<p><?php _e('Please enter number of items to display from rss feed.', 'rss-scroller'); ?> (Example: 10)</p>
			
			<label for="tag-width"><?php _e('RSS URL 1 (This rss link is default for widget).', 'rss-scroller'); ?></label>
			<input name="rss_scr_url" type="text" value="<?php echo $rss_scr_url; ?>"  id="rss_scr_url" size="90" />
			<p><?php _e('Please enter your rss link.', 'rss-scroller'); ?> (url1)</p>
			
			<label for="tag-width"><?php _e('RSS URL 2', 'rss-scroller'); ?></label>
			<input name="rss_scr_url2" type="text" value="<?php echo $rss_scr_url2; ?>"  id="rss_scr_url2" size="90" />
			<p><?php _e('Please enter your rss link.', 'rss-scroller'); ?> (url2)</p>
			
			<label for="tag-width"><?php _e('RSS URL 3', 'rss-scroller'); ?></label>
			<input name="rss_scr_url3" type="text" value="<?php echo $rss_scr_url3; ?>"  id="rss_scr_url3" size="90" />
			<p><?php _e('Please enter your rss link', 'rss-scroller'); ?> (url3)</p>
			
			<input type="hidden" name="rss_scr_form_submit" value="yes"/>
			<p class="submit">
			<input name="hsa_submit" id="hsa_submit" class="button" value="<?php _e('Submit', 'rss-scroller'); ?>" type="submit" />
			<a class="button" target="_blank" href="http://www.gopiplus.com/work/2010/07/18/rss-scroller/"><?php _e('Help', 'rss-scroller'); ?></a>
			</p>
			<?php wp_nonce_field('rss_scr_form_setting'); ?>
		</form>
		</div>
		<h3><?php _e('Plugin configuration option', 'rss-scroller'); ?></h3>
		<ol>
			<li><?php _e('Add the plugin in the posts or pages using short code.', 'rss-scroller'); ?></li>
			<li><?php _e('Add directly in to the theme using PHP code.', 'rss-scroller'); ?></li>
			<li><?php _e('Drag and drop the widget to your sidebar.', 'rss-scroller'); ?></li>
		</ol>
	  <p class="description"><?php _e('Check official website for more information', 'rss-scroller'); ?> 
	  <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/rss-scroller/"><?php _e('click here', 'rss-scroller'); ?></a></p>
	</div>
	<?php
}

function rss_scr_widget_init() 
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget( __('RSS scroller', 'rss-scroller'), __('RSS scroller', 'rss-scroller'), 'rss_scr_widget');
	}
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control( __('RSS scroller', 'rss-scroller'), array( __('RSS scroller', 'rss-scroller'), 'widgets'), 'rss_scr_control');
	} 
}

function rss_scr_deactivation() 
{
	// No action required.
}

function rss_scr_add_to_menu() 
{
	add_options_page( __('RSS scroller', 'rss-scroller'), __('RSS scroller', 'rss-scroller'), 'manage_options', 'rss-scroller', 'rss_scr_admin' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'rss_scr_add_to_menu');
}

function rss_scr_textdomain() 
{
	  load_plugin_textdomain( 'rss-scroller', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'rss_scr_textdomain');
add_action("plugins_loaded", "rss_scr_widget_init");
register_activation_hook(__FILE__, 'rss_scr_install');
register_deactivation_hook(__FILE__, 'rss_scr_deactivation');
add_action('init', 'rss_scr_widget_init');
?>