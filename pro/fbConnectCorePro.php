<?php

if (! defined('FB_CANVAS_URL')){
	$canvasUrl= get_option('fb_canvas_url');
	$canvasUrl = sslFBConnectFilter($canvasUrl); 
    define('FB_CANVAS_URL',$canvasUrl);
}

if (isset($_REQUEST["fb_facebookapp_mode"])){
	
	$_SESSION["fb_facebookapp_mode"]= $_REQUEST["fb_facebookapp_mode"];
}

//print_r($_SESSION);
global $oldsiteurl;
global $oldhomeurl;
$oldsiteurl = get_option('siteurl');
$oldhomeurl = get_option('home');

if (isset($_REQUEST["signed_request"])){
	$signed_request = fb_getSignedRequest();
	if (isset($signed_request["app_data"])){
		global $fb_signed_request_appdata;
		parse_str($signed_request["app_data"],$fb_signed_request_appdata);
	}
	
}

if (isset($_REQUEST["fb_sig_in_canvas"]) || isset($_REQUEST["signed_request"]) || (isset($_REQUEST["fb_app_mode"]) && $_REQUEST["fb_app_mode"]=="on") || (isset($_SESSION["fb_facebookapp_mode"]) && $_SESSION["fb_facebookapp_mode"]=="on") || isset($_REQUEST["fb_sig_in_canvas"]) || isset($_REQUEST["fb_sig_is_ajax"])){
		
	define ('FBCONNECT_CANVAS', "appcanvas");

	if( get_option('fb_canvas_redirect') ){
		fb_redirect_canvas();
	}
	
}else{
	define ('FBCONNECT_CANVAS', "web");
}

function fb_redirect_canvas(){
	global $post;
	
	if(get_option('fb_canvas_redirect')=="site" ){
		global $oldsiteurl;
		?>
		<script>
		top.location = "<?php echo $oldsiteurl;?>";
		</script>
		<?php 
		exit;
	}
}
					
?>