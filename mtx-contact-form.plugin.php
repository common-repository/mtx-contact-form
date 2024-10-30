<?php
/* 
Plugin Name: mtx contact form
Description: Yet Another Contact Form. After installing the plugin include a link with class mtx_contact_form_open (&lt;a href="#" class="mtx_contact_form_open"&gt;Contact Us&lt;/a&gt;)
Version: 1.0
Author: Maurizio Tarchini
Author URI: http://www.mtxweb.ch
 */


//Plugin location
$location = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),'',plugin_basename(__FILE__));
define("YACF_PLUGIN_FOLDER_URL", $location);


function yacf_contact_form_markup()
{
	?>
	<!-- ********** START MTX CONTACT FORM MARKUP ********** -->
	<div id="mtx_contact">
		<a href="#" class="mtx_contact_form_close"><img src="<?php echo YACF_PLUGIN_FOLDER_URL; ?>css/images/close-icon.png" alt="close" title="close"  /></a>
		<div class="mtx_container"><p class="error_info"></p></div>
		<form action="?" method="post" id="mtx_contact_form">
			<p><label for="mtx_name">name</label><input type="text" name="name" class="mtx_field" id="mtx_name" /></p>
			<p><label for="mtx_email">email</label><input type="text" name="email" class="mtx_field" id="mtx_email" /></p>
			<p><label for="mtx_message">message</label><textarea name="message" class="mtx_textarea" id="mtx_message"></textarea></p>
			<input type="image" src="<?php echo YACF_PLUGIN_FOLDER_URL; ?>css/images/yellow_mail_send.png" value="submit" alt="Submit" title="submit" id="mtx_submit_image" />
			<p id="mtx_remaining"></p>
		</form>
	</div>
	<!-- ********** END MTX CONTACT FORM MARKUP ********** -->
	<?php
}

function yacf_init_option_page()
{
	add_options_page('mtx contact form', 'mtx contact form options', 'administrator', 'mtx-contact-form', 'yacf_option_page');
} 

function yacf_option_page()
{
		?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br /></div>
		<h2>mtx contact form Configuration</h2>
		<p>&nbsp;</p>
		<form method="post" action="options.php">
	    	<?php
	        if(function_exists('settings_fields')){
	            settings_fields('yacf-options');
	        } else {
	            wp_nonce_field('update-options');
	        ?>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="yacf_email_to,yacf_send_confirm,yacf_confirm_subject,yacf_confirm_message" />
            <?php
	        }
	    	?>
    		<table class="form-table">
    			<tbody>
					<tr valign="top">
					<th scope="row"><label for="yacf_email_to">Email sent to</label></th>
						<td>
							<input type="text" id="yacf_email_to" value="<?php echo get_option('yacf_email_to'); ?>" name="yacf_email_to"  style="width:400px" />
							<span class="description">usually the administrator's email</span>	
						</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="yacf_send_confirm">Send a confirmation</label></th>
							<td>
								<input type="checkbox" value="yes" name="yacf_send_confirm" id="yacf_send_confirm" <?php if(get_option('yacf_send_confirm') == 'yes'){echo ' checked="checked"';}?> />
								<span class="description">(optional)</span>	
							</td>
					</tr>
				
					<tr valign="top">
						<th scope="row"><label for="yacf_confirm_subject">Subject of confirm email</label></th>
							<td>
								<input type="text" id="yacf_confirm_subject" value="<?php echo get_option('yacf_confirm_subject'); ?>" name="yacf_confirm_subject" style="width:400px" />
								<span class="description"></span>	
							</td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="yacf_confirm_message">Message of confirm email</label></th>
							<td>
								<textarea id="yacf_confirm_message" name="yacf_confirm_message" style="width:400px; height:200px"><?php echo get_option('yacf_confirm_message'); ?></textarea>
								<span class="description"></span>	
							</td>
					</tr>
					<tr valign="top">
						<th scope="row"></th>
							<td>
								<p class="submit">
            						<input type="submit" class="button-primary" id="submit" name="submit" value="<?php _e('Save Changes') ?>" />
        						</p>
							</td>
					</tr>
				</tbody>
    		</table>
    		
        </form>
	</div>
	<?php
}

function yacf_option_register()
{
    if(function_exists('register_setting'))
	{
        register_setting('yacf-options', 'yacf_email_to');
        register_setting('yacf-options', 'yacf_send_confirm');
        register_setting('yacf-options', 'yacf_confirm_subject');
        register_setting('yacf-options', 'yacf_confirm_message');
    }
}

function yacf_enqueue_required_script()
{
	//sylesheet enqueue
	wp_enqueue_style('mtx-contact-css', YACF_PLUGIN_FOLDER_URL . '/css/contact_style.css');
	
	//script register and enqueue
	wp_register_script('ui-dialog-custom', YACF_PLUGIN_FOLDER_URL . 'jquery-ui.min.js', array('jquery'));
	wp_enqueue_script('mtx_dialog', YACF_PLUGIN_FOLDER_URL . 'mtx_contact_custom.js.php', array('ui-dialog-custom'));
}


//============================== ACTIONS ===================================//

add_action('wp_footer', 'yacf_contact_form_markup');
add_action('admin_init', 'yacf_option_register');
add_action('init', 'yacf_enqueue_required_script');
add_action('admin_menu', 'yacf_init_option_page');


// Set the default options when the plugin is activated
function yacf_on_activate()
{
    add_option('yacf_email_to', get_option('admin_email'));
    add_option('yacf_send_confirm', 'yes');
    add_option('yacf_confirm_subject', '[blogname] - send confirmation');
    add_option('yacf_confirm_message', 'Hello [name]. We have received your request and respond as soon as possible. Best regards [blogname] Team');
}

// On plugin activate
register_activation_hook( __FILE__, 'yacf_on_activate');
?>