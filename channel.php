<?php 
		$lang= "en_US";
		if (isset($_REQUEST["lang"]) && $_REQUEST["lang"]!=""){
			$lang = $_REQUEST["lang"];	
		}
?>
<script src="http://connect.facebook.net/<?php echo $lang;?>/all.js"></script>