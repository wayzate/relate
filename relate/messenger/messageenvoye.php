<?PHP
require_once("../include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("../login.php");
    exit;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
      <title>Envoyer un message</title>
      <link rel="STYLESHEET" type="text/css" href="../css/stylethankyou.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>
 </br>
 </br>
 <div id='fg_membersite_content'>
<h2>Envoi de message</h2>
Votre message a bien été envoyé.
</br>
</br>
	<a href='../messages.php' >Retour à la boîte de réception</a></br></br>
    
</div>

</body>
</html>