<?php
/**
 * @author: Paddy Miller (http://paddy.eu.com)
 * @date: 05/10/2011
 * @license: GPLv2
 */
function fb_getSignedRequest(){
	$fbclient = & facebook_client();
	return $fbclient->getSignedRequest();
}

function fb_getLoginUrl(){
	WPfbConnect::log("[fbConfig_phpsdk::getLoginUrl]",FBCONNECT_LOG_DEBUG);	
	$scope="";
	$scopeConf=get_option('fb_permsToRequestOnConnect');
	$app_id = get_option("fb_appId");
	if($scopeConf!=null && $scopeConf!='')
		$scope="&scope=" . $scopeConf;
	
	$uri = $_SERVER['REQUEST_URI'];
	if (get_option('fb_bloguri_base')!=""){
		$bloguribase = preg_replace('|/+$|', '',get_option('fb_bloguri_base') );
		$uri = str_replace($bloguribase,'', $_SERVER['REQUEST_URI']);
	}
	$canvasurl = preg_replace('|/+$|', '', FB_CANVAS_URL);
	$return_url = $canvasurl.$uri;
	$auth_url = "http://www.facebook.com/dialog/oauth?client_id=" . $app_id . "&redirect_uri=" . urlencode($return_url) . $scope;
	return $auth_url;
}


function fb_get_session(){
	WPfbConnect::log("[fbConfig_phpsdk::fb_get_session]",FBCONNECT_LOG_DEBUG);	
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			return $fbclient->getSession();
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_get_session] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_get_session] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}

function fb_get_access_token(){
	WPfbConnect::log("[fbConfig_phpsdk::fb_get_access_token]",FBCONNECT_LOG_DEBUG);	
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			return $fbclient->getAccessToken();
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_get_access_token] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_get_access_token] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}

function fb_get_appaccess_token(){
  /*$token_url = "https://graph.facebook.com/oauth/access_token?" .
    "client_id=" . $app_id .
    "&client_secret=" . $app_secret .
    "&grant_type=client_credentials";

  $app_access_token = file_get_contents($token_url);
	*/
	return get_appId() .'|'. get_api_secret();
}

function fb_fbml_refreshRefUrl($url){
	WPfbConnect::log("[fbConfig_phpsdk::fb_fbml_refreshRefUrl] url: ".$url,FBCONNECT_LOG_DEBUG);	
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$fbapi_client = & $fbclient->api_client;
			$resp = & $fbclient->api(array(
			  'method' => 'fbml.refreshRefUrl',
			  'url' => $url
			));
			return $resp;
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_users_hasAppPermission] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_users_hasAppPermission] url: ".$url,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_users_hasAppPermission] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}

						   
function fb_get_userPrmisions($fb_user){
	$perms = fb_fql_query("SELECT uid,status_update,photo_upload,sms,publish_stream,offline_access,email,create_event,rsvp_event,read_stream,share_item,create_note,bookmarked FROM permissions  WHERE uid ='".$fb_user."'");
	if ($perms!="" && $perms!="ERROR" && count($perms>0)){
		return $perms[0];
	}else{
		return $perms;
	}
}

function fb_get_userlike($uid,$page_id){
	$resp = fb_fql_query("SELECT page_id FROM page_fan WHERE page_id='$page_id' AND uid='$uid'");
	return $resp;
}

function fb_get_objectinfo($pageurl){
	$resp = fb_fql_query("select url, id,type,site from object_url where url='$pageurl'");
	return $resp;
}
function fb_get_friends($fb_user){
	$friends = fb_fql_query("SELECT uid2 FROM friend  WHERE uid1 ='".$fb_user."'");
	return $friends;
}

function fb_users_hasAppPermission($ext_perm, $uid=null) {
	WPfbConnect::log("[fbConfig_phpsdk::fb_users_hasAppPermission] PERMS: ".$ext_perm,FBCONNECT_LOG_DEBUG);	
	try{
		$fbclient = & facebook_client();

		if ($fbclient){
			$resp = & $fbclient->api(array(
			  'method' => 'users.hasAppPermission',
			  'ext_perm' => $ext_perm,
			  'uid' => $uid
			));
			return $resp;
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_users_hasAppPermission] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_users_hasAppPermission] PERMS: ".$ext_perm,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_users_hasAppPermission] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;	
}

function fb_admin_setRestrictionInfo($restriction_str = null){
	WPfbConnect::log("[fbConfig_phpsdk::fb_admin_setRestrictionInfo] ",FBCONNECT_LOG_DEBUG);	
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$resp = & $fbclient->api(array(
			  'method' => 'admin.setRestrictionInfo',
			  'restriction_str' => $restriction_info
			));
			return $resp;
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_admin_setRestrictionInfo] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		return "ERROR";
	}
	return null;
}

function fb_admin_getRestrictionInfo(){
	WPfbConnect::log("[fbConfig_phpsdk::fb_admin_getRestrictionInfo] ",FBCONNECT_LOG_DEBUG);	
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$resp = & $fbclient->api(array(
			  'method' => 'admin.getRestrictionInfo'
			));
			return $resp;
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_admin_getRestrictionInfo] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		return "ERROR";
	}
	return null;	
}
	
function fb_get_action_links_url(){
	$action_links = array(array('text' => 'Read more...', 'href' => 'http://paddy.eu.com'));
}


										   	
function fb_pages_isFan($page_id, $uid = null) {
	WPfbConnect::log("[fbConfig_phpsdk::fb_pages_isFan] PageId: ".$page_id." UID:".$uid,FBCONNECT_LOG_DEBUG);
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$resp = & $fbclient->api(array(
			  'method' => 'pages.isFan',
			  'page_id' => $page_id,
			  'uid' => $uid			  
			));
			WPfbConnect::log("[fbConfig_phpsdk::fb_pages_isFan] Resp: ".$resp);
			return $resp;
		}
	}catch (Exception $e) {
		print_r($e);
		WPfbConnect::log("[fbConfig_phpsdk::fb_pages_isFan] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_pages_isFan] PageId: ".$page_id." UID:".$uid,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_pages_isFan] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
	
}

function fb_get_loggedin_user() {
	WPfbConnect::log("[fbConfig_phpsdk::fb_get_loggedin_user] ",FBCONNECT_LOG_DEBUG);
	try{
		$fbclient = & facebook_client();
		global $fberror;
		if ($fberror!=""){
			return "";
		}
		
		if ($fbclient)
			return $fbclient->getUser();
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_get_loggedin_user] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_get_loggedin_user] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}

function fb_user_getInfo($fb_user) {
	WPfbConnect::log("[fbConfig_phpsdk::fb_user_getInfo] UID: ".$fb_user,FBCONNECT_LOG_DEBUG);
	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			//Llamando al nuevo API no se obtiene el country
			//$userinfo = & $fbclient->api("/".$fb_user);
			$userinfo = fb_fql_query("select username,website,about_me,email,proxied_email,profile_url,name,first_name,middle_name,last_name,birthday,birthday_date,current_location,locale,sex,pic,pic_with_logo,pic_small,pic_small_with_logo,pic_big_with_logo,pic_big,pic_square,pic_square_with_logo,affiliations,email_hashes,hometown_location,hs_info,education_history,interests,meeting_for,meeting_sex,movies,music,political,profile_update_time,proxied_email,quotes,relationship_status,religion,significant_other_id,timezone,tv,work_history,wall_count from user where uid='$fb_user'");
			
			if ($userinfo!="ERROR" && count($userinfo)>0){
				return $userinfo[0];
			}else{
				$fbclient->clearAllPersistentData();
				return $userinfo;
			}

		}
	}catch (Exception $e) {
		global $fberror;
		$fberror = print_r($e,true);
		WPfbConnect::log("[fbConfig_phpsdk::fb_user_getInfo] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_user_getInfo] UID: ".$fb_user,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_user_getInfo] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	return null;
}

function fb_stream_posts($uids,$fromdate=null){
	if (isset($fromdate) && $fromdate!=""){
		$datefilter="AND updated_time > ".$fromdate;
	}
	return fb_fql_query("SELECT post_id, actor_id, target_id, message,attachment,likes,updated_time,permalink FROM stream WHERE source_id IN (".$uids.") ".$datefilter);
}

function fb_stream_posts_likes($uid,$limit=5000,$fromdate="",$sincedate=""){
	if ($fromdate!=""){
		//$datefilter = "AND created_time<=".$fromdate;
		$datefilter = "AND updated_time<=".$fromdate;
	}
	if ($sincedate!=""){
		//$datefilter .= " AND created_time>=".$sincedate;
		$datefilter .= " AND updated_time>=".$sincedate;
	}
	return fb_fql_query("SELECT post_id,user_id FROM like WHERE post_id IN (select post_id from stream where source_id=$uid $datefilter) LIMIT $limit");
}

function fb_stream_likes_post($postid){
	return fb_fql_query("SELECT post_id,user_id FROM like WHERE post_id IN ('$postid')");
}

/*function fb_stream_comments($uid){
	return fb_fql_query("select xid,object_id,post_id,fromid,time,text,id,username,reply_xid from comment where post_id IN (select post_id from stream where source_id=$uid LIMIT 10)");
}*/
function fb_stream_comments($uid,$limit=5000,$fromdate="",$sincedate="",$offset=0){
	if ($fromdate!=""){
		//$datefilter = "AND created_time<=".$fromdate;
		$datefilter = "AND updated_time<=".$fromdate;
	}
	if ($sincedate!=""){
		//$datefilter .= " AND created_time>=".$sincedate;
		$datefilter .= " AND updated_time>=".$sincedate;
	}
	$fql = "select xid,object_id,post_id,fromid,time,text,id,username,reply_xid from comment where post_id IN (select post_id from stream where source_id=$uid $datefilter) ORDER BY time LIMIT $offset,$limit";
	//$fql = "select post_id from stream where source_id=$uid $datefilter";
	//echo $fql;
	$resp = fb_fql_query($fql);
	//print_r($resp);
	return $resp;
}

function fb_stream_comments_post($postid){
	
	$fql = "select xid,object_id,post_id,fromid,time,text,id,username,reply_xid from comment where post_id IN ('$postid')";
	//$fql = "select post_id from stream where source_id=$uid $datefilter";
	//echo $fql;
	$resp = fb_fql_query($fql);
	//print_r($resp);
	return $resp;
}

function fb_stream_comments_xid($xid){
	return fb_fql_query("select xid,object_id,post_id,fromid,time,text,id,username,reply_xid from comment where xid=$xid LIMIT 10");
}


function fb_get_user_pages($uid,$order="name"){
	return fb_fql_query("select page_id,name,type,pic_small,pic_big,pic_square,pic,pic_large,page_url,fan_count  from page where page_id IN (select page_id from page_fan where uid=$uid) order by ".$order);
}

function fb_get_user_pages_ids($uid){
	return fb_fql_query("select page_id from page_fan where uid=$uid");
}

function fb_get_page_info($pageid){
	return fb_fql_query("select page_id,username,general_info,name,bio,company_overview ,website,type,pic_small,pic_big,pic_square,pic,pic_large,page_url,location,fan_count  from page where page_id IN(".$pageid.")");
}

function fb_all_user_photos($userid){
	return fb_fql_query("SELECT aid,pid,src_small,src_small_height,src_small_width,src_big,src_big_height,src_big_width,caption,object_id FROM photo WHERE aid IN ( SELECT aid FROM album WHERE owner='".$userid."' ) ORDER BY created DESC");
}

function fb_all_user_album_photos($fb_user){
	if ($fb_user){
		$albums = fb_photos_getAlbums($fb_user);
		$newalbums=array();
		foreach($albums as $album){
			$album["photos"] = array();
			$newalbums[$album["aid"]] = $album;
		}
	
		$photos = fb_all_user_photos($fb_user);
		foreach($photos as $photo){
			$newalbums[$photo["aid"]]["photos"][]=$photo;
		}
		return $newalbums;
	}
	return "";
}
function fb_pages_getInfo($page_ids, $fields=null, $uid=null){
/*	if ($fields==null){
		$fields = array("page_id","name","type","pic_small","pic_big","pic_square","pic","pic_large","page_url","fan_count");
	}*/
	WPfbConnect::log("[fbConfig_phpsdk::fb_pages_getInfo] PageIDs: ".print_r($page_ids,true),FBCONNECT_LOG_DEBUG);
  	try{
		$fbclient = & facebook_client();
		if ($fbclient){		
			$resp = & $fbclient->api(array(
			  'method' => 'pages.getInfo',
			  'fields' => $fields,			  
			  'page_ids' => $page_ids,
  			  'uid' => $uid
			));
			return $resp;
		}
	}catch (Exception $e) {
		//print_r($e);
		WPfbConnect::log("[fbConfig_phpsdk::fb_pages_getInfo] ".$e->getCode(),FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_pages_getInfo] ".print_r($e,true),FBCONNECT_LOG_DEBUG);

		return "ERROR";
		
		//echo "Facebook connect error:".$e->getCode();
	}
	return null;
}

function fb_link_count($url){
	$url = rtrim($url,"/");
	$url = urlencode($url);
	$result = fb_fql_query("select url,total_count from link_stat where url IN (\"$url\",\"$url%2F\")");

	if ($result!="" && $result!="ERROR" && count($result)>0){
		$count =  $result[0]["total_count"];
		if (isset($result[1])){
			$count = $count + $result[1]["total_count"];
		}
		return $count;
	}else{
		return $result;
	}
}

function fb_link_count_array($url_array){
	$urls="";
	$separador = "";
	foreach ($url_array as $url){
		$urls = $urls.$separador;
		$urls = $urls."\"".$url."\"";
		$separador = ",";
	}
	return fb_fql_query("select url,total_count from link_stat where url IN ($urls)");
}

function fb_fql_query($query){
	WPfbConnect::log("[fbConfig_phpsdk::fb_fql_query] Query: ".$query,FBCONNECT_LOG_DEBUG);
  	try{
		$fbclient = & facebook_client();
		if ($fbclient){
			$params = array(
			  'method' => 'fql.query',
			  'query' => $query
			);
			/*global $use_offline_token;
			if ($use_offline_token){	
				$params['access_token'] = get_option('fb_offline_token');
			}*/	
			$resp = & $fbclient->api($params);
			return $resp;
		}
	}catch (Exception $e) {
		//print_r($e);
		WPfbConnect::log("[fbConfig_phpsdk::fb_fql_query] ".$e->getCode(),FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_fql_query] Query: ".$query,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_fql_query] ".print_r($e,true),FBCONNECT_LOG_DEBUG);

		return "ERROR";
		
		//echo "Facebook connect error:".$e->getCode();
	}
	return null;
}
function fb_logout(){
  	try{
		$fbclient = & facebook_client();
		if ($fbclient){		
			$fbapi_client = & $fbclient->api_client;
			$resp = & $fbclient->getLogoutUrl();
			//header( 'Location: '.$resp ) ;
			return $resp;
		}
	}catch (Exception $e) {
		return "ERROR";
		
		//echo "Facebook connect error:".$e->getCode();
	}

}

function fb_events_get($uid=null, $eids=null, $start_time=null, $end_time=null, $rsvp_status=null){
	WPfbConnect::log("[fbConfig_phpsdk::fb_events_get] UID: ".$uid." EIDS:".$eids,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$resp = & $fbclient->api(array(
			  'method' => 'events.get',
			  'uid' => $uid,
  			  'eids' => $eids,
  			  'start_time' => $start_time,			  
  			  'end_time' => $end_time,			  			  
  			  'rsvp_status' => $rsvp_status			  			  
			));
			return $resp;
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_events_get] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_events_get] UID: ".$uid." EIDS:".$eids,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_events_get] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
}

function fb_photos_getAlbums($uid=null, $aids=null) {
	WPfbConnect::log("[fbConfig_phpsdk::fb_photos_getAlbums] UID: ".$uid." AIDS:".$aids,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$resp = & $fbclient->api(array(
			  'method' => 'photos.getAlbums',
			  'uid' => $uid,			  
  		      'aids' => $aids
			));
			return $resp;
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_photos_getAlbums] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_photos_getAlbums] UID: ".$uid." AIDS:".$aids,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_photos_getAlbums] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}

}

function fb_photos_get($subj_id=null, $aid=null, $pids=null){
	WPfbConnect::log("[fbConfig_phpsdk::fb_photos_get] SUBJID: ".$subj_id." AID:".$aid." PIDS:".$pids,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$resp = & $fbclient->api(array(
			  'method' => 'photos.getAlbums',
			  'aid' => $aid,			  
  		      'pids' => $pids
			));
			return $resp;
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_photos_get] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_photos_get] SUBJID: ".$subj_id." AID:".$aid." PIDS:".$pids,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_photos_get] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
}		

function fb_hash($email) {
      $normalizedAddress = trim(strtolower($email));
      //crc32 outputs signed int
      $crc = crc32($normalizedAddress);
      //output in unsigned int format
      $unsignedCrc = sprintf('%u', $crc);
      $md5 = md5($normalizedAddress);
      return "{$unsignedCrc}_{$md5}";
}
	

function fb_users_setStatus($status,$uid = null,$clear = false,$status_includes_verb = true){
	WPfbConnect::log("[fbConfig_phpsdk::fb_users_setStatus] UID: ".$uid,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$resp = & $fbclient->api(array(
			  'method' => 'users.setStatus',
			  'status' => $status,			  
  		      'clear' => $clear,
  		      'status_includes_verb' => $status_includes_verb		  
			));
			return $resp;

		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_users_setStatus] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_users_setStatus] UID: ".$uid,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_users_setStatus] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}	
}	

function fb_stream_get($viewer_id = null,$source_ids = null,$start_time = 0,$end_time = 0,$limit = 30,$filter_key = '') {												  
	WPfbConnect::log("[fbConfig_phpsdk::fb_stream_get] Viewer ID: ".$viewer_id." SourcerIDS: ".$source_ids,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$resp = & $fbclient->api(array(
			  'method' => 'stream.get',
			  'viewer_id' => $viewer_id,			  
  		      'source_ids' => $source_ids,
  		      'start_time' => $start_time,			  
   		      'end_time' => $end_time,
   		      'limit' => $limit,
   		      'filter_key' => $filter_key
			));
			return $resp;
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_stream_get] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_stream_get] Viewer ID: ".$viewer_id." SourcerIDS: ".$source_ids,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_stream_get] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
		//print_r($e);
		//echo "ERROR";
	// nothing, probably an expired session
	}
}

function fb_stream_publish(
    $message, $attachment = null, $action_links = null, $target_id = null,
    $uid = null,$privacy_value="EVERYONE") {
	WPfbConnect::log("[fbConfig_phpsdk::fb_stream_publish] TARGET: ".$target_id." UID:".$uid,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){	
			$params = array(
				  'method' => 'stream.publish',
	  		      );
	  		if ($message!=""){
	  			$params['message']= $message;
	  		} 
	  		if ($attachment!=""){
	  			$params['attachment']=$attachment;
	  		} 
	  		if ($action_links!=""){
	  			$params['action_links']=$action_links;
	  		}   
			if ($target_id!=""){
	  			$params['target_id']=$target_id;
	  		}
			if ($uid!=""){
	  			$params['uid']=$uid;
	  		}    
			if ($privacy_value!=""){
				$privacyarray = array();
				$privacyarray["value"] = $privacy_value;
				$privacyjson = fb_json_encode($privacyarray);
	   		    $params['privacy'] = $privacyjson;
			}
			$resp = & $fbclient->api($params);
				
			return $resp;
		}
	}catch (Exception $e) {
		print_r($e);
		WPfbConnect::log("[fbConfig_phpsdk::fb_stream_publish] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_stream_publish] TARGET: ".$target_id." UID:".$uid,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_stream_publish] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
}

function fb_stream_addLike($post_id, $uid = null){
	WPfbConnect::log("[fbConfig_phpsdk::fb_stream_addLike] POSTID: ".$post_id." UID:".$uid,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$resp = & $fbclient->api(array(
			  'method' => 'stream.addLike',
   		      'uid' => $uid,
   		      'post_id' => $post_id
			));
			return $resp;
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_stream_addLike] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_stream_addLike] POSTID: ".$post_id." UID:".$uid,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_stream_addLike] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}

}

function fb_comments_add($xid, $text, $uid=0, $title='', $url='', $publish_to_stream=false,$object_id=null) {  
	WPfbConnect::log("[fbConfig_phpsdk::fb_comments_add] XID: ".$xid." Title:".$title,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$resp = & $fbclient->api(array(
			  'method' => 'comments.add',
   		      'text' => $text,
   		      'xid' => $xid,
   		      'uid' => $uid,
   		      'title' => $title,
   		      'url' => $url,	
   		      'publish_to_stream' => $publish_to_stream
			));
			return $resp;
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_comments_add] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_comments_add] XID: ".$xid." Title:".$title,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_comments_add] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
}

function fb_comments_get($xid=null,$object_id=null){
	WPfbConnect::log("[fbConfig_phpsdk::fb_comments_get] XID: ".$xid,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			if ($xid!=null && $xid!=""){
				$resp = & $fbclient->api(array(
				  'method' => 'comments.get',
	   		      'xid' => $xid
				));
			}else{
				$resp = & $fbclient->api(array(
				  'method' => 'comments.get',
	   		      'object_id' => $object_id
				));				
			}
			return $resp;
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_comments_get] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_comments_get] XID: ".$xid,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_comments_get] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
}
    	
function fb_friends_areFriends($uids1, $uids2) {
	WPfbConnect::log("[fbConfig_phpsdk::fb_friends_areFriends] UID1: ".$uids1." UID2: ".$uids2,FBCONNECT_LOG_DEBUG);
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$resp = & $fbclient->api(array(
				  'method' => 'friends.areFriends',
	   		      'uids1' => $uids1,
  	   		      'uids2' => $uids2
				));				
			return $resp;
		}
	}catch (Exception $e) {
		WPfbConnect::log("[fbConfig_phpsdk::fb_friends_areFriends] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_friends_areFriends] UID1: ".$uids1." UID2: ".$uids2,FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_friends_areFriends] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}	
}

function fb_json_encode($data){
	return json_encode($data);
}

function fb_dashboard_addGlobalNews($news, $image = null){
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			if ($image!=null & $image!=""){
				$resp = & $fbclient->api(array(
					  'method' => 'dashboard.addGlobalNews',
		   		      'news' => $news,
	  	   		      'image' => $image
					));				
			  }else{
			  	$resp = & $fbclient->api(array(
					  'method' => 'dashboard.addGlobalNews',
		   		      'news' => $news
					));				
			  }
			return $resp;
		}
	}catch (Exception $e) {
		print_r($e);
		WPfbConnect::log("[fbConfig_phpsdk::fb_dashboard_addGlobalNews] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_dashboard_addGlobalNews] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}		
}
function fb_dashboard_setCount($uid, $count){
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			$resp = & $fbclient->api(array(
			  'method' => 'dashboard.setCount',
   		      'uid' => $uid,
	   		  'count' => $count
			));	
		}
	}catch (Exception $e) {
		print_r($e);
		WPfbConnect::log("[fbConfig_phpsdk::fb_dashboard_setCount] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_dashboard_setCount] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
}

function fb_dashboard_publishActivity($activity, $image=null){
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
			if ($image!=null & $image!=""){
				$resp = & $fbclient->api(array(
					  'method' => 'dashboard.publishActivity',
		   		      'activity' => $activity,
	  	   		      'image' => $image
					));				
			  }else{
			  	$resp = & $fbclient->api(array(
					  'method' => 'dashboard.publishActivity',
		   		      'activity' => $activity
					));				
			  }
			return $resp;
		}
	}catch (Exception $e) {
		print_r($e);
		WPfbConnect::log("[fbConfig_phpsdk::fb_dashboard_publishActivity] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_dashboard_publishActivity] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}	
}

function fb_sendApprequests($fbuserid,$msg="",$data=""){
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
		  	$resp = & $fbclient->api("/".$fbuserid."/apprequests","POST",array(
					  'message' => $msg,
		   		      'data' => $data,
		  			  'access_token' => fb_get_appaccess_token()
					));				
			return $resp;
		}
	}catch (Exception $e) {
		print_r($e);
		WPfbConnect::log("[fbConfig_phpsdk::fb_sendApprequests] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_sendApprequests] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	
} 

function fb_getApprequests($fbuserid){
	try {
		$fbclient = & facebook_client();
		if ($fbclient){		
		  	$resp = & $fbclient->api("/".$fbuserid."/apprequests",array(
		  			  'access_token' => fb_get_appaccess_token()
					));				
			return $resp;
		}
	}catch (Exception $e) {
		print_r($e);
		WPfbConnect::log("[fbConfig_phpsdk::fb_sendApprequests] [Line: ".$e->getLine()."] [Code: ".$e->getCode()."] [MSG:".$e->getMessage()."]",FBCONNECT_LOG_ERR);
		WPfbConnect::log("[fbConfig_phpsdk::fb_sendApprequests] ".print_r($e,true),FBCONNECT_LOG_DEBUG);
		return "ERROR";
	}
	
} 
?>