<?PHP
require_once("../include/membersite_config.php");
require_once("mailboxsystem.php");

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
	
	$fgmembersite->RedirectToURL("messageenvoye.php");
}

/*if(isset($_POST['submitted']))
{     

		$destinataire = array();
		if(!$fgmembersite->GetUserFromEmail($_POST['to'],'student',$destinataire))
		{
			echo "Le destinataire n'existe pas !";
            return false;
		};

		  
		$insert_query = 'insert into messages (
				`title`,
				`from`,
				`to`,
				`content`
                )
                values
                (
                "' . $fgmembersite->SanitizeForSQL($_POST['title']) . '",
                "' . $fgmembersite->SanitizeForSQL($_POST['from']) . '",
				"' . $fgmembersite->SanitizeForSQL($_POST['to']) . '",
				"' . $fgmembersite->SanitizeForSQL($_POST['content']) .'"
                )';

        if(!mysql_query( $insert_query ,$fgmembersite->connection))
        {
            echo "L'accès à la base de donnée des messages a échoué : /nquery:$insert_query";
            return false;
        } 
		$mess_id = mysql_insert_id();
		
		$oldmailbox = explode('$', $destinataire['mailbox']);
		$newmailbox = array_merge($oldmailbox, array($mess_id));
		$newmailbox = implode('$',$newmailbox);
		
		$query = "UPDATE `student` 
				SET `mailbox` = \"".$newmailbox."\" 
				WHERE `student`.`id_user` =".$destinataire['id_user'];

        if(!mysql_query( $query ,$fgmembersite->connection))
        {
            echo "Boite de réception : L'accès à la base de donnée des étudiants a échoué /nquery:$query";
            return false;
        }     
		
		$oldsentbox = explode('$', $userrec['sentbox']);
		$newsentbox = array_merge($oldsentbox, array($mess_id));
		$newsentbox = implode('$',$newsentbox);
		
		$query = "UPDATE `student` 
				SET `sentbox` = \"".$newsentbox."\" 
				WHERE `student`.`id_user` =".$userrec['id_user'];

        if(!mysql_query( $query ,$fgmembersite->connection))
        {
            echo "Boite d'envoi : L'accès à la base de donnée des étudiants a échoué /nquery:$query";
            return false;
        }    
		
        $fgmembersite->RedirectToURL("messageenvoye.php");
}*/


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
      <title>Envoyer un message</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>
Bonjour <?= $fgmembersite->UserFullName(); ?> !

<form id="sendmess"  action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
	<fieldset>    	
    	<legend>Nouveau message</legend>
        <br/>
             <label>Destinataire</label><br/>
             <input type='text'  name='to' maxlength="64"/><br/>
             
             <label>Objet</label><br/>
             <textarea name="title" maxlength="128"></textarea><br/>
             
             <label>Contenu</label><br/>
             <textarea name="content" maxlength="2000"></textarea><br/>
             
             <input type='hidden' name='from' value='<?php echo $email;?>' />
             <input type="submit" name="submitted"/>
    </fieldset>
</form>
</body>
</html>