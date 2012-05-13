<?php
/**
 * @author: Paddy Miller (http://paddy.eu.com)
 * @date: 05/10/2011
 * @license: GPLv2
 */

include_once 'fbConfig.php';
global $wp_version, $fbconnect,$fb_reg_formfields;

			if (isset($_POST['og_update'])){
				check_admin_referer('wp-fbconnect-info_update');

				$error = '';
				update_option('fb_comments_logo',$_POST['fb_comments_logo']);
				update_option( 'fb_og_type', $_POST['fbconnect_og_type'] );	
				update_option( 'fb_admins', $_POST['fb_admins'] );	
				update_option( 'fb_og_latitude', $_POST['fb_og_latitude'] );
				update_option( 'fb_og_longitude', $_POST['fb_og_longitude'] );		
				update_option( 'fb_og_street_address', $_POST['fb_og_street_address'] );	
				update_option( 'fb_og_locality', $_POST['fb_og_locality'] );	
				update_option( 'fb_og_region', $_POST['fb_og_region'] );	
				update_option( 'fb_og_postal_code', $_POST['fb_og_postal_code'] );	
				update_option( 'fb_og_country_name', $_POST['fb_og_country_name'] );																	
			}
			
			// Display the options page form
			$siteurl = get_option('home');
			if( substr( $siteurl, -1, 1 ) !== '/' ) $siteurl .= '/';
			?>
			<div class="wrap">
				<h2><?php _e('Facebook Connect Plugin Options', 'fbconnect') ?></h2>

				<form method="post">

					<h3><?php _e('Open Graph MetaData', 'fbconnect') ?></h3>
	<?php
							
						$fb_user = fb_get_loggedin_user();

							?>

	     				<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
	     					<tr valign="top">
								<th width="30%" scope="row"><label for="fb_og_type"><?php _e('OpenGraph type:', 'fbconnect') ?></label></th>
								<td width="70%">
									<div style="width:380px;">
								<?php WPfbConnect_Interface::print_ogtypes_select(get_option('fb_og_type')); ?>
								</div>
								</td>
							</tr>
							<tr valign="top">
								<th width="30%" scope="row"><label for="fb_admins"><?php _e('Facebook admin IDs:', 'fbconnect') ?></label></th>
								<td width="70%">
								<input type="text" name="fb_admins" id="fb_admins" value="<?php echo get_option('fb_admins'); ?>" size="25" /> (Your ID: <?php echo $fb_user;?>)  		
								</td>
							</tr>
							<tr valign="top">
								<th width="30%" scope="row"><label for="fb_comments_logo"><?php _e('Default image:', 'fbconnect') ?></label></th>
								<td width="70%">
								<input type="text" size="62" name="fb_comments_logo" id="fb_comments_logo" value="<?php echo get_option('fb_comments_logo'); ?>"/> 
								</td>
							</tr>
							<tr valign="top">
								<th width="30%" scope="row"><label for="fb_og_latitude "><?php _e('Latitude:', 'fbconnect') ?></label></th>
								<td width="70%">
								<input type="text" size="62" name="fb_og_latitude" id="fb_og_latitude" value="<?php echo get_option('fb_og_latitude'); ?>"/> 
								</td>
							</tr>
							<tr valign="top">
								<th width="30%" scope="row"><label for="fb_og_longitude"><?php _e('Longitude:', 'fbconnect') ?></label></th>
								<td width="70%">
								<input type="text" size="62" name="fb_og_longitude" id="fb_og_longitude" value="<?php echo get_option('fb_og_longitude'); ?>"/> 
								</td>
							</tr>
							<tr valign="top">
								<th width="30%" scope="row"><label for="fb_og_street_address"><?php _e('Street address:', 'fbconnect') ?></label></th>
								<td width="70%">
								<input type="text" size="62" name="fb_og_street_address" id="fb_og_street_address" value="<?php echo get_option('fb_og_street_address'); ?>"/> 
								</td>
							</tr>
							<tr valign="top">
								<th width="30%" scope="row"><label for="fb_og_locality"><?php _e('Locality:', 'fbconnect') ?></label></th>
								<td width="70%">
								<input type="text" size="62" name="fb_og_locality" id="fb_og_locality" value="<?php echo get_option('fb_og_locality'); ?>"/> 
								</td>
							</tr>
							<tr valign="top">
								<th width="30%" scope="row"><label for="fb_og_region"><?php _e('Region:', 'fbconnect') ?></label></th>
								<td width="70%">
								<input type="text" size="62" name="fb_og_region" id="fb_og_region" value="<?php echo get_option('fb_og_region'); ?>"/> 
								</td>
							</tr>
							<tr valign="top">
								<th width="30%" scope="row"><label for="fb_og_postal_code"><?php _e('Postal code:', 'fbconnect') ?></label></th>
								<td width="70%">
								<input type="text" size="62" name="fb_og_postal_code" id="fb_og_postal_code" value="<?php echo get_option('fb_og_postal_code'); ?>"/> 
								</td>
							</tr>
							<tr valign="top">
								<th width="30%" scope="row"><label for="fb_og_country_name"><?php _e('Country name:', 'fbconnect') ?></label></th>
								<td width="70%">
								<input type="text" size="62" name="fb_og_country_name" id="fb_og_country_name" value="<?php echo get_option('fb_og_country_name'); ?>"/> 
								</td>
							</tr>

	     				</table>

						<?php wp_nonce_field('wp-fbconnect-info_update'); ?>
	     				<p class="submit"><input class="button-primary" type="submit" name="og_update" value="<?php _e('Update Configuration', 'fbconnect') ?> &raquo;" /></p>
	     			</form>
			</div>
