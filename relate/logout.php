<?PHP
require_once("./include/membersite_config.php");

$fgmembersite->LogOut();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
      <title>Connexion</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>

<h2>Vous vous êtes déconnecté</h2>
<p>
<a href='login.php'>Se (re)connecter</a>
</p>
<?php 
if(!$fgmembersite->CheckLogin())
{
	$fgmembersite->RedirectToURL("accueil.php");
	exit;
}
?>
</body>
</html>