<?php

/*
Plugin Name: RSS scroller
Description: This plug-in will display RSS feed with simple scroller or ticker. It gradually reveals each item into view from left to right.
Author: Gopi.R
Version: 6.0
Plugin URI: http://www.gopiplus.com/work/2010/07/18/rss-scroller/
Author URI: http://www.gopiplus.com/work/2010/07/18/rss-scroller/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

function rss_scr_show()
{
	//include_once(ABSPATH.WPINC.'/rss.php');
	$rss_scr = "";
	$rss_scr_width = get_option('rss_scr_width');
	$rss_scr_height = get_option('rss_scr_height');
	$rss_scr_delay = get_option('rss_scr_delay');
	$rss_scr_speed = get_option('rss_scr_speed');
	$siteurl = get_option('siteurl');
	$rss_scr_num = get_option('rss_scr_num');
	$rss_scr_url = get_option('rss_scr_url');
	
	if(!is_numeric($rss_scr_delay)){ $rss_scr_delay = 3000;} 
	if(!is_numeric($rss_scr_speed)){ $rss_scr_speed = 5;} 
	if(!is_numeric($rss_scr_num)){ $rss_scr_num = 5;} 
	
	if(!is_numeric($rss_scr_width)){ 
		$rss_scr_width = "";
	}
	else {
		$rss_scr_width = "width:".$rss_scr_width."px;";
	}
	
	if(!is_numeric($rss_scr_height)){ 
		$rss_scr_height = "";
	}
	else {
		$rss_scr_height = "height:".$rss_scr_height."px;";
	}
	
	$cnt=0;
	$xml = "";
	$f = fopen( $rss_scr_url, 'r' );
	while( $data = fread( $f, 4096 ) ) { $xml .= $data; }
	fclose( $f );
	preg_match_all( "/\<item\>(.*?)\<\/item\>/s", $xml, $itemblocks );
	foreach( $itemblocks[1] as $block )
	{
		if($cnt==$rss_scr_num)
		{
			break;
		}
		preg_match_all( "/\<title\>(.*?)\<\/title\>/",  $block, $title );
		preg_match_all( "/\<link\>(.*?)\<\/link\>/", $block, $link );
		$content = '<a href="'.$link[1][0].'" title="'.mysql_real_escape_string( $title[1][0] ).'">'.mysql_real_escape_string( $title[1][0] ).'</a>';
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

add_shortcode( 'rss-scroller', 'rss_scr_shortcode' );

function rss_scr_shortcode( $atts ) 
{
	global $wpdb;
	//[RSS-SCROLLER:TYPE=widget]
	//$scode = $matches[1];
	//include_once(ABSPATH.WPINC.'/rss.php');
	
	//[rss-scroller url="url1" width="200" height="60" delay="3000" speed="5"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$url = $atts['url'];
	$width = $atts['width'];
	$height = $atts['height'];
	$delay = $atts['delay'];
	$speed = $atts['speed'];
	
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
	
	if(!is_numeric($rss_scr_width)){ 
		$rss_scr_width = "";
	}
	else {
		$rss_scr_width = "width:".$rss_scr_width."px;";
	}
	
	if(!is_numeric($rss_scr_height)){ 
		$rss_scr_height = "";
	}
	else {
		$rss_scr_height = "height:".$rss_scr_height."px;";
	}
	
	$cnt=0;
	$f = fopen( $rss_scr_url, 'r' );
	while( $data = fread( $f, 4096 ) ) { $xml .= $data; }
	fclose( $f );
	preg_match_all( "/\<item\>(.*?)\<\/item\>/s", $xml, $itemblocks );
	foreach( $itemblocks[1] as $block )
	{
		if($cnt==$rss_scr_num)
		{
			break;
		}
		preg_match_all( "/\<title\>(.*?)\<\/title\>/",  $block, $title );
		preg_match_all( "/\<link\>(.*?)\<\/link\>/", $block, $link );
		$content = '<a href="'.$link[1][0].'" title="'.mysql_real_escape_string( $title[1][0] ).'">'.mysql_real_escape_string( $title[1][0] ).'</a>';
		$rss = $rss . " rss_scr_contents[$cnt]='".$content."'; ";
		$cnt++;
	}

	$rss_scr = $rss_scr .'<div style="padding-top:5px;"> <span id="rss_scr_spancontant" style="position:absolute;'.$rss_scr_width.$rss_scr_height.'"></span> </div>';
	$rss_scr = $rss_scr .'<script src="'.$siteurl.'/wp-content/plugins/rss-scroller/rss-scroller.js" type="text/javascript"></script>';
	$rss_scr = $rss_scr .'<script type="text/javascript">';
	$rss_scr = $rss_scr .'var rss_scr_contents=new Array(); ';
	$rss_scr = $rss_scr . $rss;
	$rss_scr = $rss_scr .'var rss_scr_delay='.$rss_scr_delay.'; ';
	$rss_scr = $rss_scr .'var rss_scr_speed='.$rss_scr_speed.'; '; 
	$rss_scr = $rss_scr .'rss_scr_start();';
	$rss_scr = $rss_scr .'</script>';

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
	echo '<p>To change the setting goto RSS Scroller link under setting menu.';
	echo '<br><a href="options-general.php?page=rss-scroller">';
	echo 'click here</a></p>';
}

function rss_scr_admin() 
{
	?>
	<div class="wrap">
	  <div class="form-wrap">
		<div id="icon-edit" class="icon32 icon32-posts-post"><br>
		</div>
		<h2>RSS scroller</h2>	
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
				<p><strong>Details successfully updated.</strong></p>
			</div>
			<?php
		}
		?>
		<form name="rss_scr_form" method="post" action="">
			<h3>Plugin management</h3>
			
			<label for="tag-width">Title (For widget)</label>
			<input name="rss_scr_title" type="text" value="<?php echo $rss_scr_title; ?>"  id="rss_scr_title" size="50" maxlength="100">
			<p>Please enter your widget title.</p>
			
			<label for="tag-width">Scroller speed</label>
			<input name="rss_scr_speed" type="text" value="<?php echo $rss_scr_speed; ?>"  id="rss_scr_speed" maxlength="5" />
			<p>Please enter your scroller speed, this option only for widget. (Example: 5)</p>
			
			<label for="tag-width">Scroller delay</label>
			<input name="rss_scr_delay" type="text" value="<?php echo $rss_scr_delay; ?>"  id="rss_scr_delay" maxlength="5" />
			<p>Please enter your scroller delay, this option only for widget. (Example: 5000)</p>
			
			<label for="tag-width">Width</label>
			<input name="rss_scr_width" type="text" value="<?php echo $rss_scr_width; ?>"  id="rss_scr_width" maxlength="5" />
			<p>Please enter your width, this option only for widget. (Example: 300)</p>
			
			<label for="tag-width">Height</label>
			<input name="rss_scr_height" type="text" value="<?php echo $rss_scr_height; ?>"  id="rss_scr_height" maxlength="5">
			<p>Please enter your height, this option only for widget. (Example: 200)</p>
			
			<label for="tag-width">No of items to display</label>
			<input name="rss_scr_num" type="text" value="<?php echo $rss_scr_num; ?>"  id="rss_scr_num" maxlength="3" />
			<p>Please enter number of items to display from rss feed. (Example: 10)</p>
			
			<label for="tag-width">RSS URL 1 (This rss link is default for widget).</label>
			<input name="rss_scr_url" type="text" value="<?php echo $rss_scr_url; ?>"  id="rss_scr_url" size="120" />
			<p>Please enter your rss link (url1).</p>
			
			<label for="tag-width">RSS URL 2</label>
			<input name="rss_scr_url2" type="text" value="<?php echo $rss_scr_url2; ?>"  id="rss_scr_url2" size="120" />
			<p>Please enter your rss link (url2).</p>
			
			<label for="tag-width">RSS URL 3</label>
			<input name="rss_scr_url3" type="text" value="<?php echo $rss_scr_url3; ?>"  id="rss_scr_url3" size="120" />
			<p>Please enter your rss link (url3).</p>
			
			<input type="hidden" name="rss_scr_form_submit" value="yes"/>
			<p class="submit">
			<input name="hsa_submit" id="hsa_submit" class="button" value="Submit" type="submit" />
			<a class="button" target="_blank" href="http://www.gopiplus.com/work/2010/07/18/rss-scroller/">Help</a>
			</p>
			<?php wp_nonce_field('rss_scr_form_setting'); ?>
		</form>
		</div>
		<h3>Plugin configuration option</h3>
		<ol>
			<li>Add the plugin in the posts or pages using short code.</li>
			<li>Add directly in to the theme using PHP code.</li>
			<li>Drag and drop the widget to your sidebar.</li>
		</ol>
	  <p class="description">Check official website for more information <a target="_blank" href="http://www.gopiplus.com/work/2010/07/18/rss-scroller/">click here</a></p>
	</div>
	<?php
}

function rss_scr_widget_init() 
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('RSS scroller', 'RSS scroller', 'rss_scr_widget');
	}
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('RSS scroller', array('RSS scroller', 'widgets'), 'rss_scr_control');
	} 
}

function rss_scr_deactivation() 
{
	// No action required.
}

function rss_scr_add_to_menu() 
{
	add_options_page('RSS scroller', 'RSS scroller', 'manage_options', 'rss-scroller', 'rss_scr_admin' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'rss_scr_add_to_menu');
}

add_action("plugins_loaded", "rss_scr_widget_init");
register_activation_hook(__FILE__, 'rss_scr_install');
register_deactivation_hook(__FILE__, 'rss_scr_deactivation');
add_action('init', 'rss_scr_widget_init');
?>