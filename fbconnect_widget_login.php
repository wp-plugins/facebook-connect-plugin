<?php
/**
 * @author: Paddy Miller (http://paddy.eu.com)
 * @date: 23/12/2008
 * @license: GPLv2
 */
?> 	
<?php
$fb_user = fb_get_loggedin_user();
$user = wp_get_current_user();
$siteurl = get_option('siteurl');

$pageurl = $siteUrl."/index.php";
if (!is_home()){
	global $post;
	if ($post!=""){
		$pageurl = get_permalink($post->ID);
	}
}

if ($loginbutton != "small" && $loginbutton != "medium" && $loginbutton != "large" && $loginbutton != "xlarge"){
	$loginbutton = "medium";
}
if ( $user->ID && $user->ID!=0) {
		echo '<div class="fbconnect_miniprofile">';
		echo $welcometext;
		echo '<div style="margin:2px;clear:both;"></div>';
		echo '<div class="fbconnect_userpicmain_cont">';
		echo '<div class="fbconnect_userpicmain">'.get_avatar( $user->ID,50 ).'</div>';
		echo '</div>';
		$linked = get_option('fb_connect_avatar_link');
		if ($linked=="on"){
				echo '<a href="http://www.facebook.com/profile.php?id='.$fb_user.'"><b>'.$user->display_name.'</b></a>';
		}else{
				echo '<a href="'.$siteurl.'/?fbconnect_action=myhome&amp;userid='.$user->ID.'"><b>'.$user->display_name.'</b></a>';
		}
		if (!get_option('fb_hide_edit_profile')){
			if(get_option('fb_custom_reg_form') && get_option('fb_custom_reg_form')!=""){
				echo '<br/><a href="'.$siteurl.get_option('fb_custom_reg_form').'">[ '.__('Edit profile', 'fbconnect').' ]</a>';
			}elseif(get_option('fb_show_reg_form') && get_option('fb_connect_use_thick')){
				echo '<br/><a class="thickbox" href="'.$siteurl.'?fbconnect_action=register&amp;height='.FBCONNECT_TICKHEIGHT.'&amp;width='.FBCONNECT_TICKWIDTH.'">[ '.__('Edit profile', 'fbconnect').' ]</a>';
			}elseif (get_option('fb_show_reg_form')){
				echo '<br/><a href="'.$siteurl.'?fbconnect_action=register">[ '.__('Edit profile', 'fbconnect').' ]</a>';
			}else{
					echo '<br/><a href="'.$siteurl.'/wp-admin/profile.php">[ '.__('Edit profile', 'fbconnect').' ]</a>';
			}
		}
		
		if ( $fb_user && $fb_user==$user->fbconnect_userid){
			//echo '<br/> <a href="'.$siteurl.'/?fbconnect_action=invite">[ '.__('Invite', 'fbconnect').' ]</a>';
			$requestfriends = "fbInviteFriends('".get_option('blogname')." : ".get_option('blogdescription')."')";
			echo '<br/> <a href="#" onclick="'.$requestfriends.'">[ '.__('Invite', 'fbconnect').' ]</a>';
		}
		
		//echo '<br/><a href="#" onclick="FB.logout(function(result) { window.location = \''.$siteurl.'/?fbconnect_action=logout'.'\'; });return false;">[ '.__('Logout', 'fbconnect').' ]</a>';
		echo '<br/><a href="#" onclick="logout_facebook();return false;">[ '.__('Logout', 'fbconnect').' ]</a>';
		echo '</div>';

	}
	
	if ( $fb_user && $fb_user==$user->fbconnect_userid){
		//echo '<div><fb:prompt-permission title="We don\'t store your email, and you can stop the notifications from Facebook" perms="email" class="FB_ElementReady"/><img src="'.FBCONNECT_PLUGIN_URL.'/images/sobre.gif"/> Allow notifications?</fb:prompt-permission></div>';
		
		//echo "<input type=\"button\" value=\"".$invitetext."\" style=\"width:100%;\" onclick=\"location.href='".$siteurl."/?fbconnect_action=invite'\"/>";
		//echo '<div id="fbinvitebutton"><a href="'.$siteurl.'/?fbconnect_action=invite">'.$invitetext.'</a></div>';
	/*}elseif (WPfbConnect_Logic::getMobileClient()!=""){
		echo "<div class=\"invitebutton\">";
		echo __('Login with Facebook:', 'fbconnect')."<br/>";	
		echo '<a href="'.fb_get_fbconnect_tos_url().'"><img src="'.FBCONNECT_PLUGIN_URL.'/images/Connect_with_facebook_iphone.png" /></a>';
		echo "</div>";*/
	}else{
		echo "<div id=\"fbloginbutton\" class=\"invitebutton\">";
		
		if(!isset($hidelogintext) && !$hidelogintext){
			echo __('Login with Facebook:', 'fbconnect')."<br/>";	
		}
		?>
		<a class="fb_button fb_button_<?php echo $loginbutton;?>" onclick="login_facebookjs('')">
		<span class="fb_button_text"><?php _e('Log In');?></span>
		</a>
		</div>
		<?php 		
	}

?> 
<script>
function invitarAmigos() {
	var mensajeFinal="<?php echo get_option('blogname')." : ".get_option('blogdescription'); ?>";
	FB.ui({method: 'apprequests', message: <?php echo json_encode(get_option('blogname')." : ".get_option('blogdescription')); ?>, data: ''});
}
</script>