<?PHP
require_once("./include/membersite_config.php");
require_once("messenger/mailboxsystem.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("../login.php");
    exit;
}

$userrec = array();
$email = $fgmembersite->UserEmail();
$type = $fgmembersite->UserType();


if(!$fgmembersite->DBLogin())
{
	$fgmembersite->HandleError("Echec d'authentification base de donnée !");
	return false;
}

if(isset($_POST['submitted']))
{ 

	$boite_mail = new Mailbox($fgmembersite);
	
	if(!$boite_mail->sendMessage($_POST['title'],$_POST['from'],$_POST['to'],$_POST['content'])) 
	{
		echo "Echec de l'envoi<br/>";
		return false;
	}
	
	$fgmembersite->RedirectToURL("messenger/messageenvoye.php");
}

?>

<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
 <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
      <title>Nouveau message</title>
      
      <link rel="STYLESHEET" type="text/CSS" href="css/style.css" media="screen" />
      <link rel="STYLESHEET" type="text/css" href="css/stylenouveau_message.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
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

		<div id="titre"><div id="nouveau"><a href="messages.php">Retour à la boîte de réception</a></div>VOS MESSAGES</div>
        
       
<!-- *********************** MENU MESSAGES *********************** -->
        
<div id="menumessages">
	
		<ul>
			<li><a href="messages.php"> Messages reçus </a></li>
			<li><a href="messages_envoyes"> Messages envoyés </a></li>
			<li><a href="#"> Important </a></li>
			<li><a href="#"> Sélection </a></li>
		</ul>
        
</div>
        
<!-- *********************** NOUVEAU MESSAGE *********************** -->

<div id="nouveaumessage">

<form id="sendmess"  action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
	<fieldset>   
    <legend>Nouveau message</legend> 	
             <label>Destinataire</label><br/>
             <input type='text'  name='to' maxlength="64" <?php if(isset($_GET['to'])) {echo "value='{$_GET['to']}'";}?>/><br/>
             
             <label>Objet</label><br/>
             <input type='text' name='title' maxlength="128" <?php if(isset($_GET['sub'])) {echo "value='Re : {$_GET['sub']}'";}?>/><br/>
 
             <label>Contenu</label><br/>
             <textarea name="content" maxlength="2000"></textarea><br/>
             
             <input type='hidden' name='from' value='<?php echo $email;?>' />
             <input type="submit" class='boutonvalider' value='Envoyer' name="submitted"/>
    </fieldset>
</form>

</div>       


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
