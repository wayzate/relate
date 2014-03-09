<?PHP
require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
      <title>Accueil</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css">
</head>
<body>
<div id='fg_membersite_content'>
<h2>Accueil</h2>
Bienvenue <? echo($fgmembersite->UserFullName()); ?> !

<li><a href='changeprofile/changeprofile.php'>Editer mon profil</a></li>
	<li><a href='change-pwd.php'>Changer de mot de passe</a></li>


<?php 
if($fgmembersite->UserType() == "etudiant") 
	{
	echo ("		<li><a href='estelle/PDF.php'>Générer CV</a></li>");
	echo ("<li><a href='searchstage.php'>Rechercher un stage</a></li>");
	}
		
?>

<?php if($fgmembersite->UserType() == "entreprise") 
		{
		echo ("<li><a href='searchstudent.php'>Effectuer une recherche étudiant</a></li>");
		echo ("<li><a href='addstage.php'>Ajouter une offre de stage</a></li>");
		echo ("<li><a href='searchstudent.php'>Sourcing étudiant</a></li>");
		}
		
?>
<li><a href='entreprise.php'>Consulter les entreprises</a></li>
<li><a href='messenger/mailbox.php'>Boîte de réception</a></li>
<li><a href='messenger/sentbox.php'>Messages envoyés</a></li>
<li><a href='messenger/sendmess.php'>Envoyer un message</a></li>


Avec Graphisme
<li><a href='accueil.php'>Accueil</a></li>
<li><a href='accueil_etudiant.php'>Accueil étudiant</a></li>


<br><br><br>
<p><a href='logout.php'>Se déconnecter</a></p>
</div>
</body>
</html>
