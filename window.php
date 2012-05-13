<?php

// look up for the path
require_once("../../../wp-config.php");

// check for rights
if ( !is_user_logged_in() || !current_user_can('edit_posts') ) 
	wp_die(__("You are not allowed to be here"));

global $wpdb, $nggdb;

?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>NextGEN Gallery</title>
	<meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php echo get_option('blog_charset'); ?>" />
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo FBCONNECT_PLUGIN_URL ?>/js/jquery-1.4.2.js"></script>		
	<script language="javascript" type="text/javascript" src="<?php echo FBCONNECT_PLUGIN_URL ?>/js/jquery.ui.core.min.js"></script>			
	<script language="javascript" type="text/javascript" src="<?php echo FBCONNECT_PLUGIN_URL ?>/js/jquery.ui.widget.min.js"></script>		
	<script language="javascript" type="text/javascript" src="<?php echo FBCONNECT_PLUGIN_URL ?>/js/jquery.ui.position.min.js"></script>				
	<script language="javascript" type="text/javascript" src="<?php echo FBCONNECT_PLUGIN_URL ?>/js/jquery.ui.autocomplete.min.js"></script>		
	<script language="javascript" type="text/javascript" src="<?php echo NGGALLERY_URLPATH ?>admin/tinymce/tinymce.js"></script>
	<link rel="stylesheet" href="<?php echo FBCONNECT_PLUGIN_URL ?>/css/jquery.ui.all.css" type="text/css" media="screen" />
	<base target="_self" />
</head>
<body id="link" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" style="display: none">
<div class="demo">
	<div id="project-label">Select a project (type "j" for a start):</div>
	<img id="project-icon" src="images/transparent_1x1.png" class="ui-state-default"/>
	<input id="project"/>
	<input type="hidden" id="project-id"/>
	<p id="project-description"></p>
</div><!-- End demo -->
	
<style type="text/css">
	#feedback { font-size: 10px; }
	.selectableol .ui-selecting { 
		background: #FECA40; 
	}
	
	.selectableol .ui-selected { 
		background: #F39814; 
		color: white; 
		border:1px solid;
	}
	
	.selectableol { 
		list-style-type: none; 
		margin: 0; 
		padding: 0; 
	}
	
	.selectableol li {		
		list-style-type: none; 
		margin-right: 10px;		
		margin-top: 5px;
		margin-bottom: 25px;
		padding: 2px; 
		float: left; 
		width: 50px; 
		height: 50px; 
		font-size: 10px; 
		text-align: center;
		position:relative; 
	}
	.selectableol li .titulo{
		background-color:#FFFFFF;
		color:#000000;
		left:-3px; 
		top: 54px; 
		position:absolute;
		width: 60px;
		cursor: pointer;
		font-weight:normal;
	}
	</style>
<?php

$urlfriends =  "https://graph.facebook.com/me/friends?access_token=".fb_get_access_token();
//echo "https://graph.facebook.com/me/friends?access_token=".fb_get_access_token();
?>

<script type=text/javascript>
<?php
	/*$fb_user = fb_get_loggedin_user();
	$friends = fb_get_friends_info($fb_user);
	echo "var friends=".json_encode($friends).";";*/
?>
var friends="";
var autocompletefriends = [];
var objid="";
var objtype="";
var objname="";
var aftersearch=false;
var maxfql = 12;
var startfql = 0;
var searchtype = "friends";

function anterior(){
	startfql -= maxfql;
	jQuery( "#project" ).autocomplete( "search" );
}

function siguiente(){
	startfql += maxfql;
	jQuery( "#project" ).autocomplete( "search" );
}

function cargaSearch(search){

var filter ="";
if (search!=""){
	filter = "AND strpos(upper(name),'" + search.toUpperCase() + "')>=0 ";
}
FB.api( '/search', { 
			q: "Coca",
			 limit: 3 }, 
        function(result) { 
	//alert("carga2"+result);
		friends=result;
		var cadfriends = "";
		//alert(""+friends[1]["id"]);
		autocompletefriends = [];
		for (i = 0; i < friends.length; i++) {
			
			autocompletefriends[i] = {
				value: friends[i]["page_id"],
				label: friends[i]["name"],
				desc: friends[i]["name"],
				icon: "http://graph.facebook.com/" + friends[i]["page_id"] + "/picture"
			};
		}
		aftersearch = true;
		$( "#project" ).autocomplete( "search" );
		$( "#project" ).focus();
	});
	
}

function cargaPaginas(search){

var filter ="";
if (search!=""){
	filter = "AND strpos(upper(name),'" + search.toUpperCase() + "')>=0 ";
}
FB.api( 
        { 
            method: 'fql.query', 
            query: "select page_id,name,fan_count  from page where page_id IN (select page_id from page_fan where uid="+fb_userid+") "+filter+" order by name LIMIT "+startfql+","+maxfql
        }, 
        function(result) { 
	//alert("carga2"+result);
		friends=result;
		var cadfriends = "";
		//alert(""+friends[1]["id"]);
		autocompletefriends = [];
		for (i = 0; i < friends.length; i++) {
			
			autocompletefriends[i] = {
				value: friends[i]["page_id"],
				label: friends[i]["name"],
				desc: friends[i]["name"],
				icon: "http://graph.facebook.com/" + friends[i]["page_id"] + "/picture"
			};
		}
		aftersearch = true;
		$( "#project" ).autocomplete( "search" );
		$( "#project" ).focus();
	});
	
}

function cargaFriends(search){
	 var filter ="";
	if (search!=""){
		filter = "AND strpos(upper(concat(first_name, ' ', last_name)),'" + search.toUpperCase() + "')>=0 ";
	}
	//alert("carga "+search);
	//FB.api('/me/friends', function(result) {
	FB.api( 
        { 
            method: 'fql.query', 
            query: "SELECT uid, name, first_name, last_name FROM user WHERE  uid IN (SELECT uid2 FROM friend WHERE uid1 = "+fb_userid+") "+filter+" order by first_name LIMIT "+startfql+","+maxfql
        }, 
        function(result) { 
	//alert("carga2"+result);
		friends=result;
		var cadfriends = "";
		//alert(""+friends[1]["id"]);
		autocompletefriends = [];
		for (i = 0; i < friends.length; i++) {
			
			autocompletefriends[i] = {
				value: friends[i]["uid"],
				label: friends[i]["name"],
				desc: friends[i]["name"],
				icon: "http://graph.facebook.com/" + friends[i]["uid"] + "/picture"
			};
		}
		aftersearch = true;
		$( "#project" ).autocomplete( "search" );
	});
}

function initAutoComplete(page){
	/*	for(i=0; i< friends.length ; i++ ){
			autocompletefriends[i] = {
				value: friends[i]["id"],
				label: friends[i]["name"],
				desc: friends[i]["name"],
				icon: "http://graph.facebook.com/"+friends[i]["id"]+"/picture"
			};
			//alert("carga"+friends[i]["id"]);
			if (i < 10) {
				cadfriends += '<li id="user_' + friends[i]["id"] + '" class="ui-state-default">';
				cadfriends += '<img width="50" height="50" src="http://graph.facebook.com/' + friends[i]["id"] + '/picture" class="avatar photo avatar-avatar">';
				cadfriends += '<div id="user_' + friends[i]["id"] + '_name" class="titulo">' + friends[i]["name"] + '</div></li>';
			}
			
		}
		*/
		//cargaPaginas("");
		$( "#project" ).autocomplete({
			minLength: 0,
			source: function(request, response){
				//alert("source "+autocompletefriends.length);
				response( autocompletefriends );
			}
			,
			search: function(event, ui){
				//alert(this.value);
				//alert("serch");
				if (aftersearch == false) {
					loadSearch(this.value);
				}else{
					aftersearch = false;
				}
			}
			,
			open:function( event, ui ) {
				$(".ui-autocomplete").appendTo("#"+searchtype+"_panel");
				$(".ui-autocomplete").css("top","");
				$(".ui-autocomplete").css("left","");
				$(".ui-autocomplete").css("width","450px");
				$("#navbar").css("display","block");
				return false;
			},
			focus: function( event, ui ) {
				//$( "#project" ).val( ui.item.label );
				$( "#project-icon" ).attr( "src", ui.item.icon );
				return false;
			},
			select: function( event, ui ) {
				$( "#project" ).val( ui.item.label );
				$( "#project-id" ).val( ui.item.value );
				$( "#project-description" ).html( ui.item.desc );
				$( "#project-icon" ).attr( "src", ui.item.icon );

				return false;
			}
		})
		.data( "autocomplete" )._renderItem = function( ul, item ) {
			return $( "<li></li>" )
				.data( "item.autocomplete", item )
				.append( "<a style=\"display:block;height:45px;\"><img style=\"width:35px;display:inline;float:left;\" src=\""+item.icon +"\"/>"+ item.label + "</a>")
				.appendTo( ul );
		};
		//$( "#project" ).autocomplete( "search" ,"A");

		loadSearch("");
	//	jQuery("#finli").before(cadfriends);
	
}

function loadSearch(search){
	if (searchtype == "friends"){
		cargaFriends(search);
	}else if(searchtype == "pages"){
		cargaPaginas(search);
	}
}

function insertFBLink() {
	
	var tagtext;
	var singlepicid =1;
	
	if (objid != "" ) {
			tagtext = '<a target="_blank" href="http://www.facebook.com/profile.php?id='+objid+'">'+objname+'</a>';
	} else {
		tinyMCEPopup.close();
	}
	
	if(window.tinyMCE) {
		//TODO: For QTranslate we should use here 'qtrans_textarea_content' instead 'content'
		window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, tagtext);
		//Peforms a clean up of the current editor HTML. 
		//tinyMCEPopup.editor.execCommand('mceCleanup');
		//Repaints the editor. Sometimes the browser has graphic glitches. 
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
	}
	return;
}



/*	jQuery(document).ready(function($){
		$("#selectable_user").selectable({
			stop: function(){				
				$(".ui-selected", this).each(function(){					
					//var index = $("#selectable_video li").index(this);
					var temp = new Array();
					var objAttr = $(this).attr("id");
					
					temp = objAttr.split('_');
					objid = temp[1];
					objtype= temp[0];
					objname=$("#"+objAttr).text();
						//$('#videoID').attr('value', video_selected);
						//url_imgvideo = url_base +"/media/videoG_" + video_selected+".jpg";					
				});				
			}
		});
		
 
	});
 */

function changeTab(name){
	mcTabs.displayTab(name+'_tab',name+'_panel');
	searchtype = name;
	startfql = 0;
	jQuery( "#project" ).autocomplete( "search" );
}
</script>
	
<!-- <form onsubmit="insertLink();return false;" action="#"> -->
	<form name="NextGEN" action="#">
	<div class="tabs">
		<ul>
			<li id="friends_tab" class="current"><span><a href="javascript:changeTab('friends');" onmousedown="return false;"><?php echo _n( 'friends', 'Friends', 1, 'nggallery' ) ?></a></span></li>
			<li id="pages_tab"><span><a href="javascript:changeTab('pages');" onmousedown="return false;"><?php echo _n( 'pages', 'Pages', 1, 'nggallery' ) ?></a></span></li>
			<li id="search_tab"><span><a href="javascript:changeTab('search');" onmousedown="return false;"><?php _e('Picture', 'nggallery'); ?></a></span></li>
		</ul>
	</div>
	
	<div class="panel_wrapper" id="panel_wrapper" style="height:200px;">
		<!-- friends panel -->

		<div id="friends_panel" class="panel current">

		</div>
		<!-- gallery panel -->
		
		<!-- pages panel -->
		<div id="pages_panel" class="panel">
		
		</div>
		<!-- album panel -->
		
		<!-- single pic panel -->
		<div id="search_panel" class="panel">
		<br />
		<table border="0" cellpadding="4" cellspacing="0">
         <tr>
            <td nowrap="nowrap"><label for="singlepictag"><?php _e("Select picture", 'nggallery'); ?></label></td>
            <td><select id="singlepictag" name="singlepictag" style="width: 200px">
                <option value="0"><?php _e("No picture", 'nggallery'); ?></option>
				<?php
					/*$picturelist = $wpdb->get_results("SELECT * FROM $wpdb->nggpictures ORDER BY pid DESC");
					if(is_array($picturelist)) {
						foreach($picturelist as $picture) {
							echo '<option value="' . $picture->pid . '" >'. $picture->pid . ' - ' . $picture->filename.'</option>'."\n";
						}
					}*/
				?>
            </select></td>
          </tr>
          <tr>
            <td nowrap="nowrap"><?php _e("Width x Height", 'nggallery'); ?></td>
            <td><input type="text" size="5" id="imgWidth" name="imgWidth" value="320" /> x <input type="text" size="5" id="imgHeight" name="imgHeight" value="240" /></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="top"><?php _e("Effect", 'nggallery'); ?></td>
            <td>
				<label><select id="imgeffect" name="imgeffect">
					<option value="none"><?php _e("No effect", 'nggallery'); ?></option>
					<option value="watermark"><?php _e("Watermark", 'nggallery'); ?></option>
					<option value="web20"><?php _e("Web 2.0", 'nggallery'); ?></option>
				</select></label>
			</td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="top"><?php _e("Float", 'nggallery'); ?></td>
            <td>
				<label><select id="imgfloat" name="imgfloat">
					<option value=""><?php _e("No float", 'nggallery'); ?></option>
					<option value="left"><?php _e("Left", 'nggallery'); ?></option>
					<option value="center"><?php _e("Center", 'nggallery'); ?></option>
					<option value="right"><?php _e("Right", 'nggallery'); ?></option>
				</select></label>
			</td>
          </tr>

        </table>
		</div>
		<!-- single pic panel -->
		<div id="navbar" style="margin-top:60px;">
			<a href="#" onclick="anterior();">Anterior</a>
			<a href="#" onclick="siguiente();">Siguiente</a>
		</div>
	</div>

	<div class="mceActionPanel">
		<div style="float: left">
			<input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", 'nggallery'); ?>" onclick="tinyMCEPopup.close();" />
		</div>

		<div style="float: right">
			<input type="submit" id="insert" name="insert" value="<?php _e("Insert", 'nggallery'); ?>" onclick="insertFBLink();" />
		</div>
	</div>
</form>
<?php
WPfbConnect_Logic::fbconnect_init_scripts("initAutoComplete");
?>


</body>
</html>
