<?php
/**
 * @author: Paddy Miller (http://paddy.eu.com)
 * @date: 23/12/2008
 * @license: GPLv2
 */
?> 	

<div class="fbconnect_LastUsers">
	<div class="fbconnect_title"><?php echo $welcometext; ?></div>
	<div class="fbconnect_userpics">
	
<?php
	foreach($users as $last_user){
			echo get_avatar( $last_user->ID,35 );
	}

	echo '</div>';
	echo '<div style="text-align:right;"><a href="'.$siteurl.'/?fbconnect_action=community'.'">'.__('view more...', 'fbconnect').' </a></div>';
?> 
</div>

