<?php
/**
 * @author: Paddy Miller (http://paddy.eu.com)
 * @date: 05/10/2011
 * @license: GPLv2
 */

include_once 'fbConfig.php';

global $wp_version, $fbconnect,$fb_reg_formfields;

			if (isset($_POST['share_update'])){
				check_admin_referer('wp-fbconnect-share_update');

				$error = '';

				update_option( 'fb_add_page_head_share', isset($_POST['fb_add_page_head_share']) ? true : false );				
				update_option( 'fb_add_page_share', isset($_POST['fb_add_page_share']) ? true : false );
				update_option( 'fb_hide_home_head_share', isset($_POST['fb_hide_home_head_share']) ? true : false );				
				update_option( 'fb_hide_home_share', isset($_POST['fb_hide_home_share']) ? true : false );
				update_option( 'tw_userid', $_POST['tw_userid']);
				update_option( 'fb_share_head_style', $_POST['fb_share_head_style']);
				update_option( 'fb_share_style', $_POST['fb_share_style']);
				update_option( 'fb_add_post_share', isset($_POST['fb_add_post_share']) ? true : false );
				update_option( 'fb_add_post_like', isset($_POST['fb_add_post_like']) ? true : false );				
				update_option( 'li_add_post_share', isset($_POST['li_add_post_share']) ? true : false );
				update_option( 'fb_add_post_send', isset($_POST['fb_add_post_send']) ? true : false );
				update_option( 'fb_add_post_google1', isset($_POST['fb_add_post_google1']) ? true : false );
				update_option( 'fb_add_post_head_share', isset($_POST['fb_add_post_head_share']) ? true : false );
				update_option( 'fb_add_post_head_like', isset($_POST['fb_add_post_head_like']) ? true : false );
				update_option( 'fb_add_post_head_send', isset($_POST['fb_add_post_head_send']) ? true : false );
				update_option( 'fb_add_post_head_google1', isset($_POST['fb_add_post_head_google1']) ? true : false );
				update_option( 'fb_like_show_faces', isset($_POST['fb_like_show_faces']) ? true : false );
				update_option( 'tw_add_post_share', isset($_POST['tw_add_post_share']) ? true : false );
				update_option( 'tw_add_post_head_share', isset($_POST['tw_add_post_head_share']) ? true : false );
				update_option( 'li_add_post_head_share', isset($_POST['li_add_post_head_share']) ? true : false );
				update_option( 'fb_like_show_faces', isset($_POST['fb_like_show_faces']) ? true : false );
				
			}
			
			// Display the options page form
			$siteurl = get_option('home');
			if( substr( $siteurl, -1, 1 ) !== '/' ) $siteurl .= '/';
			?>
			<div class="wrap">
				<h2><?php _e('Facebook Configuration', 'fbconnect') ?> : <?php _e('Share options', 'fbconnect') ?></h2>

				<form method="post">

	     				<table class="form-table" cellspacing="2" cellpadding="5" width="100%">
	
						<tr valign="top">
							<td colspan=2>
								<h3><?php _e('Post Head Share Options', 'fbconnect') ?></h3>
							</td>
						</tr>
	
	     				<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_add_post_share"><?php _e('Add share button:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_add_post_head_share" id="fb_add_post_head_share" <?php 
									echo get_option('fb_add_post_head_share') ? 'checked="checked"' : ''; ?> />
									<label for="fb_add_post_head_share"><?php _e('Add Facebook share button to post head', 'fbconnect') ?></label>
							</td>
						</tr>

	     				<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_add_post_like"><?php _e('Add like button:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_add_post_head_like" id="fb_add_post_head_like" <?php 
									echo get_option('fb_add_post_head_like') ? 'checked="checked"' : ''; ?> />
									<label for="fb_add_post_head_like"><?php _e('Add Facebook like button to post head', 'fbconnect') ?></label>
							</td>
						</tr>
	    				<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_add_post_head_send"><?php _e('Add send button:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_add_post_head_send" id="fb_add_post_head_send" <?php 
									echo get_option('fb_add_post_head_send') ? 'checked="checked"' : ''; ?> />
									<label for="fb_add_post_head_send"><?php _e('Add Facebook send button to post head', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_add_post_head_google1"><?php _e('Add google +1 button:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_add_post_head_google1" id="fb_add_post_head_google1" <?php 
									echo get_option('fb_add_post_head_google1') ? 'checked="checked"' : ''; ?> />
									<label for="fb_add_post_head_google1"><?php _e('Add google +1 button to post head', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="tw_add_post_head_share"><?php _e('Add twitter share:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="tw_add_post_head_share" id="tw_add_post_head_share" <?php 
									echo get_option('tw_add_post_head_share') ? 'checked="checked"' : ''; ?> />
									<label for="tw_add_post_head_share"><?php _e('Add Twitter share button to post head', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="li_add_post_head_share"><?php _e('Add Linked In share:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="li_add_post_head_share" id="li_add_post_head_share" <?php 
									echo get_option('li_add_post_head_share') ? 'checked="checked"' : ''; ?> />
									<label for="li_add_post_head_share"><?php _e('Add Linked In share button to post head', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<td colspan=2>
							<h3><?php _e('Post Footer Share Options', 'fbconnect') ?></h3>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_add_post_share"><?php _e('Add Facebook share button:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_add_post_share" id="fb_add_post_share" <?php 
									echo get_option('fb_add_post_share') ? 'checked="checked"' : ''; ?> />
									<label for="fb_add_post_share"><?php _e('Add Facebook share button to post footer', 'fbconnect') ?></label>
							</td>
						</tr>

						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_add_post_like"><?php _e('Add Facebook like button:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_add_post_like" id="fb_add_post_like" <?php 
									echo get_option('fb_add_post_like') ? 'checked="checked"' : ''; ?> />
									<label for="fb_add_post_like"><?php _e('Add Facebook like button to post footer', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_add_post_send"><?php _e('Add Facebook send button:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_add_post_send" id="fb_add_post_send" <?php 
									echo get_option('fb_add_post_send') ? 'checked="checked"' : ''; ?> />
									<label for="fb_add_post_send"><?php _e('Add Facebook send button to post footer', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_like_show_faces"><?php _e('Hide faces under like:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_like_show_faces" id="fb_like_show_faces" <?php 
									echo get_option('fb_like_show_faces') ? 'checked="checked"' : ''; ?> />
									<label for="fb_like_show_faces"><?php _e('Hide friends faces under like footer button', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_add_post_google1"><?php _e('Add google +1 button:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_add_post_google1" id="fb_add_post_google1" <?php 
									echo get_option('fb_add_post_google1') ? 'checked="checked"' : ''; ?> />
									<label for="fb_add_post_google1"><?php _e('Add google +1 button to post footer', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="tw_add_post_share"><?php _e('Add twitter share button:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="tw_add_post_share" id="tw_add_post_share" <?php 
									echo get_option('tw_add_post_share') ? 'checked="checked"' : ''; ?> />
									<label for="tw_add_post_share"><?php _e('Add Twitter share button to post footer', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="li_add_post_share"><?php _e('Add Linked In share button:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="li_add_post_share" id="li_add_post_share" <?php 
									echo get_option('li_add_post_share') ? 'checked="checked"' : ''; ?> />
									<label for="li_add_post_share"><?php _e('Add Linked In share button to post footer', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<td colspan=2>
							<h3><?php _e('General Share Options', 'fbconnect') ?></h3>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_add_page_head_share"><?php _e('Add share to page headers:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_add_page_head_share" id="fb_add_page_head_share" <?php 
									echo get_option('fb_add_page_head_share') ? 'checked="checked"' : ''; ?> />
									<label for="fb_add_page_head_share"><?php _e('Add share buttons to page header(not only posts)', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_add_page_share"><?php _e('Add share to page footers:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_add_page_share" id="fb_add_page_share" <?php 
									echo get_option('fb_add_page_share') ? 'checked="checked"' : ''; ?> />
									<label for="fb_add_page_share"><?php _e('Add share buttons to page footers (not only posts)', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_hide_home_head_share"><?php _e('Hide share in home headers:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_hide_home_head_share" id="fb_hide_home_head_share" <?php 
									echo get_option('fb_hide_home_head_share') ? 'checked="checked"' : ''; ?> />
									<label for="fb_hide_home_head_share"><?php _e('Hide share buttons in home header', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_add_page_share"><?php _e('Hide share in home footers:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="checkbox" name="fb_hide_home_share" id="fb_hide_home_share" <?php 
									echo get_option('fb_hide_home_share') ? 'checked="checked"' : ''; ?> />
									<label for="fb_hide_home_share"><?php _e('Hide share buttons in home footers', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="tw_userid"><?php _e('Twitter user:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="text" name="tw_userid" value="<?php echo get_option('tw_userid'); ?>" size="25" /> 
									<label for="tw_userid"><?php _e('Twitter user for shares (via @youruser)', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_share_head_style"><?php _e('Custom head styles:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="text" name="fb_share_head_style" value="<?php echo get_option('fb_share_head_style'); ?>" size="50" /> 
									<label for="fb_share_head_style"><?php _e('CSS custom styles', 'fbconnect') ?></label>
							</td>
						</tr>
						<tr valign="top">
							<th style="width: 33%" scope="row"><label for="fb_share_style"><?php _e('Custom footer styles:', 'fbconnect') ?></label></th>
							<td>
								<p><input type="text" name="fb_share_style" value="<?php echo get_option('fb_share_style'); ?>" size="50" /> 
									<label for="fb_share_style"><?php _e('CSS custom styles', 'fbconnect') ?></label>
							</td>
						</tr>
							     				</table>

						<?php wp_nonce_field('wp-fbconnect-share_update'); ?>
	     				<p class="submit"><input class="button-primary" type="submit" name="share_update" value="<?php _e('Update Configuration', 'fbconnect') ?> &raquo;" /></p>
	     			</form>
			</div>
