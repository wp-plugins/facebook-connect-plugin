function show_feed(template_id,data,callback,user_message_prompt,user_message_txt){
		user_message = {value: user_message_txt};
		FB.Connect.showFeedDialog(template_id, data,null, null, null , FB.RequireConnect.promptConnect,callback);
}
var fb_refreshURL = unescape(window.location.pathname);

function fb_add_urlParam(url,param){
	if (param != ""){
		pos = url.indexOf("?",0);
		if (pos == -1) {
			return url + "?" + param;
		}else{
			return url + "&" +param;
		}
	}else{
		return url;
	}

}

function login_facebook(url){
	if(url!=null && url!=""){
		window.location.href = url;	
	}else{
		//alert("REDIRECT"+fb_canvas_url);
		//top.location = "http://apps.facebook.com/wordpressbloggers/";
		top.location = fb_pageurl;
	}
}

function login_facebookjs(urlredirect){
	 FB.login(function(response) {
		   if (response.authResponse) {
			   if (urlredirect!=""){
				   top.location = urlredirect;
			   }else{
				   top.location = fb_pageurl;
			   }
		   } else {
		     //alert('User cancelled login or did not fully authorize.');
		   }
		 }, {scope: fb_requestperms});
}

function logout_facebook(){
	if (FB.getUserID()==0){
		window.location = fb_add_urlParam(fb_pageurl,"fbconnect_action=logout&fbclientuser=0");
	}else{
		FB.logout(function(result) { 
			window.location = fb_add_urlParam(fb_pageurl,"fbconnect_action=logout&fbclientuser=0");
		});	
	}
}

function fb_view_moresiteinfo(thehref,domain,pos){
		var activity= '<iframe src="http://www.facebook.com/plugins/activity.php?site='+domain+'&amp;width=250&amp;height=270&amp;header=true&amp;colorscheme=dark&amp;border_color=#000000" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:250px; height:270px;margin-right:10px;"></iframe>';
		var recommendations= '<iframe src="http://www.facebook.com/plugins/recommendations.php?site='+domain+'&amp;width=250&amp;height=270&amp;header=true&amp;colorscheme=dark&amp;border_color=#000000" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:250px; height:270px"></iframe>';
		jQuery('#moreinfo'+pos).replaceWith("<br/>"+activity+recommendations);
}

function fb_links_info(){
	var pos=0;
	jQuery(document).ready(function($) {
 //$('.entry a').each(function () {	
  $('a').each(function () {
    // options
	var thehref = $(this).attr("href");
	var domain = thehref.split(/\/+/g)[1]; 
	pos++;
	//$(this).append('<div class="bubbleInfo"><span class="trigger"> AAA </span> <div class="popup"><div id="fb_din_like"><fb:like href="'+thehref+'"/>-</fb:like></div>"</div></div>');
	var activity= '<iframe src="http://www.facebook.com/plugins/activity.php?site='+domain+'&amp;width=250&amp;height=270&amp;header=true&amp;colorscheme=dark&amp;border_color=#000000" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:250px; height:270px;margin-right:10px;"></iframe>';
	var recommendations= '<iframe src="http://www.facebook.com/plugins/recommendations.php?site='+domain+'&amp;width=250&amp;height=270&amp;header=true&amp;colorscheme=dark&amp;border_color=#000000" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:250px; height:270px"></iframe>';
	var like='<iframe src="http://www.facebook.com/plugins/like.php?href='+thehref+'&amp;layout=standard&amp;show_faces=true&amp;width=480&amp;action=like&amp;colorscheme=dark" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:480px; height:70px"></iframe>';
	$(this).append('<span class="bubbleInfo"><span class="trigger"></span> <div class="popup"><div class="borderpopup"></div><div class="popupIn"><div id="fb_din_like">'+like+'<br/><a href="#" onmouseover="javascript:fb_view_moresiteinfo(\''+thehref+'\',\''+domain+'\',\''+pos+'\');">More site info ['+domain+']</a><div id="moreinfo'+pos+'"></div><div class="poweredpop"><a href="http://paddy.eu.com">Powered by Sociable!</a></div></div></div></div></span>');
    var distance = 11;
	var distancex = 20;
    var time = 250;
    var hideDelay = 500;

    var hideDelayTimer = null;

    // tracker
    var beingShown = false;
    var shown = false;
    
    var trigger = $('.trigger', this);
	//var trigger = this;

    //var popup = $('#popupid').css('opacity', 0);
	var popup = $('.popup', this).css('opacity', 0);


    $([this, popup.get(0)]).mouseover(function () {

	    
    // set the mouseover and mouseout on both element
    //$([trigger, popup.get(0)]).mouseover(function () {
		//alert("dentro");
      // stops the hide event if we move from the trigger to the popup element
      if (hideDelayTimer) clearTimeout(hideDelayTimer);

      // don't trigger the animation again if we're being shown, or already visible
      if (beingShown || shown) {
        return;
      } else {
        beingShown = true;

        // reset position of popup box
        popup.css({
           top: '-80px',
		  left: '-80px',
          display: 'block' // brings the popup back in to view
        })

        // (we're using chaining on the popup) now animate it's opacity and position
        .animate({
          top: distance + 'px',
		  left: '-'+distancex+'px',
          opacity: 1
        }, time, 'swing', function() {
          // once the animation is complete, set the tracker variables
          beingShown = false;
          shown = true;
        });
      }
    }).mouseout(function () {
      // reset the timer if we get fired again - avoids double animations
      if (hideDelayTimer) clearTimeout(hideDelayTimer);
      
      // store the timer so that it can be cleared in the mouseover if required
      hideDelayTimer = setTimeout(function () {
        hideDelayTimer = null;
        popup.animate({
          top: distance + 'px',
  		  left: '-'+distancex+'px',
          opacity: 0
        }, time, 'swing', function () {
          // once the animate is complete, set the tracker variables
          shown = false;
          // hide the popup entirely after the effect (opacity alone doesn't do the job)
          popup.css('display', 'none');
        });
      }, hideDelay);
    });
  });
});
}
function fb_links_info2(){
	jQuery(document).ready(function($) {
		$('a').hover(
  function () {
  	var thehref = $(this).attr("href");
  	//alert($(this).attr("href"));
    $(this).append($("<div id='fb_din_like'><fb:like href='"+thehref+"'/>-</fb:like></div>"));
	FB.XFBML.parse();
  }, 
  function () {
    //$("#fb_din_like").remove();
  }
);

	});
}

function fb_links_canvas(){

jQuery(document).ready(function($) {

	
	if (fb_signed_request!=""){
		$('a').click(
		  function () {
			  
		  	var thehref = $(this).attr("href");
		  	var domain = thehref.split(/\/+/g)[1];
		  	
		  	if (thehref.indexOf(domain)>-1){
		  		//alert($(this).attr("href")+"?signed_request="+fb_signed_request);
		  		if (thehref.indexOf('?')>-1){
		  			thehref = thehref+"&signed_request="+fb_signed_request;
		  		}else{
		  			thehref = thehref+"?signed_request="+fb_signed_request;
		  		}
		  		$(this).attr("href",thehref);
		  	}
		  }
		);
		$('form').submit(
				  function () {
				  	var thehref = $(this).attr("action");
				  	var domain = thehref.split(/\/+/g)[1]; 
				  	if (thehref.indexOf(domain)>-1){
				  		if (thehref.indexOf('?')>-1){
				  			thehref = thehref+"&signed_request="+fb_signed_request;
				  		}else{
				  			thehref = thehref+"?signed_request="+fb_signed_request;
				  		}
				  		$(this).attr("action",thehref); 
				  	}
				  }
		);
	}
	
});
}


function login_facebook3(urlajax){
	jQuery(document).ready(function($) {
		$('.fbconnect_login_div').load(urlajax+'?checklogin=true&refreshpage=fbconnect_refresh');
	});
}

function login_facebookForm(){
	jQuery(document).ready(function($) {
		$('#fbconnect_reload2').show(); 
		var fbstatusform = $('#fbstatusform');	
		$('#fbresponse').load(fbstatusform[0].action+'?checklogin=true&login_mode=themeform');
	});
}

function login_facebookNoRegForm(){
	jQuery(document).ready(function($) {
		$('#fbconnect_reload2').show(); 
		$('#fbloginbutton').hide(); 
		var fbstatusform = $('#fbstatusform');	
		$('#fbresponse').load(fbstatusform[0].action+'?checklogin=true&login_mode=themeform&hide_regform=true');
	});
}
	
function verify(url, text){
		if (text=='')
			text='Are you sure you want to delete this comment?';
		if (confirm(text)){
			document.location = url;
		}
		return void(0);
	}
// setup everything when document is ready
var fb_statusperms = false;	

function facebook_prompt_permission(permission, callbackFunc) {
    //check is user already granted for this permission or not
    FB.Facebook.apiClient.users_hasAppPermission(permission,
     function(result) {
        // prompt offline permission
        if (result == 0) {
            // render the permission dialog
            FB.Connect.showPermissionDialog(permission, callbackFunc,true);
        } else {
            // permission already granted.
			fb_statusperms = true;
            callbackFunc(true);
        }
    });
}

function callback_perms(){
	
	window.location.reload()
}

function facebook_prompt_stream_permission(callback_perms){
    //check is user already granted for this permission or not
	FB.Facebook.apiClient.users_hasAppPermission('read_stream',
     function(result) {
        // prompt offline permission
        if (result == 0) {
            // render the permission dialog
            FB.Connect.showPermissionDialog('publish_stream,read_stream',callback_perms,true );
        } 
    });	
} 
function facebook_prompt_mail_permission(){
    //check is user already granted for this permission or not
	FB.Facebook.apiClient.users_hasAppPermission('email',
     function(result) {
        // prompt offline permission
        if (result == 0) {
            // render the permission dialog
            FB.Connect.showPermissionDialog('email',callback_perms );
        } 
    });	
}

function fb_showTab(tabName){
	document.getElementById("fbFirstA").className = '';
	document.getElementById("fbSecondA").className = '';
	document.getElementById("fbThirdA").className = '';
	
	document.getElementById("fbFirst").style.visibility = 'hidden';
	document.getElementById("fbSecond").style.visibility = 'hidden';
	document.getElementById("fbThird").style.visibility = 'hidden';
	document.getElementById("fbFirst").style.display = 'none';
	document.getElementById("fbSecond").style.display = 'none';
	document.getElementById("fbThird").style.display = 'none';
	document.getElementById(tabName).style.visibility = 'visible';
	document.getElementById(tabName).style.display = 'block';
	document.getElementById(tabName+'A').className = 'selected';
	return false;
}

function fb_showTabComments(tabName){
	document.getElementById("fbFirstCommentsA").className = '';
	document.getElementById("fbSecondCommentsA").className = '';
	
	document.getElementById("fbFirstComments").style.visibility = 'hidden';
	document.getElementById("fbSecondComments").style.visibility = 'hidden';
	document.getElementById("fbFirstComments").style.display = 'none';
	document.getElementById("fbSecondComments").style.display = 'none';
	document.getElementById(tabName).style.visibility = 'visible';
	document.getElementById(tabName).style.display = 'block';
	document.getElementById(tabName+'A').className = 'selected';
	return false;
}

function fb_show(idname){
	document.getElementById(idname).style.visibility = 'visible';
	document.getElementById(idname).style.display = 'block';
}
function fb_hide(idname){
	document.getElementById(idname).style.visibility = 'hidden';
	document.getElementById(idname).style.display = 'none';
}	
function fb_showComments(tabName){
	document.getElementById("fbAllFriendsComments").style.visibility = 'hidden';
	document.getElementById("fbAllComments").style.visibility = 'hidden';
	document.getElementById("fbAllFriendsComments").style.display = 'none';
	document.getElementById("fbAllComments").style.display = 'none';
	document.getElementById("fbAllFriendsCommentsA").className = '';
	document.getElementById("fbAllCommentsA").className = '';
	document.getElementById(tabName).style.visibility = 'visible';
	document.getElementById(tabName).style.display = 'block';
	document.getElementById(tabName+'A').className = 'selected';
	return false;
}
function pinnedChange(){
	if (document.getElementById('fbconnect_widget_div').className == "") {
		document.getElementById('fbconnect_widget_div').className = "pinned";
	}else{
		document.getElementById('fbconnect_widget_div').className = "";
	}
}

function showCommentsLogin(){
	var comment_form = document.getElementById('commentform');
	if (!comment_form) {
		return;
	}

	commentslogin = document.getElementById('fbconnect_commentslogin');
	var firstChild = comment_form.firstChild;
    comment_form.insertBefore(commentslogin, firstChild);
	//comment_form.appendChild(commentslogin);
}


var urllike = "";

function joinws(urllike){
	alert("JOIN "+urllike);
}

function login_thickbox(urllike){
	//alert("LOGIN "+urllike);
	var urlthick = urllike;	
		
	if(urllike.indexOf("?") != -1){
			urlthick = urllike + "&fbconnect_action=register&height=400&width=370";
	}else{
			urlthick = urllike + "?fbconnect_action=register&height=400&width=370";			
	}
	tb_show('Registro', urlthick, null); 
}

var urllike ="";

function isfanfbpage(pageid,uid){
	FB.api( {
   		method: 'pages.isFan',
   		page_id: pageid,
		uid: uid }, 
       function(result) { 
				 alert(result);
			 });
}

function fbInviteFriendsCallback(response){
	//alert("callback");
}

function fbInviteFriends(msg) {
        FB.ui({method: 'apprequests',
          message: msg
        }, fbInviteFriendsCallback);
      }


function customHandleSessionResponse(responseInit){
	//fb_links_canvas();
	
}

