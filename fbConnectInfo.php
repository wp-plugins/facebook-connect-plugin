<?php
/**
 * @author: Paddy Miller (http://paddy.eu.com)
 * @date: 05/10/2011
 * @license: GPLv2
 */

include_once 'fbConfig.php';

	global $wp_version, $fbconnect,$fb_reg_formfields;

			
			// Display the options page form
			$siteurl = get_option('home');
			if( substr( $siteurl, -1, 1 ) !== '/' ) $siteurl .= '/';
			?>
			<div class="wrap">
				<h2><?php _e('Facebook Connect Plugin', 'fbconnect') ?></h2>
<fb:fan profile_id="62885075047" stream="1" connections="10" logobar="1" width="600"></fb:fan>
<div style="font-size:12px; padding-left:10px"><a href="http://paddy.eu.com/">Paddy Miller</a> </div>


			</div>
