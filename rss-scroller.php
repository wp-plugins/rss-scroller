<?php

/*
Plugin Name: RSS scroller.
Description: This plug-in will display RSS feed with simple scroller or ticker. It gradually reveals each item into view from left to right.
Author: Gopi.R
Version: 3.0
Plugin URI: http://www.gopiplus.com/work/2010/07/18/rss-scroller/
Author URI: http://www.gopiplus.com/work/2010/07/18/rss-scroller/
*/

function rss_scr_show()
{
	//include_once(ABSPATH.WPINC.'/rss.php');
	
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


add_filter('the_content','rss_scr_show_filter');

function rss_scr_show_filter($content)
{
	return 	preg_replace_callback('/\[RSS-SCROLLER:(.*?)\]/sim','rss_scr_show_filter_callback',$content);
}

function rss_scr_show_filter_callback($matches) 
{
	global $wpdb;
	//[RSS-SCROLLER:TYPE=widget]
	
	//include_once(ABSPATH.WPINC.'/rss.php');
	
	$scode = $matches[1];
	
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

function rss_scr_install() {
	add_option('rss_scr_title', "rss scroller");
	add_option('rss_scr_width', '175');
	add_option('rss_scr_height', '60');
	add_option('rss_scr_delay', '5000');
	add_option('rss_scr_speed', '5');
	add_option('rss_scr_num', '10');
	$rss2_url = get_option('home'). "/?feed=rss2";
	//add_option('rss_scr_url', $rss2_url);
	add_option('rss_scr_url', 'http://wordpress.org/news/feed/');
}

function rss_scr_widget($args) {
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('rss_scr_title');
	echo $after_title;
	rss_scr_show();
	echo $after_widget;
}
	
function rss_scr_control() {
	echo '<p>To change the setting goto RSS Scroller link under setting menu.';
	echo '<br><a href="options-general.php?page=rss-scroller/rss-scroller.php">';
	echo 'click here</a></p>';
	?>
	<h2>About plugin</h2>
	Plug-in created by <a target="_blank" href='http://www.gopiplus.com/work/'>www.gopiplus.com</a>.<br>
	<a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/rss-scroller/'>Click here</a> to post suggestion/comments.<br>
	<a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/rss-scroller/'>Click here</a> to see live demo.<br>
	<?php
}

function rss_scr_admin() {
	$rss_scr_title = get_option('rss_scr_title');
	$rss_scr_width = get_option('rss_scr_width');
	$rss_scr_height = get_option('rss_scr_height');
	$rss_scr_delay = get_option('rss_scr_delay');
	$rss_scr_speed = get_option('rss_scr_speed');
	$rss_scr_num = get_option('rss_scr_num');
	$rss_scr_url = get_option('rss_scr_url');
	
	if ($_POST['rss_scr_submit']) 
	{	
		$rss_scr_title = stripslashes($_POST['rss_scr_title']);
		$rss_scr_width = stripslashes($_POST['rss_scr_width']);
		$rss_scr_height = stripslashes($_POST['rss_scr_height']);
		$rss_scr_delay = stripslashes($_POST['rss_scr_delay']);
		$rss_scr_speed = stripslashes($_POST['rss_scr_speed']);
		$rss_scr_num = stripslashes($_POST['rss_scr_num']);
		$rss_scr_url = stripslashes($_POST['rss_scr_url']);
		
		update_option('rss_scr_title', $rss_scr_title );
		update_option('rss_scr_width', $rss_scr_width );
		update_option('rss_scr_height', $rss_scr_height );
		update_option('rss_scr_delay', $rss_scr_delay );
		update_option('rss_scr_speed', $rss_scr_speed );
		update_option('rss_scr_num', $rss_scr_num );
		update_option('rss_scr_url', $rss_scr_url );
	}
	
?>
<div class="wrap">
  <h2><?php echo wp_specialchars( 'RSS scroller' ); ?></h2>
  <form name="form_mt" method="post" action="">
    <table width="800" border="0" cellspacing="0" cellpadding="3">
      <tr>
        <td colspan="2" align="left" valign="bottom">Title: </td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="bottom"><input name="rss_scr_title" type="text" value="<?php echo $rss_scr_title; ?>"  id="rss_scr_title" size="120" maxlength="100"></td>
      </tr>
      <tr align="left" valign="middle">
        <td width="314" valign="bottom">Scroller Speed:</td>
        <td width="474" height="230" rowspan="10" align="center" valign="middle"><?php if (function_exists (timepass)) timepass(); ?>      
      </tr>
      <tr align="left" valign="middle">
        <td valign="bottom"><input name="rss_scr_speed" type="text" value="<?php echo $rss_scr_speed; ?>"  id="rss_scr_speed" maxlength="5" />
          only number </td>
      </tr>
      <tr align="left" valign="middle">
        <td valign="bottom">Scroller Delay:</td>
      </tr>
      <tr align="left" valign="middle">
        <td valign="middle"><input name="rss_scr_delay" type="text" value="<?php echo $rss_scr_delay; ?>"  id="rss_scr_delay" maxlength="5" />
          only number </td>
      </tr>
      <tr align="left" valign="middle">
        <td valign="middle">Width:</td>
      </tr>
      <tr>
        <td align="left" valign="middle"><input name="rss_scr_width" type="text" value="<?php echo $rss_scr_width; ?>"  id="rss_scr_width" maxlength="5" />
          only number </td>
      </tr>
      <tr align="left" valign="middle">
        <td valign="middle">Height:</td>
      </tr>
      <tr align="left" valign="middle">
        <td valign="middle"><input name="rss_scr_height" type="text" value="<?php echo $rss_scr_height; ?>"  id="rss_scr_height" maxlength="5">
          only number </td>
      </tr>
      <tr>
        <td align="left" valign="middle">No of Items:</td>
      </tr>
      <tr>
        <td align="left" valign="middle"><input name="rss_scr_num" type="text" value="<?php echo $rss_scr_num; ?>"  id="rss_scr_num" maxlength="3" />
          only number </td>
      </tr>
      <tr>
        <td align="left" valign="bottom">RSS URL: </td>
        <td align="center" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="bottom"><input name="rss_scr_url" type="text" value="<?php echo $rss_scr_url; ?>"  id="rss_scr_url" size="120" /></td>
      </tr>
      <tr>
        <td height="40" align="left" valign="bottom"><input name="rss_scr_submit" id="rss_scr_submit" lang="publish" class="button-primary" value="Update Setting" type="submit" /></td>
        <td align="center" valign="top">&nbsp;</td>
      </tr>
    </table>
  </form>
  <br>
  <h2>Plugin configuration</h2>
	<ul>
    <li><a href="http://www.gopiplus.com/work/2010/07/18/rss-scroller/" target="_blank">Drag and Drop the Widget</a></li>
    <li><a href="http://www.gopiplus.com/work/2010/07/18/rss-scroller/" target="_blank">Add the gallery in the Posts or Pages</a></li>
    <li><a href="http://www.gopiplus.com/work/2010/07/18/rss-scroller/" target="_blank">Add directly in the theme</a></li>
    </ul>
	<h2>About plugin</h2>
	Plug-in created by <a target="_blank" href='http://www.gopiplus.com/work/'>www.gopiplus.com</a>.<br>
	<a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/rss-scroller/'>Click here</a> to post suggestion/comments/feedback.<br>
	<a target="_blank" href='http://www.gopiplus.com/work/2010/07/18/rss-scroller/'>Click here</a> to see live demo.<br>
</div>
<?php
}

function rss_scr_widget_init() {
  	register_sidebar_widget(__('RSS scroller'), 'rss_scr_widget');   
	if(function_exists('register_sidebar_widget')) {
		register_sidebar_widget('RSS scroller', 'rss_scr_widget');
	}
	if(function_exists('register_widget_control')) {
		register_widget_control(array('RSS scroller', 'widgets'), 'rss_scr_control');
	} 
}

function rss_scr_deactivation() 
{
	//delete_option('rss_scr_title');
//	delete_option('rss_scr_width');
//	delete_option('rss_scr_height');
//	delete_option('rss_scr_delay');
//	delete_option('rss_scr_speed');
//	delete_option('rss_scr_num');
//	delete_option('rss_scr_url');
}

function rss_scr_add_to_menu() {
	add_options_page('RSS scroller', 'RSS scroller', 'manage_options', __FILE__, 'rss_scr_admin' );
}

add_action("plugins_loaded", "rss_scr_widget_init");
register_activation_hook(__FILE__, 'rss_scr_install');
register_deactivation_hook(__FILE__, 'rss_scr_deactivation');
add_action('init', 'rss_scr_widget_init');
add_action('admin_menu', 'rss_scr_add_to_menu');
?>
