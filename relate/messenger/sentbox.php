<?PHP
require_once("../include/membersite_config.php");
require_once("mailboxsystem.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("../login.php");
    exit;
}

$user_rec = array();
if(!$fgmembersite->GetUserIDFromEmail($fgmembersite->UserEmail(),$fgmembersite->UserType(),$user_rec))
{	
	return false;
}

$id_user = $user_rec['id'];
$typeOfUser = $user_rec['type'];

$mb = new Mailbox($fgmembersite);
$sentbox = array();

$mb->getSentbox($sentbox,$id_user);
var_dump($sentbox);



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
      <title>Boîte d'envoi</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
      <script type="text/javascript" src="../popup/jquery-1.8.3.js"></script>
      <style type="text/css">
	  
#fade {
	display: none;
	background: #000; 
	position: fixed; left: 0; top: 0; 
	z-index: 10;
	width: 100%; height: 100%;
	opacity: .80;
	z-index: 9999;
}
.popup_block{
	display: none;
	background: #fff;
	padding: 20px; 	
	border: 20px solid #ddd;
	float: left;
	font-size: 1.2em;
	position: fixed;
	top: 50%; left: 50%;
	z-index: 99999;
	-webkit-box-shadow: 0px 0px 20px #000;
	-moz-box-shadow: 0px 0px 20px #000;
	box-shadow: 0px 0px 20px #000;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
}
img.btn_close {
	float: right; 
	margin: -55px -55px 0 0;
}
.popup p {
	padding: 5px 10px;
	margin: 5px 0;
}
/*--Making IE6 Understand Fixed Positioning--*/
*html #fade {
	position: absolute;
}
*html .popup_block {
	position: absolute;
}
</style>
</head>
<body>
Bonjour <?= $fgmembersite->UserFullName(); ?> !
<br />
<a href="sendmess.php">Envoyer un message</a>
<br />
<br />

<br />

<div>
Messages envoyés :
<?php 
foreach($sentbox as $i => $mess) {		
	if($mess) {
		echo "<div><a href='#?w=500' rel='message".$i."' class='poplight'>A {$mess['toSurname']} {$mess['toName']}  </div></a> <div>".$mess['title']."</div><br />
			<div class='popup_block' id='message".$i."'>".
			"{$mess['toSurname']} {$mess['toName']} <br />".
			$mess['title']."<br />
			Le ".$mess['date']."<br />
			<br />".$mess['content']."
			</div>";
			
	}
}

?>
</div>

<br />



<script type="text/javascript">
$(document).ready(function(){
						   		   
	//When you click on a link with class of poplight and the href starts with a # 
	$('a.poplight[href^=#]').click(function() {
		var popID = $(this).attr('rel'); //Get Popup Name
		var popURL = $(this).attr('href'); //Get Popup href to define size
				
		//Pull Query & Variables from href URL
		var query= popURL.split('?');
		var dim= query[1].split('&');
		var popWidth = dim[0].split('=')[1]; //Gets the first query string value

		//Fade in the Popup and add close button
		$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="images/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');
		
		//Define margin for center alignment (vertical + horizontal) - we add 80 to the height/width to accomodate for the padding + border width defined in the css
		var popMargTop = ($('#' + popID).height() + 80) / 2;
		var popMargLeft = ($('#' + popID).width() + 80) / 2;
		
		//Apply Margin to Popup
		$('#' + popID).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		//Fade in Background
		$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer 
		
		return false;
	});
	
	
	//Close Popups and Fade Layer
	$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
	  	$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();  
	}); //fade them both out
		
		return false;
	});

	
});

</script>


</body>
</html>