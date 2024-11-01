<?php
/*
Plugin Name: WP Twitch
Plugin URI: https://github.com/bertjohnson/WPTwitch
Description: Display your Twitch stream when online.
Author: Bert Johnson
Version: 1.0
Author URI: https://bertjohnson.com
*/

// Register shortcodes.
function WPTwitchShortcodes() {
	add_shortcode( 'twitch', 'WPTwitch');
}
add_action('init', 'WPTwitchShortcodes');

// Handle embedding.
function WPTwitch($atts) {
	extract(shortcode_atts(array('channel' => '', 'width' => null, 'height' => null, 'https' => false), $atts));
  
	// Only display the stream if it's online.
	$json = @file_get_contents("https://api.twitch.tv/kraken/streams/{$channel}", 0, null, null);
	if (strpos($json, '{"stream":{') !== false)
	{
		$returnValue = "<div class='wptwitch'><iframe type='text/html' src='";
		
		if ($https)
			$returnValue .= "https://www-cdn.jtvnw.net/swflibs/TwitchPlayer.rfc07d37fc4eed1d17243b452dd3441665496e1e0.swf?channel={$channel}";
		else
			$returnValue .= "http://www.twitch.tv/{$channel}/embed'";

		$returnValue .= "' frameborder='0'";
		
		if (isset($width))
			$returnValue .= " width='{$width}'";
		if (isset($height))
			$returnValue .= " height='{$height}'";
				
		$returnValue .= "></iframe></div>";
		
		return $returnValue;
	}
}

include('widget.php');
?>