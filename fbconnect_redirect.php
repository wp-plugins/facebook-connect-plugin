<?php
error_reporting(E_ALL & ~E_DEPRECATED);
require_once("../../../wp-config.php");

$user = wp_get_current_user();
$fb_user = fb_get_loggedin_user();

if (isset($_GET['urlredirect'])){
	if ($fb_user!="" && $user!="" && $user->ID!=0){
		header( 'Location: '.$_GET['urlredirect'] ) ;
	}
}
?>
