<?php
/*
Plugin Name: Inject Header And Footer
Plugin URI: https://www.digitaladquest.com/wordpress-plugins/
Description: This plugin allows you to easily add scripts, codes, or texts to the header (head section) and footer (footer section) of your WordPress Website and Blogs.
Version: 1.0
Author: Digital Ad Quest
Author URI: https://www.digitaladquest.com/
License: GPLv2 or later
Copyright 2017 Digital Ad Quest

This program is free software:
you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation,
either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.

See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.
If not, see http://www.gnu.org/licenses/gpl-2.0.html
*/


// Adding Menu
add_action('admin_menu', 'daq_ihaf_add_menu');
function daq_ihaf_add_menu() {
    $page = add_menu_page('Inject Header Footer', 'Inject Header Footer', 'administrator', 'inject-header-footer', 'daq_ihaf_menu_function');
}


// Add settings link on plugin page
function daq_ihaf_plugin_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=inject-header-footer">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'daq_ihaf_plugin_settings_link' );


// Default Options
register_activation_hook( __FILE__, 'daq_ihaf_activate' );

function daq_ihaf_activate() {
// Default Options
}


// Enque CSS and Scripts Admin Page Only
function daq_ihaf_custom_wp_admin_style($hook) {
        // Load only on ?page=tags-categories
        if($hook != 'toplevel_page_inject-header-footer') {
                return;
        }
        wp_enqueue_style( 'custom_wp_admin_css', plugins_url('css/style.css', __FILE__) );
}
add_action( 'admin_enqueue_scripts', 'daq_ihaf_custom_wp_admin_style' );


// Save Settings
add_action('admin_init', 'daq_ihaf_reg_function' );
function daq_ihaf_reg_function() {
	register_setting( 'daq-ihaf-settings-group', 'daq_ihaf_header_content' );
	register_setting( 'daq-ihaf-settings-group', 'daq_ihaf_footer_content' );
}


// Inject Header
function  daq_ihaf_inject_header(){
 
  $daq_ihaf_header_content = get_option('daq_ihaf_header_content');
   if ( $daq_ihaf_header_content != '' ) {
				echo $daq_ihaf_header_content, "\n";
			}
	
}
add_action('wp_head','daq_ihaf_inject_header');


// Inject Footer
function  daq_ihaf_inject_footer(){
 
  $daq_ihaf_footer_content = get_option('daq_ihaf_footer_content');
  
  if ( $daq_ihaf_footer_content != '' ) {
				echo $daq_ihaf_footer_content, "\n";
			}
	
}
add_action('wp_footer','daq_ihaf_inject_footer');




// FEED TO WP DASHBOARD
add_action( 'wp_dashboard_setup', 'daq_ihaf_plugin_setup_function' );
function daq_ihaf_plugin_setup_function() {
    add_meta_box( 'daq_ihaf_plugin_dashboard_custom_feed', 'Plugin Support', 'daq_ihaf_plugin_widget_function', 'dashboard', 'side', 'high' );
}
function daq_ihaf_plugin_widget_function() {
    
	echo '<div class="daq-rss-widget" style="max-height:300px; overflow-y:scroll"><a href="https://www.digitaladquest.com/" target="_blank"><img src="' . plugins_url( 'images/feed-logo.png', __FILE__ ) . '" ></a><br>Thank you for using our plugin <strong>Inject Header And Footer</strong>! We hope the plugin works as stated and you liked this plugin, for any support or feedback, Please <a href="https://www.digitaladquest.com/" target="_blank">visit our website.</a><h3><br><strong>Also You May Check Our Latest News &amp; Blog Updates Below:</strong></h3>';
		wp_widget_rss_output(array(
		// CHANGE THE URL BELOW TO THAT OF YOUR FEED
		'url' => 'http://feeds.feedburner.com/DigitalAdQuest',
		// CHANGE 'OrganicWeb News' BELOW TO THE NAME OF YOUR WIDGET
		'title' => 'Digital Ad Quest Updates',
		// CHANGE '2' TO THE NUMBER OF FEED ITEMS YOU WANT SHOWING
		'items' => 3,
		// CHANGE TO '0' IF YOU ONLY WANT THE TITLE TO SHOW
		'show_summary' => 1,
		// CHANGE TO '1' TO SHOW THE AUTHOR NAME
		'show_author' => 0,
		// CHANGE TO '1' TO SHOW THE PUBLISH DATE
		'show_date' => 1
		));
	echo "</div>";
}



// Setting Form For Admin
function daq_ihaf_menu_function() {
?>

<div class="wrap">
<h1>Inject Header And Footer</h1>
<div id="dashboard" class="daq-ihaf-dashboard">
<h1>Add Contents To Be Added In Header And Footer</h1>

	<!-- Display Saved Message-->
 	<?php if( isset($_GET['settings-updated']) ) { ?>
	<div id="message" class="updated settings-error notice is-dismissible">
	<p><strong><?php _e('Settings saved.') ?></strong></p>
	</div>
	<?php } ?>
	
<form method="post" action="options.php">
	
	<?php settings_fields( 'daq-ihaf-settings-group' ); ?>
    <table class="form-table">
           
        <tr valign="top">
        <th scope="row">Inject Header (Head Section)</th>
        <td>
        <label>
        <textarea rows="4" cols="80" name="daq_ihaf_header_content" /><?php echo get_option('daq_ihaf_header_content'); ?></textarea>
        </label><br />
		<span class="daq-ihaf-orange-color">Note:</span> Content added here will be printed to the &lt;head&gt; section.
		</td>
        </tr>
		
		<tr valign="top">
        <th scope="row">Inject Footer (Footer Section)</th>
        <td>
        <label>
        <textarea rows="4" cols="80" name="daq_ihaf_footer_content" /><?php echo get_option('daq_ihaf_footer_content'); ?></textarea>
        </label><br />
		<span class="daq-ihaf-orange-color">Note:</span> Content added here will be printed to the &lt;footer&gt; section.
		</td>
        </tr>
		    
    </table>
 
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
	
 
</form>
</div>


<div class="daq-ihaf-sidebar">
<?php
echo '<a href="https://www.digitaladquest.com/" target="_blank"><img src="' . plugins_url( 'images/logo.png', __FILE__ ) . '" ></a> ';
?>
<p class="text-justify"><strong>Thank you for using our plugin!</strong> We hope the plugin works as stated and you liked this plugin, for any support or feedback, Please <a href="https://www.digitaladquest.com/" target="_blank">visit our website.</a></p>
<a href="https://www.digitaladquest.com/wordpress-plugins/" class="button daq-ihaf-width-100">View Our Other Plugins</a><br /><br /><a href="https://www.digitaladquest.com/wordpress-theme/" class="button daq-ihaf-width-100">Download WordPress Themes</a><br /><br /><a href="https://www.digitaladquest.com/" class="button-primary daq-ihaf-width-100">Visit Our Website</a>
</div>
</div>
<?php } ?>