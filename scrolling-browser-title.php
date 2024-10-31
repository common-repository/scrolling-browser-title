<?php
/*
Plugin Name: Scrolling browser title
Plugin URI: http://www.gopiplus.com/work/2021/06/12/scrolling-browser-title-wordpress-plugin/
Description: Scrolling browser title is a simple WordPress plugin to scroll the browser title. This plugin uses simple JavaScript code to create the scrolling. It works in all the leading browsers.
Author: Gopi Ramasamy
Version: 1.2
Author URI: http://www.gopiplus.com/work/about/
Donate link: http://www.gopiplus.com/
Tags: plugin, scrolling, title, browser
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: scrolling-browser-title
Domain Path: /languages
*/

function sbtp_display() {
    
	$sbtp_wp_common = get_option('sbtp_common'); // HOME=YES|POST=YES|PAGE=YES|FULL=NO
	$sbtp_wp_posts = get_option('sbtp_posts');
	$sbtp_wp_pages = get_option('sbtp_pages');
	
	$sbtp_common = explode("|", $sbtp_wp_common);
	$sbtp_home = explode("=", $sbtp_common[0]);
	$sbtp_posts = explode("=", $sbtp_common[1]);
	$sbtp_pages = explode("=", $sbtp_common[2]);
	$sbtp_full = explode("=", $sbtp_common[3]);
	$sbtp_home = $sbtp_home[1];
	$sbtp_posts = $sbtp_posts[1];
	$sbtp_pages = $sbtp_pages[1];
	$sbtp_full = $sbtp_full[1];
	
	$display = "";
	
	if($sbtp_full == 'YES') {
		$display = "show";
	}
	else {
		if(is_home() && $sbtp_home == 'YES') {	
			$display = "show";	
		}
		if(is_single()) {	
			if($sbtp_posts == 'YES') {
				$display = "show";
			}
			else {
				if ($sbtp_wp_posts <> "") { 
					$current_id = get_the_ID();
					$ids = explode(",", $sbtp_wp_posts);
					foreach($ids as $id) {
						$id = trim($id);
						if($id == $current_id) {
							$display = "show";
						}
					}
				}
			}	
		}
		if(is_page()) {	
			if($sbtp_pages == 'YES') {
				$display = "show";
			}
			else {
				if ($sbtp_wp_pages <> "") { 
					$current_id = get_the_ID();
					$ids = explode(",", $sbtp_wp_pages);
					foreach($ids as $id) {
						$id = trim($id);
						if($id == $current_id) {
							$display = "show";
						}
					}
				}
			}
		}
	}

	if( $display == 'show' ) {
	?>
        <script type="text/javascript">
			var repeat = 0; //enter 0 to not repeat scrolling after 1 run, othersise, enter 1
			var title = document.title;
			var leng = title.length;
			var start = 1;
			function titlescroll() {
				titl = title.substring(start, leng) + title.substring(0, start);
				document.title = titl;
				start++;
				if (start==leng+1) {
					start=0;
					if (repeat==0) {
						return;
					}
				}
				setTimeout("titlescroll()", 140);
			}
			if (document.title) {
				titlescroll();
			}
        </script>
    <?php
	}
}

function sbtp_addtomenu() {
	if (is_admin()) {
		add_options_page( __('Scrolling browser title', 'scrolling-browser-title'), 
							__('Scrolling browser title', 'scrolling-browser-title'), 'manage_options', 
								'scrolling-browser-title', 'sbtp_adminoptions' );
	}
}

function sbtp_adminoptions() {
	global $wpdb;
	
	$sbtp_wp_common = get_option('sbtp_common'); // HOME=YES|POST=YES|PAGE=YES|FULL=NO
	$sbtp_wp_posts = get_option('sbtp_posts');
	$sbtp_wp_pages = get_option('sbtp_pages');
	
	$sbtp_common = explode("|", $sbtp_wp_common);
	$sbtp_home = explode("=", $sbtp_common[0]);
	$sbtp_posts = explode("=", $sbtp_common[1]);
	$sbtp_pages = explode("=", $sbtp_common[2]);
	$sbtp_full = explode("=", $sbtp_common[3]);
	
	$sbtp_home = $sbtp_home[1];
	$sbtp_posts = $sbtp_posts[1];
	$sbtp_pages = $sbtp_pages[1];
	$sbtp_full = $sbtp_full[1];
	
	if (isset($_POST['sbtp_form_submit']) && sanitize_text_field($_POST['sbtp_form_submit']) == 'yes')
	{
		check_admin_referer('sbtp_form_setting');
		
		$sbtp_home = stripslashes(sanitize_text_field($_POST['sbtp_home']));
		$sbtp_posts = stripslashes(sanitize_text_field($_POST['sbtp_posts']));
		$sbtp_pages = stripslashes(sanitize_text_field($_POST['sbtp_pages']));
		$sbtp_full = stripslashes(sanitize_text_field($_POST['sbtp_full']));
		$sbtp_wp_posts = stripslashes(sanitize_text_field($_POST['sbtp_wp_posts']));
		$sbtp_wp_pages = stripslashes(sanitize_text_field($_POST['sbtp_wp_pages']));
		
		$sbtp_common = "HOME=".$sbtp_home."|POST=".$sbtp_posts."|PAGE=".$sbtp_pages."|FULL=".$sbtp_full;
		update_option('sbtp_common', $sbtp_common );
		update_option('sbtp_posts', esc_html($sbtp_wp_posts) );
		update_option('sbtp_pages', esc_html($sbtp_wp_pages) );
		
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'scrolling-browser-title'); ?></strong></p>
		</div>
		<?php
	}
	?>
	<div class="wrap">
	  <h1><?php _e('Scrolling browser title', 'scrolling-text-title'); ?></h1>
	  <form  name="sbtp_form" method="post" action="#" novalidate="novalidate">
	  	<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row"><label for="sbtp_home"><?php _e('Scroll on home page', 'scrolling-browser-title'); ?></label></th>
					<td>
					<select name="sbtp_home" id="sbtp_home">
						<option value='YES' <?php if($sbtp_home == 'YES') { echo "selected='selected'" ; } ?>>YES</option>
						<option value='NO' <?php if($sbtp_home == 'NO') { echo "selected='selected'" ; } ?>>NO</option>
					</select>
					<p><?php _e('This option is to scroll the browser title on the home page.', 'scrolling-browser-title'); ?></p>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><label for="sbtp_posts"><?php _e('Scroll on wp posts', 'scrolling-browser-title'); ?></label></th>
					<td>
					<select name="sbtp_posts" id="sbtp_posts">
						<option value='YES' <?php if($sbtp_posts == 'YES') { echo "selected='selected'" ; } ?>>YES</option>
						<option value='NO' <?php if($sbtp_posts == 'NO') { echo "selected='selected'" ; } ?>>NO</option>
					</select>
					<p><?php _e('This option is to scroll the browser title on all the wp posts.', 'scrolling-browser-title'); ?></p>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><label for="sbtp_pages"><?php _e('Scroll on wp pages', 'scrolling-browser-title'); ?></label></th>
					<td>
					<select name="sbtp_pages" id="sbtp_pages">
						<option value='YES' <?php if($sbtp_pages == 'YES') { echo "selected='selected'" ; } ?>>YES</option>
						<option value='NO' <?php if($sbtp_pages == 'NO') { echo "selected='selected'" ; } ?>>NO</option>
					</select>
					<p><?php _e('This option is to scroll the browser title on all the wp pages.', 'scrolling-browser-title'); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><label for="sbtp_full"><?php _e('Scroll on entire website', 'scrolling-browser-title'); ?></label></th>
					<td>
					<select name="sbtp_full" id="sbtp_full">
						<option value='YES' <?php if($sbtp_full == 'YES') { echo "selected='selected'" ; } ?>>YES</option>
						<option value='NO' <?php if($sbtp_full == 'NO') { echo "selected='selected'" ; } ?>>NO</option>
					</select>
					<p><?php _e('This option is to scroll the browser title on the entire website.', 'scrolling-browser-title'); ?></p>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><label for="sbtp_posts"><?php _e('Scroll on specific wp posts', 'scrolling-browser-title'); ?></label></th>
					<td><input name="sbtp_wp_posts" type="text" id="sbtp_wp_posts" value="<?php echo esc_html($sbtp_wp_posts); ?>" class="regular-text" >					
					<p><?php _e('Enter specific wp posts IDs. Enter comma separated.', 'scrolling-browser-title'); ?></p>
					</td>
				</tr>
				
				<tr>
					<th scope="row"><label for="sbtp_pages"><?php _e('Scroll on specific wp pages', 'scrolling-browser-title'); ?></label></th>
					<td><input name="sbtp_wp_pages" type="text" id="sbtp_wp_pages" value="<?php echo esc_html($sbtp_wp_pages); ?>" class="regular-text" >					
					<p><?php _e('Enter specific wp pages IDs. Enter comma separated.', 'scrolling-browser-title'); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<input type="hidden" name="sbtp_form_submit" value="yes"/>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
		<?php wp_nonce_field('sbtp_form_setting'); ?>
	  </form>
	</div>
	<?php
}

function sbtp_activation() 
{
	global $wpdb;
	add_option('sbtp_common', "HOME=YES|POST=YES|PAGE=YES|FULL=NO");
	add_option('sbtp_posts', "");
	add_option('sbtp_pages', "");
}

function sbtp_deactivation() 
{
	//No action
}

function sbtp_textdomain()
{
	  load_plugin_textdomain( 'scrolling-browser-title', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action('plugins_loaded', 'sbtp_textdomain');
add_action('wp_head', 'sbtp_display');
add_action('admin_menu', 'sbtp_addtomenu');
register_activation_hook(__FILE__, 'sbtp_activation');
register_deactivation_hook(__FILE__, 'sbtp_deactivation');
?>