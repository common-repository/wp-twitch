<?php
	// Class to allow Twitch embedding as a widget.
	class WPTwitchWidget extends WP_Widget {
		// Constructor.
		function WPTwitchWidget() {
			$widget_ops = array('classname' => 'WPTwitchWidget','description' => 'Display your Twitch stream when online');
			$this->WP_Widget('WPTwitchWidget','WP Twitch',$widget_ops);
		}

		// Form for editing widget settings.
		function form($instance)
		{
			$instance = wp_parse_args( (array) $instance, array( 'channel' => '' ) );
			$channel = $instance['channel'];
			$https = $instance['https'];
?>

<p>
	<label for="<?php echo $this->get_field_id('channel'); ?>">Channel Name:
		<input class="widefat" id="<?php echo $this->get_field_id('channel'); ?>" name="<?php echo $this->get_field_name('channel'); ?>" type="text" value="<?php echo esc_attr($channel); ?>" />
	</label>
</p>

<p><label for="<?php echo $this->get_field_id('https'); ?>">Use HTTPS: <input id="<?php echo $this->get_field_id('https'); ?>" name="<?php echo $this->get_field_name('https'); ?>" type="checkbox" value="1" <?php checked(isset($instance['https']) ? $instance['https'] : 0); ?> /></label></p>

<?php
		}

		// Handle saving of widget settings.
		function update($new_instance, $old_instance)
		{
			$instance = $old_instance;
			$instance['channel'] = $new_instance['channel'];
			$instance['https'] = $new_instance['https'];
			return $instance;
		}

		// Display the widget.
		function widget($args, $instance)
		{
			$channel = $instance['channel'];
			$https = $instance['https'];

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
				
				echo $returnValue;
			}
		}
	}

	// Register the widget.
	add_action('widgets_init',create_function('','return register_widget("WPTwitchWidget");'));
?>