<?PHP
require_once("./include/membersite_config.php");
require_once("messenger/mailboxsystem.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}

$user_rec = array();
if(!$fgmembersite->GetUserIDFromEmail($fgmembersite->UserEmail(),$fgmembersite->UserType(),$user_rec))
{
	return false;
}

if(isset($_GET['message'])) {
	$boite_mail = new Mailbox($fgmembersite);
	
	if(!$boite_mail->delMessage($fgmembersite->SanitizeForSQL($_GET['message']),"dest") )
	{
		echo "Echec de la suppression<br/>";
		return false;
	}
	
	//$fgmembersite->RedirectToURL("messages.php");
}

$id_user = $user_rec['id'];
$typeOfUser = $user_rec['type'];
$mb = new Mailbox($fgmembersite);
$mailbox = array();

$mb->getMailbox($mailbox,$id_user);

?>


<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Messages</title>

	  <link rel="STYLESHEET" type="text/CSS" href="css/style.css" media="screen" />
	  <link rel="STYLESHEET" type="text/css" href="css/stylemessages.css" media="screen" />
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
      <script type="text/javascript" src="popup/jquery-1.8.3.js"></script>
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
	font-size: 1em;
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

<!-- ************************ DEBUT GROSCONTENEUR ************************ -->
		
	<div id="grosconteneur">

<!-- ************************ HEADER ************************ -->
	<div id="header">
    
		<div id="top">
			<div id="item">
				<div id="logo"></div>
			</div>
		</div>
		<div id="topombre"></div>
		
	</div>
    

<!-- ************************ BARRE DE MENU ************************ -->
	
	<div id="menu">
	
		<ul>
			<li><a href="profil_etudiant.php" class="profil"> Profil </a></li>
			<li><a href="#" class="offres"> Offres </a></li>
			<li><a href="#" class="entreprises"> Entreprises </a></li>
			<li><a href="#" class="etudiants"> Etudiants </a></li>
			<li><a href="#" class="candidatures"> Candidatures </a></li>
			<li><a href='messages.php' class="messages"> Messages </a></li>
		</ul>
	</div>


<!-- ************************ DEBUT CONTENEUR ************************ -->
		
	<div id="conteneur">
    
    
<!-- ************************ BIENVENUE ET DECONNEXION ************************ -->		

    	<div id="textetop">
			<div id="bienvenue"><a href="accueil_etudiant.php">Bienvenue <?php echo $fgmembersite->UserFullName(); ?></a></div>
        	<div id="deconnexion"><a href='logout.php'> Se déconnecter </a></div>
        </div>

<!-- ************************ TITRE ************************ -->			

		<div id="titre"><div id="nouveau"><a href="nouveau_message.php">Nouveau message</a></div>VOS MESSAGES</div>
        
       
<!-- *********************** MENU MESSAGES *********************** -->
        
<div id="menumessages">
	
		<ul>
			<li><a class="actif" href="#"> Messages reçus </a></li>
			<li><a href="messages_envoyes.php"> Messages envoyés </a></li>
			<li><a href="#"> Important </a></li>
			<li><a href="#"> Sélection </a></li>
		</ul>
        
</div>
        
<!-- *********************** MESSAGES RECUS *********************** -->

<div id="messages">
<?php 
foreach($mailbox as $i => $mess) {	

	if($mess) {
	$objet = ($mess['title'] == "" || !isset($mess['title']))?"- Aucun objet -":$mess['title'];
		echo "<div id='message'>
				<li class='enteterecus'><a href='ouverture_message.php?n=$i'>
					<div class='expediteur'>{$mess['fromName']} {$mess['fromSurname']}</div> 
					<div class='marge'>{$mess['date']}</div>
			  		$objet
					<div class='marge'><input type='checkbox'/></div></a></li>
				<li class='contenu'>
			  		{$mess['content']}<br />
			  	</li>
			  </div><br />"; 
	}
}

?>
<!-- <a href='?message={$mess['id']}'>Supprimer ?</a> !-->

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


<!-- *********************** FIN CONTENEUR*********************** -->

</div>        
   

<!-- ************************ FOOTER ************************ -->

<!--
<div id="footer" class="messages">

	<div id="footombre"></div>
    
	<div id="foot">
    	<div id="textefoot">
        	<li><a class="relate"> Relate ©  2013</a></li>
			<li><a href="#"> Contactez-nous </a></li>
			<li><a href='conditions_utilisation.html'> Conditions d'utilisation </a></li>
			<li><a href='mentions_legales.html'> Mentions légales </a></li>
        </div>
	</div>

</div>
-->

<!-- ************************ FIN GROSCONTENEUR ************************ -->    
    </div>
	
</body>
</html>
