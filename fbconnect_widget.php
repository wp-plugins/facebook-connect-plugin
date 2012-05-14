<?php
/**
 * @author: Paddy Miller (http://paddy.eu.com)
 * @date: 23/12/2008
 * @license: GPLv2
 */
?>

<div id="fbconnect_widget_div" >
	<div style="position:relative;text-align:right;">
	<a href="#" onclick="pinnedChange();return false;"><img id="fbconnect_pinned" src="<?php echo FBCONNECT_PLUGIN_URL;?>/images/maxim.gif"/></a>
</div>
<?php
$hidefacepile=false;
	include("fbconnect_widget_login.php");

	if ($avatarsize==""){
		$avatarsize = 50;
	}
?> 	

<div class="fbTabs">
        <ul class="tabNavigation">
            <li><a id="fbFirstA" class="selected" href="#fbFirst" onclick="fb_showTab('fbFirst');return false;"><?php _e('Visitors', 'fbconnect'); ?></a></li>
            <li><a id="fbSecondA" href="#fbSecond" onclick="fb_showTab('fbSecond');return false;"><?php _e('Friends', 'fbconnect'); ?></a></li>
			<li><a id="fbThirdA" href="#fbThird" onclick="fb_showTab('fbThird');return false;"><?php _e('Comments', 'fbconnect'); ?></a></li>
        </ul>

	<div id="fbFirst" class="fbtabdiv" >
	<div class="fbconnect_LastUsers">
	<div class="fbconnect_userpics">
	
<?php
	foreach($users as $las_user){
		if (isset($user) && $user->ID!=0 && $las_user->ID==$user->ID){
			echo '<a href="#">';
			$cierrelink = "</a>";
		}else{
			$cierrelink = "";
		}
		echo get_avatar( $las_user->ID,$avatarsize );
		echo $cierrelink;
	}
?>
	</div>
	<div class="fbwidgetfooter">
<?php 
	if(get_option('fb_connect_use_thick')){
		echo '<a title="'.__("Community","fbconnect").'" class="thickbox" href="'.$siteurl.'/?fbconnect_action=community&amp;height=400&amp;width=450">'.__('view more...', 'fbconnect').' </a>';
	}else{
		echo '<a href="'.$siteurl.'/?fbconnect_action=community'.'">'.__('view more...', 'fbconnect').' </a>';
	}
?>
	</div>
	</div>
	</div>
		
	<div id="fbSecond" style="display:none;visibility:hidden;" class="fbtabdiv">
	<div class="fbconnect_LastUsers">
<?php 	
	
	if(isset($fb_user) && $fb_user!=""){
		$friends = WPfbConnect_Logic::get_friends($user->ID,0,$maxlastusers);
		if(count($friends)>0){
			echo '<div class="fbconnect_userpics">';
			foreach($friends as $user){
						echo get_avatar( $user->ID,$avatarsize );
			}
		}else{
			echo '<div>';
			_e("You don't have friends on this site", 'fbconnect');
			echo ': <b><a href="'.$siteurl.'/?fbconnect_action=invite">'.__('Invite your friends!', 'fbconnect').'</a> </b> ';
		}
	}else{
		echo '<div>';
		_e("To see your friends on this site, you must be logged in with Facebook:", 'fbconnect');
		?>
		<a class="fb_button fb_button_<?php echo $loginbutton;?>" onclick="login_facebookjs('')">
		<span class="fb_button_text"><?php _e('Log In');?></span>
		</a>
		<?php 
	}
?>
	</div>
	<div class="fbwidgetfooter"><a href="<?php echo $siteurl.'/?fbconnect_action=community';?>"><?php _e('view more...', 'fbconnect')?></a></div>
	</div>
	</div>

	<div id="fbThird" style="display:none;visibility:hidden;" class="fbtabdiv">
	<div id="fbconnect_feedhead">
	<div class="fbTabs_feed">
	        <ul class="tabNavigation_feed">
<?php 	      
	if(isset($fb_user) && $fb_user!=""){
		echo '<li><a id="fbAllFriendsCommentsA" href="#fbAllFriendsComments" onclick="fb_showComments(\'fbAllFriendsComments\');return false;">'.__('Friends', 'fbconnect').'</a> </li>';
		echo '<li><a id="fbAllCommentsA" class="selected" href="#fbAllComments" onclick="fb_showComments(\'fbAllComments\');return false;">'.__('Full site', 'fbconnect').'</a></li>';	
	}
?>
	</ul>
	</div>
	</div>

	<div id="fbAllComments" class="fbconnect_LastComments">
<?php 
	global $fbconnect_filter;
	global $showPostTitle;
	$showPostTitle = true;
	$fbconnect_filter="fbAllComments";
	include( FBCONNECT_PLUGIN_PATH.'/fbconnect_feed.php');
?>
	</div>
<?php 
	if(isset($fb_user) && $fb_user!=""){
		echo '<div id="fbAllFriendsComments" style="display:none;visibility:hidden;" class="fbconnect_LastComments">';
		$fbconnect_filter="fbAllFriendsComments";
		global $showPostTitle;
		$showPostTitle = true;
		include( FBCONNECT_PLUGIN_PATH.'/fbconnect_feed.php');
		echo '</div>';
	}
?>
	</div> 
</div>
<div class="fbcreditos"><?php _e('Powered by', 'fbconnect'); ?> <a href="http://www.directory2009.com">http://www.directory2009.com</a></div>
</div>
<script type="text/javascript">
	fb_showTab('fbFirst');
</script>