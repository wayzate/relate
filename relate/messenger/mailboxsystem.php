<?PHP
class Mailbox {
	
	var $fgmembersite;

	function Mailbox($fgmembersite) {
		$this->fgmembersite =$fgmembersite;
	}

	function getMailbox(&$mailbox,$id_user) {	
		$qry = "SELECT message.id, message.title, message.id_user_from, message.id_user_to, message.read, message.date, message.content, user.name AS fromName, user.surname AS fromSurname, user.email AS fromEmail
		FROM message, user
		WHERE (
		id_user_to = $id_user
		AND user.id = message.id_user_from
		AND message.delete != 2
		)
		ORDER BY message.date DESC";
		
		$qry = "SELECT mess.id, mess.title, mess.id_user_from, 
				mess.id_user_to, mess.read, mess.date, mess.content, 
				usr.name AS fromName, usr.surname AS fromSurname, usr.email AS fromEmail
				
				FROM 
					(SELECT * FROM message WHERE (message.id_user_to = $id_user AND message.delete != '2')) AS mess,
					(SELECT * FROM user WHERE id IN (SELECT id_user_from FROM message WHERE id_user_to=$id_user)) AS usr
				
				WHERE
					usr.id = mess.id_user_from
				ORDER BY mess.date DESC";
		
		$result = mysql_query($qry,$this->fgmembersite->connection);
		if(!$result) {
			echo "Erreur, requête $qry non effectuée";
			return false;
		}
		
		$k=0;
		
		while ($mailbox[$k++] = mysql_fetch_assoc($result)) {
		}
	}
	
	function getSentbox(&$sentbox,$id_user) {
		$qry = "SELECT message.id, message.title, message.id_user_from, message.id_user_to, message.read, message.date, message.content, user.name AS toName, user.surname AS toSurname, user.email AS toEmail
		FROM message, user
		WHERE (
		id_user_to =$id_user
		AND user.id = message.id_user_to
		AND message.delete != '1'
		)
		ORDER BY message.date DESC";
		$result = mysql_query($qry,$this->fgmembersite->connection);
		if(!$result) {
		echo "Erreur, requête $qry non effectuée";
		return false;
		}
		
		$qry = "SELECT mess.id, mess.title, mess.id_user_from,
		mess.id_user_to, mess.read, mess.date, mess.content,
		usr.name AS toName, usr.surname AS toSurname, usr.email AS toEmail
		
		FROM
		(SELECT * FROM message WHERE (message.id_user_from = $id_user AND message.delete != '1')) AS mess,
		(SELECT * FROM user WHERE id IN (SELECT id_user_to FROM message WHERE id_user_from=$id_user)) AS usr
		
		WHERE
		usr.id = mess.id_user_to
		ORDER BY mess.date DESC";
		
		$k=0;
		
		while ($sentbox[$k++] = mysql_fetch_assoc($result)) {
		}
	}
	
	
	function sendMessage($title,$from,$to,$content) 
	{		
		$exp = array();
		if(!$this->fgmembersite->GetUserIDFromEmail($from,'student',$exp))
		{
			echo "Id de l'expéditeur irrécupérable !<br/>";
            return false;
		};
		
		$destinataire = array();
		if(!$this->fgmembersite->GetUserIDFromEmail($to,'student',$destinataire))
		{
			echo "Le destinataire n'existe pas !<br/>";
            return false;
		};

		

		  
		$insert_query = 'insert into message (
				`title`,
				`id_user_from`,
				`id_user_to`,
				`content`
                )
                values
                (
                "' . $this->fgmembersite->SanitizeForSQL($title) . '",
                "' . $this->fgmembersite->SanitizeForSQL($exp['id']) . '",
				"' . $this->fgmembersite->SanitizeForSQL($destinataire['id']) . '",
				"' . $this->fgmembersite->SanitizeForSQL($content) .'"
                )';
				
        if(!mysql_query( $insert_query ,$this->fgmembersite->connection))
        {
            echo "L'accès à la base de donnée des messages a échoué : /nquery:$insert_query";
            return false;
        } 
		
        return true;
	}
	
	function delMessage($idmessage,$expOuDest) {
		
		$qry = "SELECT message.delete FROM message WHERE message.id = '$idmessage'";
		$res = mysql_query($qry,$this->fgmembersite->connection);
		if(!$res)
		{
			echo "Erreur d'accès à la base de données \n $qry";
			return false;
		}
		
		$delete = mysql_fetch_row($res);
		var_dump($delete);
		
		switch($delete[0]) {
			case '0' :				
				if($expOuDest == "exp") //le suppresseur est l'expéditeur (suppression dans la boite d'envoi)
				{
					$num = 1;
				}
				else{
					$num = 2;
				}

				$qry = "UPDATE message SET message.delete = '$num' WHERE message.id = '$idmessage'";
				$result = mysql_query($qry,$this->fgmembersite->connection);

				var_dump($qry,$result);
				
				if(!result) {
					echo "Erreur de mise à jour du message dans la base de données ! \n $qry";
					return false;
				}
				break;
			case '1' :
			case '2' :
				$qry = "UPDATE message SET message.delete = '3' WHERE message.id = '$idmessage'";
				$result = mysql_query($qry,$this->fgmembersite->connection);

				var_dump($qry,$result);
				
				if(!result) {
					echo "Erreur de mise à jour du message dans la base de données ! \n $qry";
					return false;
				}
				break;
			default :
				return false;
		}
		return true;
	}

	
	
}
?>