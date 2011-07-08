<?php
/**
 * @package Feed2Tabs
 */
/*
Plugin Name: Feed2Tabs
Plugin URI: http://feed2tabs.com/plugins/wordpress/
Description: In addition to link bundling services <a href="http://brief.ly/" target="_blank" ><em>Brief.ly</em></a>, <a href="http://links2.me/" target="_blank" ><em>Links2.Me</em></a>, <a href="http://many.at/" target="_blank" ><em>Many.at</em></a>, Feed2Tabs plugin automatically opens the most recent posts from your RSS feed in the tabs. If you also want your visitors to be able to open all reference in each page with one click, consider installing Links2Tabs plugin.
Version: 0.0.3
Author: Name.ly
Author URI: http://name.ly/
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/



if ( ! defined ( 'NAME_LY_MAX_NUMBER_OF_TABS' ) ) {
  define ( 'NAME_LY_MAX_NUMBER_OF_TABS', 36 );     
} // end of if ( ! defined ( 'NAME_LY_MAX_NUMBER_OF_TABS' ) )

if ( ! defined ( 'NEW_LINE' ) ) {
  define ( 'NEW_LINE', "\n" );     
} // end of if ( ! defined ( 'NEW_LINE' ) )



global $feed2tabs_api_default_bases;
$feed2tabs_api_default_bases = array (
  "many.at" => "http://many.at/feed2tabs/",
  "brief.ly" => "http://brief.ly/feed2tabs/",
  "links2.me" => "http://links2.me/feed2tabs/",
  "feed2tabs.com" => "http://wp.feed2tabs.com/",
);

global $feed2tabs_api_default_base;
$feed2tabs_api_default_base = "http://many.at/feed2tabs/";
// set "random" default base
global $blog_id;
if ( ! function_exists ( "get_blog_details" ) ) {
  if ( file_exists ( ABSPATH . 'wp-includes/ms-blogs.php' ) ) {
    include_once ( ABSPATH . 'wp-includes/ms-blogs.php' );
  } // end of if ( file_exists ( ABSPATH . 'wp-includes/ms-blogs.php' ) )
} // end of if ( ! function_exists ( "get_blog_details" ) )
if ( function_exists ( "get_blog_details" ) ) {
  $blog_details = get_blog_details ( $blog_id, true);
  $blog_registration_time = strtotime ( $blog_details->registered );
  $number_of_bases = count ( $feed2tabs_api_default_bases );
} // end of if ( function_exists ( "get_blog_details" ) )
if ( $number_of_bases ) {
  $default_bases = array_values ( $feed2tabs_api_default_bases );
  $feed2tabs_api_default_base = $default_bases [ $blog_registration_time % $number_of_bases ];
} // end of if ( $number_of_bases )



add_action ( 'widgets_init', 'feed2tabs_widget_load' );

function feed2tabs_widget_load () {
	register_widget ( 'feed2tabs_widget' );
} // end of function feed2tabs_widget_load ()

class feed2tabs_widget extends WP_Widget {

	/**
	 * Widget setup.
	 */
	function feed2tabs_widget () {
		/* Widget settings. */
		$widget_ops = array (
			'classname' => 'feed2tabs-widget',
			'description' => __ ( 'A widget that displays a Feed2Tabs icon, which opens any feed\'s (Atom, RSS, XML, et al) latest items in tabs with just one click.', 'feed2tabs' ),
		);

		/* Widget control settings. */
		$control_ops = array ( 'id_base' => 'feed2tabs-widget', 'width' => 200, );

		/* Create the widget. */
		$this->WP_Widget ( 'feed2tabs-widget', __ ( 'Feed2Tabs', 'feed2tabs' ), $widget_ops, $control_ops );
	} // end of function feed2tabs_widget ()

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_layout_style() function
	 * when creating your form elements. This handles the confusing stuff.
	 */
	function form ( $instance ) {
		global $feed2tabs_api_default_bases;
		global $feed2tabs_api_default_base;

		$site_feed_url = get_bloginfo_rss ( 'rss2_url' );
		$site_feed_link = '<a href="' . $site_feed_url . '" target="_blank" >' . $site_feed_url . '</a>';
		
		/* Set up some default widget settings. */
		$defaults = array (
			'title' => __ ( 'Feed2Tabs', 'feed2tabs' ),
			'source' => '',
			'toc' => __ ( 'ToC', 'feed2tabs' ),
			'numposts' => '10',
			'target' => '_blank',
			'description' => __ ( 'Click on the icon to open all recent posts in the tabs with just one single click.', 'feed2tabs' ),
			'custom_api_base' => $feed2tabs_api_default_base,
		);
		$instance = wp_parse_args ( (array) $instance, $defaults ); 
		?>
		
		<!-- Widget Title -->
		<p>
			<label for="<?php echo $this->get_field_id ( 'title' ); ?>"><?php _e ( 'Title:', 'feed2tabs' ); ?></label><br />
			<input value="<?php echo $instance ['title']; ?>" id="<?php echo $this->get_field_id ( 'title' ); ?>" name="<?php echo $this->get_field_name ( 'title' ); ?>" class="widefat" /><br />
			<small><?php _e ( 'Leave blank for none.', 'feed2tabs' ); ?></small>
		</p>
		
		<!-- Feed Source URL -->
		<p>
			<label for="<?php echo $this->get_field_id ( 'source' ); ?>"><?php echo str_replace ( '%feed%', $site_feed_link, __ ( 'Feed URL:', 'feed2tabs' ) ); ?></label><br />
			<input value="<?php echo $instance ['source']; ?>" id="<?php echo $this->get_field_id ( 'source' ); ?>" name="<?php echo $this->get_field_name ( 'source' ); ?>" class="widefat" /><br />
			<small><?php echo str_replace ( '%feed%', $site_feed_link, __ ( 'Leave blank to use this blog\'s feed %feed% as the source.', 'feed2tabs' ) ); ?><br />
			<?php _e ( 'Else, provide an address of any valid feed (Atom, RSS, XML, et al) you want to open.', 'feed2tabs' ); ?><br />
			<?php echo __ ( 'You can configure how many items your feed publishes in', 'feed2tabs' ) . ' <a href="' . get_site_url () . '/wp-admin/options-reading.php" >' . __ ( 'Reading Settings', 'feed2tabs' ) . '</a>.'; ?></small>
		</p>
		
		<!-- ToC Caption -->
		<p>
			<label for="<?php echo $this->get_field_id ( 'toc' ); ?>"><?php _e ( 'Table of Contents:', 'feed2tabs' ); ?></label><br />
			<input value="<?php echo $instance ['toc']; ?>" id="<?php echo $this->get_field_id ( 'toc' ); ?>" name="<?php echo $this->get_field_name ( 'toc' ); ?>" class="widefat" /><br />
			<small><?php _e ( 'Caption of the Table of Contents. Set to <code>off</code> to hide the ToC.', 'feed2tabs' ); ?></small>
		</p>
		
		<!-- Max Number of Items -->
		<p><?php
			echo '<label for="' . $this->get_field_id ( 'numposts' ) . '">' . __ ( 'Limit the number of shown feed items to:', 'feed2tabs' ) . '</label><br />' . NEW_LINE;
			echo '<select name="' . $this->get_field_name ( 'numposts' ) . '" id="' . $this->get_field_id ( 'numposts' ) . '" class="widefat" >' . NEW_LINE;
			for ( $i=1; $i<=NAME_LY_MAX_NUMBER_OF_TABS; $i++ ) {
				echo '  <option ' . ( $i == $instance [ 'numposts' ] ? 'selected ' : '' ) . 'value="' . $i . '">' . $i . '</option>' . NEW_LINE;
			} // end of for ( $i=1; $i<=NAME_LY_MAX_NUMBER_OF_TABS; $i++ )
			echo '</select><br />' . NEW_LINE;
			echo '<small>' . __ ( '', 'feed2tabs' ) . '</small>' . NEW_LINE;
		?></p>

		<!-- Target -->
		<p><?php
			echo '<label for="' . $this->get_field_id ( 'target' ) . '">' . __ ( 'Link destination:', 'feed2tabs' ) . '</label><br />' . NEW_LINE;
			echo '<select name="' . $this->get_field_name ( 'target' ) . '" id="' . $this->get_field_id ( 'target' ) . '" class="widefat" >' . NEW_LINE;
			echo '  <option ' . ( '_blank' == $instance ['target'] ? 'selected ' : '' ) . 'value="_blank">' . __ (  'New Window', 'feed2tabs' ) . '</option>' . NEW_LINE;
			echo '  <option ' . ( '_blank' != $instance ['target'] ? 'selected ' : '' ) . 'value="_same">' . __ (  'Same Window', 'feed2tabs' ) . '</option>' . NEW_LINE;
			echo '</select><br />' . NEW_LINE;
			echo '<small>' . __ ( '', 'feed2tabs' ) . '</small>' . NEW_LINE;
		?></p>

		<!-- Widget Description -->
		<p>
			<label for="<?php echo $this->get_field_id ( 'description' ); ?>"><?php _e ( 'Description:', 'feed2tabs' ); ?></label><br />
			<input value="<?php echo $instance ['description']; ?>" id="<?php echo $this->get_field_id ( 'description' ); ?>" name="<?php echo $this->get_field_name ( 'description' ); ?>" class="widefat" /><br />
			<small><?php _e ( 'Text to appear as the icon description.', 'feed2tabs' ); ?></small>
		</p>

		<!-- Custom API base URL -->
		<p><?php
			echo '<label for="' . $this->get_field_id( 'custom_api_base' ) . '">' . __ ( 'Custom API base URL (so that the advanced users have extra playground):', 'feed2tabs' ) . '</label><br />' . NEW_LINE;
			echo '<input value="' . $instance [ 'custom_api_base' ] . '" id="' . $this->get_field_id ( 'custom_api_base' ) . '" name="' . $this->get_field_name ( 'custom_api_base' ) . '" class="widefat" /><br />' . NEW_LINE;
			echo '<small>' . __ ( 'You can choose from a predefined API base', 'feed2tabs' ) . '</small>' . NEW_LINE;
			echo '<select name="' . $this->get_field_name ( 'api_base' ) . '" id="' . $this->get_field_id ( 'api_base' ) . '" onClick="document.getElementById(\'' . $this->get_field_id( 'custom_api_base' ) . '\').value=this.value;" class="widefat" style="width:50%;" >' . NEW_LINE;
			foreach ( $feed2tabs_api_default_bases as $key => $api_default_base ) {
				echo '  <option ' . ( false !== stripos ( $instance [ 'custom_api_base' ], $api_default_base ) ? 'selected ' : '' ) . 'value="' . $api_default_base . '">' . $key . '</option>' . NEW_LINE;
			} // end of foreach ( $feed2tabs_api_default_bases as $key => $api_default_base )
			echo '</select>' . NEW_LINE;
			echo '<small>' . __ ( 'or provide your own.', 'feed2tabs' ) . '</small><br />' . NEW_LINE;
			echo '<small>' . __ ( 'If you want to use your own custom base, you need to register and configure it first.', 'feed2tabs' ) . '<br />' . NEW_LINE;
			echo __ ( 'You can even map it on your own domain name. More instructions on: <a href="http://name.ly/api/custom-api/" target="_blank">Custom API</a> help page.', 'feed2tabs' ) . '</small>' . NEW_LINE;
		?></p>

		<p>
			<?php _e ( 'Extra:', 'feed2tabs' ); ?><br />
			<small><?php _e ( 'You can create more feed widgets via <a href="http://feed2tabs.com/" target="_blank" >Feed2Tabs</a> site, then insert them as HTML code using a Text widget in WordPress.', 'feed2tabs' ); ?></small><br />
			<small><?php _e ( 'Complete documentation on Feed2Tabs can be found here <a href="http://feed2tabs.com/plugins/wordpress/" target="_blank" >here</a>.', 'feed2tabs' ); ?></small>
		</p>


	<?php
	} // end of function form ( $instance )

	/**
	 * Update the widget settings.
	 */
	function update ( $new_instance, $old_instance ) {
		
		$instance = $old_instance;

		/* Strip tags for title and layout_style to remove HTML (important for text inputs). */
		$instance [ 'title' ] = strip_tags ( $new_instance [ 'title' ] );
		$instance [ 'source' ] = strip_tags ( $new_instance [ 'source' ] );
		$instance [ 'toc' ] = strip_tags ( $new_instance [ 'toc' ] );
		$instance [ 'numposts' ] = intval ( $new_instance [ 'numposts' ] );
		$instance [ 'target' ] = $new_instance [ 'target' ];
		$instance [ 'description' ] = strip_tags ( $new_instance [ 'description' ] );
		$instance [ 'custom_api_base' ] = strip_tags ( $new_instance [ 'custom_api_base' ] );

		return $instance;
	} // end of function update ( $new_instance, $old_instance )

	/**
	 * How to display the widget on the screen.
	 */
	function widget ( $args, $instance ) {
		extract ( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters ( 'widget_title', $instance [ 'title' ] );

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title ) {
			echo $before_title . $title . $after_title;		
		} // end of if ( $title )

		echo '<div class="feed2tabs" style="text-align:center;" >';
		if ( "" == trim ( $instance [ 'source' ] ) ) {
			$instance [ 'source' ] = get_bloginfo_rss ( 'rss2_url' );
		} // end of if ( "" == trim ( $instance [ 'source' ] ) )
		$result_link = $instance [ 'custom_api_base' ] . '?f=' . urlencode ( $instance [ 'source' ] ) . '&fn=' . intval ( $instance [ 'numposts' ] ) . '&toc=' . urlencode ( $instance [ 'toc' ] );
		$icon_url = get_site_url () . ( defined ( 'PLUGINDIR' ) ? '/' . PLUGINDIR : '/wp-content/plugins' ) . '/feed2tabs/feed2tabs-32.png';
		echo '<a href="' . $result_link . '" title="' . trim ( $instance [ 'description' ] ) . '"' . ( "_blank" == $instance [ 'target' ] ? ' target="_blank"' : '' ) . ' >' . '<img src="' . $icon_url . '" border="0" alt="' . trim ( $instance [ 'description' ] ) . '" />' . '</a>';
		echo '</div>';

		/* After widget (defined by themes). */
		echo $after_widget;
	} // end of function widget ( $args, $instance )

} // end of class feed2tabs_widget



function feed2tabs_show_short_code ( $atts ) {
	global $feed2tabs_api_default_base;

	$defaults = array (
		'title' => __ ( 'Feed2Tabs', 'feed2tabs' ),
		'source' => '',
		'toc' => __ ( 'ToC', 'feed2tabs' ),
		'numposts' => '10',
		'target' => '_blank',
		'description' => __ ( 'Click on the icon to open all recent posts in the tabs with just one single click.', 'feed2tabs' ),
		'custom_api_base' => $feed2tabs_api_default_base,
	);

	extract ( shortcode_atts ( $defaults, $atts ) );

	if ( "" == trim ( $source ) ) {
		$source = get_bloginfo_rss ( 'rss2_url' );
	} // end of if ( "" == trim ( $source ) )

	$result_link = strip_tags ( $custom_api_base ) . '?f=' . urlencode ( $source ) . '&fn=' . intval ( $numposts ) . '&toc=' . urlencode ( $toc );

	return '<a href="' . $result_link . '" title="' . strip_tags ( $description ) . '"' . ( "_blank" == $target ? ' target="_blank"' : '' ) . ' >' . strip_tags ( $title ) . '</a>';

} // end of feed2tabs_show_short_code ( $atts )

add_shortcode ( 'Feed2Tabs', 'feed2tabs_show_short_code' );



?>