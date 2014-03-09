<?PHP
/*
    Registration/Login script from HTML Form Guide
    V1.0

    This program is free software published under the
    terms of the GNU Lesser General Public License.
    http://www.gnu.org/copyleft/lesser.html
    

This program is distributed in the hope that it will
be useful - WITHOUT ANY WARRANTY; without even the
implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.

For updates, please visit:
http://www.html-form-guide.com/php-form/php-registration-form.html
http://www.html-form-guide.com/php-form/php-login-form.html

*/
require_once("class.phpmailer.php");
require_once("formvalidator.php");

class FGMembersite
{
    var $admin_email;
    var $from_address;
	
	var $sitename;
    var $db_host;
    var $username;
    var $pwd;
    var $database;
    var $tablename_user;
    var $tablename_student;
    var $tablename_society;
    var $connection;
    var $rand_key;
    
    var $numSection;
    var $maxAjout;
    
    var $error_message;
    
    //-----Initialization -------
    function InitDB($host,$uname,$pwd,$database,$tablename_user,$tablename_student,$tablename_society,$maxAjout,$numSection)
    {
    	$this->db_host  = $host;
    	$this->username = $uname;
    	$this->pwd  = $pwd;
    	$this->database = $database;
    	$this->tablename_user = $tablename_user;
    	$this->tablename_student = $tablename_student;
    	$this->tablename_society = $tablename_society;
    	$this->numSection = $numSection;
    	$this->maxAjout = $maxAjout;
    }
    
    function FGMembersite()
    {
        $this->sitename = 'Relate.com';
        $this->rand_key = '0iQx5oBk66oVZep';
    }
    
    
	
	
    function SetAdminEmail($email)
    {
        $this->admin_email = $email;
    }
    
    function SetWebsiteName($sitename)
    {
        $this->sitename = $sitename;
    }
    
    function SetRandomKey($key)
    {
        $this->rand_key = $key;
    }
    
    //-------Main Operations ----------------------
    function RegisterUser()
    {
        
        $formvars = array();
        
        if(!$this->ValidateRegistrationSubmission())
        {return false;}
        
        $this->CollectRegistrationSubmission($formvars);
        
        if(!$this->SaveToDatabase($formvars))
        {return false;}
        
        if(!$this->SendUserConfirmationEmail($formvars))
        {return false;}

        $this->SendAdminIntimationEmail($formvars);
        
        return true;
    }

    function ConfirmUser()
    {
        if(empty($_GET['code'])||strlen($_GET['code'])<=10)
        {
            $this->HandleError("Veuillez fournir le code de confirmation");
            return false;
        }
        $user_rec = array();
        if(!$this->UpdateDBRecForConfirmation($user_rec))
        {
            return false;
        }
        
        $this->SendUserWelcomeEmail($user_rec);
        
        $this->SendAdminIntimationOnRegComplete($user_rec);
        
        return true;
    }    
    
    function Login()
    {
        if(empty($_POST['type']))
        {
            $this->HandleError("Case type d'utilisateur vide !");
            return false;
        }
        
		
		if(empty($_POST['username']))
        {
            $this->HandleError("Case nom d'utilisateur vide !");
            return false;
        }
        
        if(empty($_POST['password']))
        {
            $this->HandleError("Case mot de passe vide !");
            return false;
        }
        
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
		$type = trim($_POST['type']);
        
        if(!isset($_SESSION)){ session_start(); }
        if(!$this->CheckLoginInDB($username,$password,$type))
        {
            return false;
        }
        
        $_SESSION[$this->GetLoginSessionVar()] = $username;
        
        return true;
    }
    
    function CheckLogin()
    {
         if(!isset($_SESSION)){ session_start(); }

         $sessionvar = $this->GetLoginSessionVar();
         
         if(empty($_SESSION[$sessionvar]))
         {
            return false;
         }
         return true;
    }
    
    function UserFullName()
    {
        return isset($_SESSION['name_of_user'])?$_SESSION['name_of_user']:'';
    }
    
    function UserEmail()
    {
        return isset($_SESSION['email_of_user'])?$_SESSION['email_of_user']:'';
    }
	
	function UserType()
    {
        return isset($_SESSION['type_of_user'])?$_SESSION['type_of_user']:'';
    }
    
    function LogOut()
    {
        session_start();
        
        $sessionvar = $this->GetLoginSessionVar();
        
        $_SESSION[$sessionvar]=NULL;
        
        unset($_SESSION[$sessionvar]);
    }
    
    function EmailResetPasswordLink()
    {
        if(empty($_POST['email']))
        {
            $this->HandleError("Case email vide !");
            return false;
        }
		if(empty($_POST['type']))
        {
            $this->HandleError("Case type vide !");
            return false;
        }
        $user_rec = array();
        if(false === $this->GetUserIDFromEmail($_POST['email'],$_POST['type'], $user_rec))
        {
            return false;
        }
        if(false === $this->SendResetPasswordLink($user_rec))
        {
            return false;
        }
        return true;
    }
    
    function ResetPassword()
    {
        if(empty($_GET['email']))
        {
            $this->HandleError("Case email vide !");
            return false;
        }
        if(empty($_GET['code']))
        {
            $this->HandleError("reset code vide!");
            return false;
        }
		if(empty($_GET['type']))				//ajout 27/11
        {
            $this->HandleError("type vide !");
            return false;
        }
        $email = trim($_GET['email']);
        $code = trim($_GET['code']);
		$type = trim($_GET['type']);
        
        if($this->GetResetPasswordCode($email) != $code)
        {
            $this->HandleError("Mauvais reset code!");
            return false;
        }
        
        $user_rec = array();
        if(!$this->GetUserIDFromEmail($email,$type,$user_rec)) //
        {
            return false;
        }
        
        $new_password = $this->ResetUserPasswordInDB($user_rec);
        if(false === $new_password || empty($new_password))
        {
            $this->HandleError("Erreur mis à jour de mot de passe");
            return false;
        }
        
        if(false == $this->SendNewPassword($user_rec,$new_password))
        {
            $this->HandleError("Erreur lors de l'envoi du nouveau mot de passe");
            return false;
        }
        return true;
    }
    
  
    function ChangePassword()
    {
        if(!$this->CheckLogin())
        {
            $this->HandleError("Vous n'êtes pas connecté !");
            return false;
        }
        
        if(empty($_POST['oldpwd']))
        {
            $this->HandleError("Case 'ancien mot de passe' vide !");
            return false;
        }
        if(empty($_POST['newpwd']))
        {
            $this->HandleError("Case 'nouveau mot de passe' vide !");
            return false;
        }

        $user_rec = array();
		
        if(!$this->GetUserFromEmail($this->UserEmail(),$this->UserType(),$user_rec)) //
        {
            return false;
        }

        $pwd = trim($_POST['oldpwd']);
        
        if($user_rec['password'] != md5($pwd))
        {
            $this->HandleError("L'ancien mot de passe ne correspond pas !");
            return false;
        }
        $newpwd = trim($_POST['newpwd']);
        
        if(!$this->ChangePasswordInDB($user_rec, $newpwd))
        {
            return false;
        }
        return true;
    }
	
	
    
    //-------Public Helper functions -------------
    function GetSelfScript()
    {
        return $_SERVER['PHP_SELF']; //html entities present avant
    }    
    
    function SafeDisplay($value_name)
    {
        if(empty($_POST[$value_name]))
        {
            return'';
        }
        return (htmlspecialchars(stripslashes($_POST[$value_name]),ENT_QUOTES)); //htmlentities present avant
    }
    
    function RedirectToURL($url)
    {
        header("Location: $url");
        exit;
    }
    
    function GetSpamTrapInputName()
    {
        return 'sp'.md5('KHGdnbvsgst'.$this->rand_key);
    }
    
    function GetErrorMessage()
    {
        if(empty($this->error_message))
        {
            return '';
        }
        $errormsg = nl2br($this->error_message);
        return $errormsg;
    }    
    
    function afficheJour($i,$j,$table,$champs,$user_rec) {
    	echo "<select name='$i.$j.$table.$champs' id='$champs'>";
    	echo "<option value=''>Jour</option>";
    	
    	for($k = 1;$k <= 31;$k++) {
    		$string ='';
    		if($user_rec[$i][$j][$table][$champs] == "$k") {
    			$string = 'selected="selected"';
    		}
    		echo "<option value='$k' $string>$k</option>\n";
    	}
    	
    		echo "</select>";
    }
    
    function afficheMois($i,$j,$table,$champs,$userrec)
    {
    	if(isset($userrec[$i][$j][$table][$champs])) {
    		$imois =$userrec[$i][$j][$table][$champs];
    	}
    	else{
    		$imois = '';
    	}
    	
   		echo "<select name='$i.$j.$table.$champs' onChange='changeDate(this.options[selectedIndex].value);'>";
    	echo "<option value=''"; 	if($imois == ''){echo "selected='selected'";};	echo ">Mois</option>";
	   	echo "<option value='1'";  if($imois == '1') {echo "selected='selected'";}; 	echo" >Janvier</option>";
	   	echo "<option value='2'";  if($imois == '2') {echo "selected='selected'";}; 	echo" >Février</option>";
	   	echo "<option value='3'";  if($imois == '3') {echo "selected='selected'";}; 	echo" >Mars</option>";
	   	echo "<option value='4'";  if($imois == '4') {echo "selected='selected'";};	echo" >Avril</option>";
	   	echo "<option value='5'";  if($imois == '5') {echo "selected='selected'";}; 	echo" >Mai</option>";
	   	echo "<option value='6'";  if($imois == '6') {echo "selected='selected'";}; 	echo" >Juin</option>";
	   	echo "<option value='7'";  if($imois == '7') {echo "selected='selected'";}; 	echo" >Juillet</option>";
	   	echo "<option value='8'";  if($imois == '8') {echo "selected='selected'";}; 	echo" >Août</option>";
	   	echo "<option value='9'";  if($imois == '9') {echo "selected='selected'";}; 	echo" >Septembre</option>";
	   	echo "<option value='10'"; if($imois == '10'){echo "selected='selected'";};	echo" >Octobre</option>";
	   	echo "<option value='11'"; if($imois == '11'){echo "selected='selected'";};	echo" >Novembre</option>";
	   	echo "<option value='12'"; if($imois == '12'){echo "selected='selected'";};	echo" >Décembre</option>";
	   	echo "</select>";
    }
    
    function afficheAnnee($i,$j,$table,$champs,$user_rec) {
    	echo "<select name='$i.$j.$table.$champs' id='$champs'>";
    	echo "<option value=''>Année</option>";
    		
    	for($k = 2018;$k > 1950;$k--) {
    		$string ='';
    		if($user_rec[$i][$j][$table][$champs] == $k) {
    			$string = "selected='selected'";
    		}
    		echo "<option value='$k' $string>$k</option>\n";
    	}
    	echo "</select>";
    }
    //-------Private Helper functions-----------
    
    function HandleError($err)
    {
        $this->error_message .= $err."\r\n";
    }
    
    function HandleDBError($err)
    {
        $this->HandleError($err."\r\n mysqlerror:".mysql_error());
    }
    
    function GetFromAddress()
    {
        if(!empty($this->from_address))
        {
            return $this->from_address;
        }

        $host = $_SERVER['SERVER_NAME'];

        $from ="webmaster@$host";
        return $from;
    } 
    
    function GetLoginSessionVar()
    {
        $retvar = md5($this->rand_key);
        $retvar = 'usr_'.substr($retvar,0,10);
        return $retvar;
    }
    
    function CheckLoginInDB($username,$password,$type) 
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Erreur d'authentification base de donnée!");
            return false;
        }
        $username = $this->SanitizeForSQL($username);
        $pwdmd5 = md5($password);
		//$this->tablename_user = ($type == "entreprise" ? 'society' : 'student'); //
		
        $qry = "Select name, email, id_society from $this->tablename_user where username='$username' and password='$pwdmd5' and confirmcode='y'"; 
        $qry_not_activated = "Select name, email from $this->tablename_user where username='$username' and password='$pwdmd5'"; //ajout
		
        $result = mysql_query($qry,$this->connection);
        $result_not_activated = mysql_query($qry_not_activated,$this->connection); //ajout
		
		
        if(!$result || mysql_num_rows($result) <= 0)
        {
			if($result_not_activated && mysql_num_rows($result_not_activated) > 0)
				$this->HandleError("Votre compte n'a pas été activé. Veuillez consulter l'e-mail qui vous a été envoyé");
			else
				$this->HandleError("Erreur d'authentification. Le nom d'utilisateur et le mot de passe ne se correspondent pas");
            return false;
        }
        
        $row = mysql_fetch_assoc($result);
        
        
 
		
		if($row['id_society'] != null && $type == "entreprise") {
			$_SESSION['name_of_user']  = $row['name'];
			$_SESSION['email_of_user'] = $row['email'];
			$_SESSION['type_of_user'] = $type;
		}
		else {
			if($row['id_society'] == null && $type=="etudiant") {
				$_SESSION['name_of_user']  = $row['name'];
				$_SESSION['email_of_user'] = $row['email'];
				$_SESSION['type_of_user'] = $type;
			}
			else {
				$this->HandleError("Erreur d'authentification. Le type de compte choisi est mauvais.");
				return false;
			}
		}

		return true;
    }
    
    function UpdateDBRecForConfirmation(&$user_rec) //à l'appel $user_rec est vide
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }   
        $confirmcode = $this->SanitizeForSQL($_GET['code']);
        $type = $_GET['type'] == "entreprise" ? 'entreprise' : 'etudiant'; //ajout 27/11 	// avant tablename_user au lieu de type
		
		
		
        $result = mysql_query("Select name, email from $this->tablename_user where confirmcode='$confirmcode'",$this->connection); // avant pas de this->
         
        if(!$result || mysql_num_rows($result) <= 0)
        {
            $this->HandleError("Mauvais code de confirmation.");
            return false;
        }
        $row = mysql_fetch_assoc($result);
        $user_rec['name'] = $row['name'];
        $user_rec['email']= $row['email'];
		$user_rec['type']= $type;	//  avant tablename_user au lieu de type
		
        $qry = "Update $this->tablename_user Set confirmcode='y' Where  confirmcode='$confirmcode'";
        
        if(!mysql_query( $qry ,$this->connection))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$qry");
            return false;
        }      
        return true;
    }
    
    function ResetUserPasswordInDB($user_rec)
    {
        $new_password = substr(md5(uniqid()),0,10);
        
        if(false == $this->ChangePasswordInDB($user_rec,$new_password))
        {
            return false;
        }
        return $new_password;
    }
    
    function ChangePasswordInDB($user_rec, $newpwd)
    {
        $newpwd = $this->SanitizeForSQL($newpwd);
        
        $qry = "Update $this->tablename_user Set password='".md5($newpwd)."' Where  id=".$user_rec['id']."";
        
        if(!mysql_query( $qry ,$this->connection))
        {
            $this->HandleDBError("Error updating the password \nquery:$qry");
            return false;
        }     
        return true;
    }
	
	
    function ChangeProfileInDB($user_rec,$profile_vars)
    {
    	 
    	
    	
    	$i=0;
    	$j=0;
    	
    	/*if(isset($profile_vars[0][0]['user']['town'])) {
    		$val = $this->SanitizeForSQL($profile_vars[0][0]['user']['town']);
    		$insertquery = "INSERT INTO `town` (`name`) values('$val')";
    		$res = mysql_query($insertquery,$this->connection);
    		 
    		if(!$res){
    			$this->HandleDBError("Echec d'insertion dans la table 'town' pour 0 0\nquery:$insertquery");
    			return false;
    		}

    		$user_rec[$i][$j]['user']['id_town']= mysql_insert_id();
    		 
    		 
    	}*/

    	 
    	 
    	
    	$query = "";
    	$qryDelete = array(); $indexDelete = 0;
    	 
    	//La liste des tables est ordonnées : entités de droite puis entités de gauche puis Relations
    	$listeTable = array('art','association','country','software','sport','town','diploma','infoLanguage','establishment','society','user','proexperience','tongue','student','AEteAPays','AEtudieA','AFaitPartieDeAssociation','APratiqueArt',
    			'APratiqueSport','ATravailleA','Parle','SaitProgrammerEn','SaitUtiliserLogiciel');
    	
    	
    	for($j=0;$j<$this->numSection;$j++) {
    		for($i=0;$i<$this->maxAjout;$i++){
    			foreach($listeTable as $table)
    			{
    				if( isset($profile_vars[$i][$j][$table]) ) {

    					$temp = &$profile_vars[$i][$j][$table];
    					
    					switch ($table) {
    						case 'student':
    							if(isset($user_rec[0][0]['student']['id'])) {
    								$insertquery =
    								"UPDATE $table SET 
    								`birthdate` = '{$temp['birthdate']}',`nationality` = '{$temp['nationality']}',
    								`presentation` = '{$temp['presentation']}',`seeking` = '{$temp['seeking']}',
    								`disponibility` = '{$temp['disponibility']}',`seekingDuration` = '{$temp['seekingDuration']}',
    								`seekingDomain` = '{$temp['seekingDomain']}',`studentYear` = '{$temp['studentYear']}'
    								WHERE `id_user` = '{$user_rec[0][0]['user']['id']}'";
    									
    								$res = mysql_query($insertquery,$this->connection);
   									//var_dump($insertquery,$res);
    								    									
    								if(!$res){
    								$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$query");
    									return false;
    								};
    							}
    							else {
    								$insertquery = "INSERT INTO `$table` (`id_user`,`birthdate`,`nationality`,`presentation`,`seeking`,`disponibility`,`seekingDuration`,`seekingDomain`,`studentYear`) 
    								values('{$user_rec[0][0]['user']['id']}','{$temp['birthdate']}','{$temp['nationality']}','{$temp['presentation']}','{$temp['seeking']}','{$temp['disponibility']}','{$temp['seekingDuration']}','{$temp['seekingDomain']}','{$temp['studentYear']}') ";
    							
    								$res = mysql_query($insertquery,$this->connection);
    								//var_dump($insertquery,$res);
    							
    							if(!$res){
    								$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$query");
    								return false;
    							}
    							else {$user_rec[0][0][$table]['id']= mysql_insert_id();}
    							
    							
    							}
    							break;
    						case 'user'  :
    							//var_dump($temp);
    							$insertquery = 
    								"UPDATE user SET
    								`name` = '{$temp['name']}',
    								`surname` = '{$temp['surname']}',
    								`gender` = '{$temp['gender']}',".
    								//`id_town` = '{$user_rec[0][0]['town']['id']}',
    								"`phonenumber` = '{$temp['phonenumber']}'
    								WHERE `id`={$user_rec[0][0]['user']['id']}";
    							
    							$res = mysql_query($insertquery,$this->connection);
/**/   								//var_dump($insertquery,$res);    							
    							if(!$res){
    								$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$insertquery");
    								return false;
    							};
    							break;
    						case 'proexperience' :
    							
    							//Gestion des FK
    							$fk = 'town'; //en fait FK = id_town, pointe vers un element de town
    							$fk2 = 'society';
    							
    							if(!isset($user_rec[$i][$j][$fk]['id']) || !isset($user_rec[$i][$j][$fk2]['id'])){
    								$this->HandleError("FK Error : $fk non renseigné dans $i $j $table");
    								return false;
    							}
    							else 
    							{
    								if(isset($temp['job']))
    								{
    									$temp['isASmallJob'] = isset($temp['isASmallJob'])?"true":"false";
    									$fkId = $user_rec[$i][$j][$fk]['id'];
    									$fk2Id = $user_rec[$i][$j][$fk2]['id'];
    									
    									$insertquery = "INSERT INTO `$table` (`id_town`,`id_society`,`job`,`isASmallJob`) values('$fkId','$fk2Id','{$temp['job']}','{$temp['isASmallJob']}')";
    									$res = mysql_query($insertquery,$this->connection);
    										
    										
    									//var_dump($insertquery,$res);
    										
    										
    										
    									if(!$res){
    										$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$query");
    										return false;
    									}
    									else {
    										if( isset($user_rec[$i][$j][$table]['id']) ) { //vérifier que $user_rec[$i][$j][$table]['id'] correspondent bien à l'ancienne id de la table lorsque l'utilisateur fait une modif
    											//Implique modification en cours et pas ajout simple
    											$user_rec[$i][$j][$table]['oldId'] = $user_rec[$i][$j][$table]['id'];
    											
    										};
    										$user_rec[$i][$j][$table]['id']= mysql_insert_id();
    									}
    										
    								}
    								else {
    									$this->HandleError("Erreur : champs obligatoire(s) non renseigné(s) dans $i $j $table");
    									return false;
    								}
    							
    							}
    							break;
    						
    						case 'art':
    						case 'country':
    						case 'diploma' :
    						case 'infoLanguage':
    						case 'software':
    						case 'sport':
    						case 'town':
    						case 'tongue':

    								
    							if(isset($temp['name']))
    							{
    								$insertquery = "INSERT INTO `$table` (`name`) values('{$temp['name']}')";
    								$res = mysql_query($insertquery,$this->connection);    								
    								
    								if(!$res){
    									$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$query");
    									return false;
    								}
    								else {
    									if( isset($user_rec[$i][$j][$table]['id']) ) { //vérifier que $user_rec[$i][$j][$table]['id'] correspondent bien à l'ancienne id de la table lorsque l'utilisateur fait une modif
    										//Implique modification en cours et pas ajout simple
    										$user_rec[$i][$j][$table]['oldId'] = $user_rec[$i][$j][$table]['id'];
    										
    									};
    									$user_rec[$i][$j][$table]['id']= mysql_insert_id();
    								
    								}
    								
    							}
    							else {
    								$this->HandleError("Erreur : nom non renseigné dans $i $j $table");
    								return false;
    							}
    							break;
    							
    						case 'establishment' :
    							
    						
    							//Gestion des FK
    							$fk = 'town'; //en fait FK = id_town, pointe vers un element de town
    							//var_dump($i,$j,$fk,'id');
    							if(!isset($user_rec[$i][$j][$fk]['id'])){
    								$this->HandleError("FK Error : $fk non renseigné dans $i $j $table");
    								return false;
    							}
    							else {
    								if(isset($temp['name']) 
    										/*&& isset($temp['type'])*/
    										/*&& isset($temp['domaine'])*/)
    								{
    									$fkId = $user_rec[$i][$j][$fk]['id'];
    									
    									$insertquery = "INSERT INTO `$table` (`id_town`,`name`) values('$fkId','{$temp['name']}')";
    									$res = mysql_query($insertquery,$this->connection);
    								
    									//var_dump($insertquery,$res);
    									
    									if(!$res){
    										$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$query");
    										return false;
    									}
    									else {
    										if( isset($user_rec[$i][$j][$table]['id']) ) { //vérifier que $user_rec[$i][$j][$table]['id'] correspondent bien à l'ancienne id de la table lorsque l'utilisateur fait une modif
    											//Implique modification en cours et pas ajout simple
    											$user_rec[$i][$j][$table]['oldId'] = $user_rec[$i][$j][$table]['id'];
    											
    										};
    										$user_rec[$i][$j][$table]['id']= mysql_insert_id();
    									}
    								
    								}
    								else {
    									$this->HandleError("Erreur : champs obligatoire(s) non renseigné(s) dans $i $j $table");
    									return false;
    								}
    								
    							}
    							break;
    							
    						case 'association' :
    							if(isset($temp['name']))
    							{    						
    								$insertquery = "INSERT INTO `$table` (`name`,`goals`,`budget`,`numberOfMembers`) values('{$temp['name']}','{$temp['goals']}','{$temp['budget']}','{$temp['numberOfMembers']}')";
    								$res = mysql_query($insertquery,$this->connection);
    								    								
    								if(!$res) {
    									$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j \nquery $insertquery");
    									return false;
    								}
    								else {
    									if(isset($user_rec[$i][$j][$table]['id'])) {
    										$user_rec[$i][$j][$table]['oldId'] = $user_rec[$i][$j][$table]['id'];
    									};
    									$user_rec[$i][$j][$table]['id'] = mysql_insert_id();
    								}
    							}
    							
    							else {
    								$this->HandleError("Erreur : nom non renseigné dans $i $j $table");
    								return false;
    							}
    							break;
    							
    						case 'society' : 
    							if(isset($temp['raisonSociale']))
    							{
    								$insertquery = "INSERT INTO `$table` (`raisonSociale`) values('{$temp['raisonSociale']}')";
    								$res = mysql_query($insertquery,$this->connection);
    							
    								
    								//var_dump($insertquery,$res);
    								
    								if(!$res){
    									$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$insertquery");
    									return false;
    								}
    								else {
    									if( isset($user_rec[$i][$j][$table]['id']) ) { //vérifier que $user_rec[$i][$j][$table]['id'] correspondent bien à l'ancienne id de la table lorsque l'utilisateur fait une modif
    										//Implique modification en cours et pas ajout simple
    										$user_rec[$i][$j][$table]['oldId'] = $user_rec[$i][$j][$table]['id'];
    										
    									};
    									$user_rec[$i][$j][$table]['id']= mysql_insert_id();
    								}
    							
    							}
    							else {
    								$this->HandleError("Erreur : nom non renseigné dans $i $j $table");
    								return false;
    							}
    							break;
    							
    						case 'Parle' :
    								
    							if(isset($user_rec[0][0]['student']['id']) && isset($user_rec[$i][$j]['tongue']['id']))
    							{
    									
    								if(isset($user_rec[$i][$j][$table]['id'])) {
    									//Modif
    									$idVirguleChamps = "`id`,";
    									$idVirguleValeur = "'{$user_rec[$i][$j][$table]['id']}',";
    								}
    								else {
    									$idVirguleChamps = "";
    									$idVirguleValeur = "";
    								}
    								$fkId = array();
    								$fkId[0] = $user_rec[0][0]['student']['id'];
    								$fkId[1] = $user_rec[$i][$j]['tongue']['id'];
    									

    								/*if(isset($temp['beggining'])
    								 && isset($temp['end']))
    								{*/
    								$insertquery =
    								"INSERT INTO `$table`
    								($idVirguleChamps `id_student`,`id_tongue`,`estimatedLevel`)
    								values($idVirguleValeur '{$fkId[0]}','{$fkId[1]}','{$temp['estimatedLevel']}')
    								ON DUPLICATE KEY UPDATE
    								`id_student`='{$fkId[0]}',`id_tongue`='{$fkId[1]}',`estimatedLevel`= '{$temp['estimatedLevel']}'";
    								$res = mysql_query($insertquery,$this->connection);
    									
    								//var_dump($insertquery,$res);

    								if(!$res){
    									$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$query");
    									return false;
    								};
    								/*}*/
    									
    									
    									
    							}
    							else {
    								$this->HandleError("FK Error : $fk non renseigné dans $i $j $table");
    								return false;
    							}
    							break;
    								
    						case 'SaitProgrammerEn' :
    							$tableEntite = 'infoLanguage';
    						case 'SaitUtiliserLogiciel':
    							$tableEntite = isset($tableEntite)?$tableEntite:'software';
    							
    							if(isset($user_rec[0][0]['student']['id']) && isset($user_rec[$i][$j][$tableEntite]['id']))
    							{

    								if(isset($user_rec[$i][$j][$table]['id'])) {
    									//Modif
    									$idVirguleChamps = "`id`,";
    									$idVirguleValeur = "'{$user_rec[$i][$j][$table]['id']}',";
    								}
    								else {
    									$idVirguleChamps = "";
    									$idVirguleValeur = "";
    								}
    								$fkId = array();
    								$fkId[0] = $user_rec[0][0]['student']['id'];
    								$fkId[1] = $user_rec[$i][$j][$tableEntite]['id'];

    									
    								/*if(isset($temp['beggining'])
    								 && isset($temp['end']))
    								{*/
    								$insertquery =
    								"INSERT INTO `$table`
    								($idVirguleChamps `id_student`,`id_$tableEntite`,`level`)
    								values($idVirguleValeur '{$fkId[0]}','{$fkId[1]}','{$temp['level']}')
    								ON DUPLICATE KEY UPDATE
    								`id_student`='{$fkId[0]}',`id_$tableEntite`='{$fkId[1]}',`level`= '{$temp['level']}'";
    								$res = mysql_query($insertquery,$this->connection);
    									
    								if(!$res){
    									$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$query");
    									return false;
    								};
    								/*}*/



    							}
    							else {
    								$this->HandleError("FK Error : $tableEntite non renseigné dans $i $j $table");
    								return false;
    							}
    							unset($tableEntite);
    							break;
    							
    						case 'AEtudieA' :
    							
    							if(isset($user_rec[0][0]['student']['id']) && isset($user_rec[$i][$j]['establishment']['id']) && isset($user_rec[$i][$j]['diploma']['id'])){

    								if(isset($user_rec[$i][$j][$table]['id'])) {
    									//Modif
    									$idVirguleChamps = "`id`,";
    									$idVirguleValeur = "'{$user_rec[$i][$j][$table]['id']}',";
    								}
    								else {
    									$idVirguleChamps = "";
    									$idVirguleValeur = "";
    								}

    								$fkId = array();
    								$fkId[0] = $user_rec[0][0]['student']['id'];
    								$fkId[1] = $user_rec[$i][$j]['establishment']['id'];
    								$fkId[2] = $user_rec[$i][$j]['diploma']['id'];
    								$beg = $temp['beggining'];
    								$end =$temp['end'] ;
    								/*if(isset($temp['beggining'])
    								 && isset($temp['end']))
    								{*/
    								$insertquery =
    								"INSERT INTO `$table`
    								($idVirguleChamps `id_student`,`id_establishment`,`id_diploma`,`beggining`,`end`)
    								values($idVirguleValeur '{$fkId[0]}','{$fkId[1]}','{$fkId[2]}','{$beg}','{$end}')
    								ON DUPLICATE KEY UPDATE
    								`id_student`='{$fkId[0]}',`id_establishment`='{$fkId[1]}',`id_diploma`='{$fkId[2]}',`beggining`='$beg',`end`='$end'";
    								$res = mysql_query($insertquery,$this->connection);
    								
    								//var_dump($insertquery,$res);
    				
    								
    								if(!$res){
    									$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$query");
    									return false;
    								};
    								/*}*/



    							}
    							else {
    								$this->HandleError("FK Error : $fkId non renseignée dans $i $j $table");
    								return false;
    							}
    							break;
    							
    						case 'APratiqueSport' :
    							$sportouart = "sport";
    						case 'APratiqueArt':
    							$sportouart = isset($sportouart)?$sportouart:"art";
    							
    							if(isset($user_rec[0][0]['student']['id']) && isset($user_rec[$i][$j][$sportouart]['id'])){
    									
    								if(isset($user_rec[$i][$j][$table]['id'])) {
    									//Modif
    									$idVirguleChamps = "`id`,";
    									$idVirguleValeur = "'{$user_rec[$i][$j][$table]['id']}',";
    								}
    								else {
    									$idVirguleChamps = "";
    									$idVirguleValeur = "";
    								}
    									
    								$fkId = array();
    								$fkId[0] = $user_rec[0][0]['student']['id'];
    								$fkId[1] = $user_rec[$i][$j][$sportouart]['id'];
    								$duration = isset($temp['duration'])?$temp['duration']:"";
    								$quality =isset($temp['quality'])?$temp['quality']:"";
    								$addSth = isset($temp['addSth'])?$temp['addSth']:"";
    								/*if(isset($temp['beggining'])
    								 && isset($temp['end']))
    								{*/
    								$insertquery =
    								"INSERT INTO `$table`
    								($idVirguleChamps `id_student`,`id_$sportouart`,`duration`,`quality`,`addSth`)
    								values($idVirguleValeur '{$fkId[0]}','{$fkId[1]}','{$duration}','{$quality}','{$addSth}')
    								ON DUPLICATE KEY UPDATE
    								`id_student`='{$fkId[0]}',`id_$sportouart`='{$fkId[1]}',`duration`='$duration',`quality`='$quality',`addSth`='$addSth'";
    								$res = mysql_query($insertquery,$this->connection);
    									
    								//var_dump($insertquery,$res);
    									
    									
    								if(!$res){
    									$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$query");
    									return false;
    								};
    								/*}*/
    									
    									
    									
    							}
    							else {
    								$this->HandleError("FK Error : $fkId non renseigné dans $i $j $table");
    								return false;
    							}
    							unset($sportouart);
    							break;

    						case 'AFaitPartieDeAssociation' :
    							if(isset($user_rec[0][0]['student']['id']) && isset($user_rec[$i][$j]['association']['id'])) {
    								if(isset($user_rec[$i][$j][$table]['id'])) {
    									//Modif
    									$idVirguleChamps = "`id`,";
    									$idVirguleValeur = "'{$user_rec[$i][$j][$table]['id']}',";
    								}
    								else {
    									$idVirguleChamps = "";
    									$idVirguleValeur = "";
    								}
    								
    								$fkId = array();
    								$fkId[0] = $user_rec[0][0]['student']['id'];
    								$fkId[1] = $user_rec[$i][$j]['association']['id'];
    								$role = isset($temp['role'])?$temp['role']:"";
    								$quality =isset($temp['quality'])?$temp['quality']:"" ;
    								$description = isset($temp['description'])?$temp['description']:"";
    								/*if(isset($temp['beggining'])
    								 && isset($temp['end']))
    								{*/
    								$insertquery =
    								"INSERT INTO `$table`
    								($idVirguleChamps `id_student`,`id_association`,`role`,`quality`,`description`)
    								values($idVirguleValeur '{$fkId[0]}','{$fkId[1]}','{$role}','{$quality}','{$description}')
    								ON DUPLICATE KEY UPDATE
    								`id_student`='{$fkId[0]}',`id_association`='{$fkId[1]}',`role`='$role',`quality`='$quality', `description`='$description'";
    								$res = mysql_query($insertquery,$this->connection);
    								
    								//var_dump($insertquery,$res);
    								
    								if(!$res){
    								$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$query");
    								return false;
    								};
    								/*}*/
    							}
    							else {
    								$this->HandleError("FK Error : fk non renseigné dans $i $j $table");
    								return false;
    							}
    							break;
    								
    						case 'ATravailleA' :
    								
    							if(isset($user_rec[0][0]['student']['id']) && isset($user_rec[$i][$j]['proexperience']['id'])){

    								if(isset($user_rec[$i][$j][$table]['id'])) {
    									//Modif
    									$idVirguleChamps = "`id`,";
    									$idVirguleValeur = "'{$user_rec[$i][$j][$table]['id']}',";
    								}
    								else {
    									$idVirguleChamps = "";
    									$idVirguleValeur = "";
    								}

    								$fkId = array();
    								$fkId[0] = $user_rec[0][0]['student']['id'];
    								$fkId[1] = $user_rec[$i][$j]['proexperience']['id'];
    								$beg = isset($temp['beggining'])?$temp['beggining']:"";
    								$end =isset($temp['end'])?$temp['end']:"" ;
    								$mission = isset($temp['mission'])?$temp['mission']:"";
    								/*if(isset($temp['beggining'])
    								 && isset($temp['end']))
    								{*/
    								$insertquery =
    								"INSERT INTO `$table`
    								($idVirguleChamps `id_student`,`id_proexperience`,`beggining`,`end`,`mission`)
    								values($idVirguleValeur '{$fkId[0]}','{$fkId[1]}','{$beg}','{$end}','{$mission}')
    								ON DUPLICATE KEY UPDATE
    								`id_student`='{$fkId[0]}',`id_proexperience`='{$fkId[1]}',`beggining`='$beg',`end`='$end', `mission`='$mission'";
    								$res = mysql_query($insertquery,$this->connection);

    								//var_dump($insertquery,$res);
    								
    								if(!$res){
    									$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$query");
    									return false;
    								};
    								/*}*/
    							}
    							else {
    								$this->HandleError("FK Error : $fkId non renseigné dans $i $j $table");
    								return false;
    							}
    							break;
    						case 'AEteAPays' :
    								
    							if(isset($user_rec[0][0]['student']['id']) && isset($user_rec[$i][$j]['country']['id'])){
    									
    								if(isset($user_rec[$i][$j][$table]['id'])) {
    									//Modif
    									$idVirguleChamps = "`id`,";
    									$idVirguleValeur = "'{$user_rec[$i][$j][$table]['id']}',";
    								}
    								else {
    									$idVirguleChamps = "";
    									$idVirguleValeur = "";
    								}
    									
    								$fkId = array();
    								$fkId[0] = $user_rec[0][0]['student']['id'];
    								$fkId[1] = $user_rec[$i][$j]['country']['id'];
    								$beg = isset($temp['beggining'])?$temp['beggining']:"";
    								$end =isset($temp['end'])?$temp['end']:"" ;
    	
    								/*if(isset($temp['beggining'])
    								 && isset($temp['end']))
    								{*/
    								$insertquery =
    								"INSERT INTO `$table`
    								($idVirguleChamps `id_student`,`id_country`,`beggining`,`end`)
    								values($idVirguleValeur '{$fkId[0]}','{$fkId[1]}','{$beg}','{$end}')
    								ON DUPLICATE KEY UPDATE
    								`id_student`='{$fkId[0]}',`id_country`='{$fkId[1]}',`beggining`='$beg',`end`='$end'";
    								$res = mysql_query($insertquery,$this->connection);
    									
    								//var_dump($insertquery,$res);
    									
    								if(!$res){
    									$this->HandleDBError("Echec d'insertion dans la table '$table' pour $i $j\nquery:$query");
    									return false;
    								};
    								/*}*/
    							}
    							else {
    								$this->HandleError("FK Error : $fkId non renseigné dans $i $j $table");
    								return false;
    							}
    							break;
    								
    					}// fin switch
    				}// fin if
    			}// fin foreach
    			
    		}//fin for
    		
    	}//fin for
    	
    	$listeTableEntite = array('art','association','country','infoLanguage','diploma',
    			'establishment','proexperience','software','sport','tongue','society','town');
    	
    	$this->ClearUnlinkedInDB($listeTableEntite,$user_rec);
    	
    	return true;
    }
    
    
    function ClearUnlinkedInDB($listeTableEntite,$user_rec) {
    	for($j=0;$j<$this->numSection;$j++) {
    		for($i=0;$i<$this->maxAjout;$i++){
    			foreach($listeTableEntite as $table) {
    				if(isset($user_rec[$i][$j][$table]['oldId'])) {
    						
    					$oldFK = $user_rec[$i][$j][$table]['oldId'];
    					$qryDelete = "DELETE FROM $table WHERE `id`='{$oldFK}'";
    					//var_dump($qryDelete);
    					if(!$this->isLinked($table,$oldFK))
    					{
    						$res = mysql_query( $qryDelete,$this->connection);
    						
    						if(!$res) {
    							$this->HandleDBError("Error while deleting `id`='$oldFK' from `$table` \nquery:$qryDelete");
    							var_dump($qryDelete,$res);
    							
    						}
    	
    					};
    				}
    			}
    		}
    	}
    }
   
    function numberOfLinks($table,$id) {
    
    	$where = "`id_$table`='$id'";
    	switch($table) {
    		case 'art':
    			$string = "`APratiqueArt`";
    			break;
    		case 'association':
    			$string = "`AFaitPartieDeAssociation`";
    			break;
    		case 'country':
    			$string = "`AEteAPays`";
    			break;
    		case 'diploma':
    		case 'establishment':
    			$string = "`AEtudieA`";
    			break;
    		case 'infoLanguage':
    			$string = "`SaitProgrammerEn`";
    			break;
    		case 'proexperience' :
    			$string = "`ATravailleA`";
    			break;
    		case 'society' :
    			$string = "`user`,`proexperience`";
    			$where = "(`user`.`id_society` = '$id') OR (`proexperience`.`id_society` = '$id')";
    			break;
    		case 'software':
    			$string = "`SaitUtiliserLogiciel`";
    			break;
    		case 'sport' :
    			$string = "`APratiqueSport`";
    			break;
    		case 'tongue' :
    			$string = "`Parle`";
    			break;
    
    
    		case 'town':
    			$string = "`user`,`proexperience`,`establishment`";
    			$table = "town";
    			$where = "(`user`.`id_town` = '$id') OR (`proexperience`.`id_town`) = '$id' OR (`establishment`.`id_town` = '$id')";
    	}
    	 
    	$qry = "SELECT COUNT(*) FROM $string WHERE $where";
    	$res = mysql_query($qry,$this->connection);
    	 
    	if(!$res) {
    		/* Gérer l'erreur de requête */
    		var_dump($qry);
    	}
    	$countArray = mysql_fetch_row($res);
    	$nb = $countArray[0];
    
    	return $nb;
    }
    
    function isLinked($table,$id) {    	
  		$nb = $this->numberOfLinks($table, $id);

    	$isLinked = ($nb == 0)?false:true;
    	
    	return $isLinked;
    }

    function DeleteInProfile($i,$j,$user_rec){

    	$entite = array(
    			0 =>array("establishment","diploma"),
    			1 =>array("proexperience"),
    			2 =>array("infoLanguage"),
    			3 =>array("tongue"),
    			4 =>array("sport"),
    			5 =>array("art"),
    			6 =>array("association"),
    			7 =>array("country"),
    			8 =>array("software")
    	);
    	foreach ($entite[$j] as $entite) {
    		$id_entite = $user_rec[$i][$j][$entite]['id'];
    		
    		if($this->numberOfLinks($entite,$id_entite) <= 1) { //Si l'entité n'est associé qu'à une relation (ie un seul utilisateur l'utilise)
    			$qryDelete = "DELETE FROM $entite WHERE `id`=$id_entite"; //Utilisation de la relation FK en cascade pour relation lors de la suppr d'entité
    			
    			$res = mysql_query($qryDelete,$this->connection);
    		
    			
    			if(!$res) {
    				echo "Requete de suppression non effectuée (cas entité non partagée): $qryDelete";

    				$this->HandleDBError("Requete de suppression non effectuée (cas entité non partagée): $qryDelete");
    				return false;
    			}
    			 
    		}
    		else { //Si l'entité gauche est utilisée par d'autres, on ne supprime que la relation
    			$corr = array(
    					"establishment" => "AEtudieA",
    					"diploma" => "AEtudieA",
    					"proexperience" => "ATravailleA",
    					"infoLanguage" => "SaitProgrammerEn",
    					"tongue" => "Parle",
    					"sport" => "APratiqueSport",
    					"art" => "APratiqueArt",
    					"association" => "AFaitPartieDeAssociation",
    					"country" => "AEteAPays",
    					"software" => "SaitUtiliserLogiciel");
    			
    			$relation = $corr[$entite];
    			
    			$id_rel = $user_rec[$i][$j][$relation]['id'];
    			
    			$qryDelete = "DELETE FROM $relation WHERE `id`=$id_rel";
    			  
    			$set0 = "SET FOREIGN_KEY_CHECKS=0";
    			$set1 = "SET FOREIGN_KEY_CHECKS=1";
    			
    			$res0 = mysql_query($set0,$this->connection);
    			$res = mysql_query($qryDelete,$this->connection);
    			$res1 = mysql_query($set1,$this->connection);
    			
    			if(!res) {
    				echo "Requete de suppression non effectuée (cas entité partagée): $qryDelete";
    				$this->HandleDBError("Requete de suppression non effectuée (cas entité partagée): $qryDelete");
    				return false;
    			}
    			
    			
    		}
    	}
    	 
    }

    function GetProfileFromEmail($email,&$user_rec) // $user_rec doit contenir tout le profil de l'utilisateur -> $user-rec[$index_i][$index_j][table][champs]
    {
    	//Connexion à la base de données
    	if(!$this->DBLogin())
    	{
    		$this->HandleError("Echec d'authentification base de donnée !");
    		return false;
    	}
    	
    	$email = $this->SanitizeForSQL($email);
    	
    	//Table user
    	$query = "Select * from user where email='$email'";
    	$result = mysql_query($query,$this->connection);
    	if(!$result || mysql_num_rows($result) <= 0)
    	{
    		$this->HandleError("Il n'y a pas d'utilisateur avec l'email: $email");
    		return false;
    	}
    	
    	$user_rec[0][0]['user'] = mysql_fetch_assoc($result);
    	
    	$user_rec[0][0]['user']['type'] = (  !isset($user_rec['id_society']) || $user_rec['id_society'] == null) ?'etudiant':'entreprise';
    	
    	
    	
    	if(isset($user_rec[0][0]['user']['id_town']) && $user_rec[0][0]['user']['id_town'] != null) {
    		$query = "SELECT * FROM `town` WHERE `id`=".$user_rec[0][0]['user']['id_town'];
    		$res = mysql_query($query,$this->connection);
    	
    		if($result && mysql_num_rows($result) > 0)
    		{
    			$user_rec[0][0]['town'] = mysql_fetch_assoc($res);
    		}
    	}
    	else {
    		$user_rec[0][0]['user']['town'] = "";
    	}
    		
    	//table student
    	$id_user = $user_rec[0][0]['user']['id'];
    	$query = "Select * from student where id_user='$id_user'";
    	$result = mysql_query($query,$this->connection);
    	
    	//Si l'utilisateur a déjà créé son profil, on le récupère, sinon rien : c'est terminé.
    	if(mysql_num_rows($result) > 0) 
    	{
    		    	$user_rec[0][0]['student'] = mysql_fetch_assoc($result);
    		    	$id_student = $user_rec[0][0]['student']['id'];
    		    	
    		    	/**
    		    	 * Formations. $j = 0
    		    	 */
    		    	$j = 0; $i = 0;
    		    	
    		    	$selectquery = "SELECT * FROM `AEtudieA` WHERE `id_student`=$id_student ORDER BY `end` DESC";
    		    	$result = mysql_query($selectquery,$this->connection);
    		    	
    		    	$user_rec[$i][$j]['AEtudieA'] = mysql_fetch_assoc($result);
    		    	//var_dump($id_student,$result,$user_rec[$i][$j]['AEtudieA'] );
    		    	
    		    	while(!is_bool($user_rec[$i][$j]['AEtudieA'] ) || $user_rec[$i][$j]['AEtudieA'] ) {
    		    		$query = "SELECT * FROM `diploma` WHERE `id`=".$user_rec[$i][$j]['AEtudieA']['id_diploma'];
    		    		$res = mysql_query($query,$this->connection);
    		    		//var_dump($query);
    		    		if($res && mysql_num_rows($res) >0)
    		    			$user_rec[$i][$j]['diploma'] = mysql_fetch_assoc($res);

    		    			$query = "SELECT * FROM `establishment` WHERE `id`=".$user_rec[$i][$j]['AEtudieA']['id_establishment'];
    		    			$res = mysql_query($query,$this->connection);
    		    			if($res && mysql_num_rows($res) >0)
    		    				$user_rec[$i][$j]['establishment'] = mysql_fetch_assoc(mysql_query($query,$this->connection));

    		    				$query = "SELECT * FROM `town` WHERE `id`=".$user_rec[$i][$j]['establishment']['id_town'];
    		    				$res = mysql_query($query,$this->connection);
    		    				if($res && mysql_num_rows($res) >0)
    		    					$user_rec[$i][$j]['town'] = mysql_fetch_assoc(mysql_query($query,$this->connection));

    		    					$query = "SELECT * FROM `country` WHERE `id`=".$user_rec[$i][$j]['town']['id_country'];
    		    					$res = mysql_query($query,$this->connection);
    		    					if($res && mysql_num_rows($res) >0)
    		    						$user_rec[$i][$j]['country'] = mysql_fetch_assoc(mysql_query($query,$this->connection));
    		    		
    		    		$i++;
    		    		$user_rec[$i][$j]['AEtudieA'] = mysql_fetch_assoc($result);
    		    	}
    		    	
    		    	/**
    		    	 * Expérience professionnelle. $j = 1
    		    	 */
    		    	$j = 1; $i = 0;
    		    		
    		    	$selectquery = "SELECT * FROM `ATravailleA` WHERE `id_student`=$id_student ORDER BY end";
    		    	$result = mysql_query($selectquery,$this->connection);

    		    	while($user_rec[$i][$j]['ATravailleA'] = mysql_fetch_assoc($result)) {
    		    		$query = "SELECT * FROM `proexperience` WHERE `id`=".$user_rec[$i][$j]['ATravailleA']['id_proexperience'];
    		    		$user_rec[$i][$j]['proexperience'] = mysql_fetch_assoc(mysql_query($query,$this->connection));
    		    	
    		    			$query = "SELECT * FROM `town` WHERE `id`=".$user_rec[$i][$j]['proexperience']['id_town'];
    		    			$user_rec[$i][$j]['town'] = mysql_fetch_assoc(mysql_query($query,$this->connection));
    		    		
    		    				$query = "SELECT * FROM `country` WHERE `id`=".$user_rec[$i][$j]['town']['id_country'];
    		    				$user_rec[$i][$j]['country'] = mysql_fetch_assoc(mysql_query($query,$this->connection));
    		    			
    		    			$query = "SELECT * FROM `society` WHERE `id`=".$user_rec[$i][$j]['proexperience']['id_society'];
    		    			$user_rec[$i][$j]['society'] = mysql_fetch_assoc(mysql_query($query,$this->connection));
    		    		
    		    		$i++;
    		    	}
    		    	/**
    		    	 * Languages de programmation. $j = 2
    		    	 */
    		    	$j = 2; $i = 0;
    		    	
    		    	$selectquery = "SELECT * FROM `SaitProgrammerEn` WHERE `id_student`=$id_student";
    		    	$result = mysql_query($selectquery,$this->connection);
    		    	
    		    	while($user_rec[$i][$j]['SaitProgrammerEn'] = mysql_fetch_assoc($result)) {
    		    		$query = "SELECT * FROM `infoLanguage` WHERE `id`=".$user_rec[$i][$j]['SaitProgrammerEn']['id_infoLanguage'];
    		    		$user_rec[$i][$j]['infoLanguage'] = mysql_fetch_assoc(mysql_query($query,$this->connection));
    		    	
    		    		$i++;
    		    	}
    		    	
    		    	/**
    		    	 * Langues parlées. $j = 3
    		    	 */
    		    	$j = 3; $i = 0;
    		    	
    		    	$selectquery = "SELECT * FROM `Parle` WHERE `id_student`=$id_student ORDER BY estimatedLevel DESC";
    		    	$result = mysql_query($selectquery,$this->connection);
    		    	
    		    	while($user_rec[$i][$j]['Parle'] = mysql_fetch_assoc($result)) {
    		    		$query = "SELECT * FROM `tongue` WHERE `id`=".$user_rec[$i][$j]['Parle']['id_tongue'];
    		    		$user_rec[$i][$j]['tongue'] = mysql_fetch_assoc(mysql_query($query,$this->connection));
    		    		
    		    		$i++;
    		    	}
    		    	
    		    	/**
    		    	 * Sports pratiqués. $j= 4
    		    	 */
    		    	$j=4; $i=0;
    		    	
    		    	$selectquery = "SELECT * FROM `APratiqueSport` WHERE `id_student`=$id_student";
    		    	$result = mysql_query($selectquery,$this->connection);
    		    	
    		    	while($user_rec[$i][$j]['APratiqueSport'] = mysql_fetch_assoc($result)) {
    		    		$query = "SELECT * FROM `sport` WHERE `id`=".$user_rec[$i][$j]['APratiqueSport']['id_sport'];
    		    		$user_rec[$i][$j]['sport'] = mysql_fetch_assoc(mysql_query($query,$this->connection));
    		    		
    		    		$i++;
    		    	}
    		    	
    		    	/**
    		    	 * Arts pratiqués. $j= 5
    		    	 */
    		    	$j=5; $i=0;
    		    		
    		    	$selectquery = "SELECT * FROM `APratiqueArt` WHERE `id_student`=$id_student";
    		    	$result = mysql_query($selectquery,$this->connection);
    		    		
    		    	while($user_rec[$i][$j]['APratiqueArt'] = mysql_fetch_assoc($result)) {
    		    		$query = "SELECT * FROM `art` WHERE `id`=".$user_rec[$i][$j]['APratiqueArt']['id_art'];
    		    		$user_rec[$i][$j]['art'] = mysql_fetch_assoc(mysql_query($query,$this->connection));
    		    	
    		    		$i++;
    		    	}
    		    	
    		    	/**
    		    	 * Parcours associatif. $j=6
    		    	 */
    		    	$j=6; $i=0;
    		    	
    		    	$selectquery = "SELECT * FROM `AFaitPartieDeAssociation` WHERE `id_student`=$id_student";
    		    	$result = mysql_query($selectquery,$this->connection);
    		    	
    		    	while($user_rec[$i][$j]['AFaitPartieDeAssociation'] = mysql_fetch_assoc($result)) {
    		    		$query = "SELECT * FROM `association` WHERE `id`=".$user_rec[$i][$j]['AFaitPartieDeAssociation']['id_association'];
    		    		$user_rec[$i][$j]['association'] = mysql_fetch_assoc(mysql_query($query,$this->connection));
    		    	
    		    		$i++;
    		    	}
    		    	
    		    	/**
    		    	 * Voyages. $j=7
    		    	 */
    		    	$j=7; $i=0;
    		    	
    		    	$selectquery = "SELECT * FROM `AEteAPays` WHERE id_student=$id_student";
    		    	$result = mysql_query($selectquery,$this->connection);
    		    	
    		    	while($user_rec[$i][$j]['AEteAPays'] = mysql_fetch_assoc($result)) {
    		    		$query = "SELECT * FROM `country` WHERE id=".$user_rec[$i][$j]['AEteAPays']['id_country'];
    		    		$user_rec[$i][$j]['country'] = mysql_fetch_assoc(mysql_query($query,$this->connection));
    		    		
    		    		$i++;
    		    	}
    		    	
    		    	/**
    		    	 * Logiciels connus. $j=8
    		    	 */
    		    	$j=8;$i=0;
    		    	
    		    	$selectquery = "SELECT * FROM `SaitUtiliserLogiciel` WHERE id_student=$id_student";
    		    	$result = mysql_query($selectquery,$this->connection);
    		    	
    		    	while($user_rec[$i][$j]['SaitUtiliserLogiciel']= mysql_fetch_assoc($result)) {
    		    		$qry = "SELECT * FROM `software` WHERE id=".$user_rec[$i][$j]['SaitUtiliserLogiciel']['id_software'];
    		    		$user_rec[$i][$j]['software'] = mysql_fetch_assoc(mysql_query($qry,$this->connection));
    		    		
    		    		$i++;
    		    	}
    		    	
    		    	/**
    		    	 * Conversion des dates : disponibility -> disponibility_day, disponibility_month, disponibility_year
    		    	 */
    		    	$dateList = array('student_birthdate', 'student_disponibility','AEtudieA_beggining','AEtudieA_end','ATravailleA_beggining','ATravailleA_end','AEteAPays_beggining','AEteAPays_end');
    		    	
    		    	foreach($dateList as $value){
    		    		$i=0;
    		    		$j=0;		//$j est un indice servant à identifier le type d'ajout : 0=formation, 1=experience professionnelle...
    		    	
    		    		$typeDate = explode('_',$value);
    		    		for($i=0;$i < $this->maxAjout;$i++) {
    		    			for($j=0;$j< $this->numSection;$j++) {
    		    				if (isset($user_rec[$i][$j][$typeDate[0]][$typeDate[1]])) {
    		    					$date = $user_rec[$i][$j][$typeDate[0]][$typeDate[1]];
    		    					
    		    					$user_rec[$i][$j][$typeDate[0]][$typeDate[1]."_day"] = substr($date,-2);
    		    					$user_rec[$i][$j][$typeDate[0]][$typeDate[1]."_month"] = substr($date,-5,2);
    		    					$user_rec[$i][$j][$typeDate[0]][$typeDate[1]."_year"] = substr($date,0,4);
    		    				};
    		    			}
    		    		}
    		    	}
    	};	
    	
    	return true;
    }
    
    function GetUserIDFromEmail($email,$type,&$user_rec) //
    {
		//echo "début GetUFE<br>";
		
        if(!$this->DBLogin())
        {
            $this->HandleError("Echec d'authentification base de donnée !");
            return false;
        }
	
        $email = $this->SanitizeForSQL($email);


		$insertquery = "Select id,id_society from $this->tablename_user where email='$email'";
        $result = mysql_query($insertquery,$this->connection);  
		
        if(!$result || mysql_num_rows($result) <= 0)
        {
            $this->HandleError("Il n'y a pas d'utilisateur avec l'email: $email");
            return false;
        }
        $user_rec = mysql_fetch_assoc($result);
		$user_rec['type'] = (  !isset($user_rec['id_society']) || $user_rec['id_society'] == null) ?'etudiant':'entreprise';

        return true;
    }
    
    function GetUserFromEmail($email,$type,&$user_rec) //
    {
    	//echo "début GetUFE<br>";
    
    	if(!$this->DBLogin())
    	{
    		$this->HandleError("Echec d'authentification base de donnée !");
    		return false;
    	}
    
    	$email = $this->SanitizeForSQL($email);
    
    
    	$insertquery = "Select * from $this->tablename_user where email='$email'";
    	$result = mysql_query($insertquery,$this->connection);
    
    	if(!$result || mysql_num_rows($result) <= 0)
    	{
    		$this->HandleError("Il n'y a pas d'utilisateur avec l'email: $email");
    		return false;
    	}
    	$user_rec = mysql_fetch_assoc($result);

    	$user_rec['type'] = (  !isset($user_rec['id_society']) || $user_rec['id_society'] == null) ?'etudiant':'entreprise';
    
    	return true;
    }

	
    function SendUserWelcomeEmail(&$user_rec)
    {
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'UTF-8';
        
        $mailer->AddAddress($user_rec['email'],$user_rec['name']);
        
        $mailer->Subject = "Bienvenue sur "."L'équipe Relate\r\n";;

        $mailer->From = $this->GetFromAddress();        
        
        $mailer->Body = $user_rec['name'].",\r\n\r\n".
        "Bienvenue ! Votre inscription à ".$this->sitename." est désormais complète.\r\n".
        "\r\n".
        "Avec toute notre considération,\r\n".
        "L'équipe Relate\r\n";

        if(!$mailer->Send())
        {
            $this->HandleError("Echec de l'envoi du mail de bienvenue.");
            return false;
        }
        return true;
    }
    
    function SendAdminIntimationOnRegComplete(&$user_rec)
    {
        if(empty($this->admin_email))
        {
            return false;
        }
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'UTF-8';
        
        $mailer->AddAddress($this->admin_email);
        
        $mailer->Subject = "Inscription Complète: ".$user_rec['name'];

        $mailer->From = $this->GetFromAddress();         
        
        $mailer->Body ="Un nouvel utilisateur (".$user_rec['type'].") s'est inscrit ".$this->sitename."\r\n".	//
        "Nom: ".$user_rec['name']."\r\n".
        "Email: ".$user_rec['email']."\r\n".
		"Mot de passe haché: ".$user_rec['password']."\r\n";
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    function GetResetPasswordCode($email)
    {
       return substr(md5($email.$this->sitename.$this->rand_key),0,10);
    }
    
    function SendResetPasswordLink($user_rec)
    {
        $email = $user_rec['email'];
		$type = $user_rec['type'];

        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'UTF-8';
        
        $mailer->AddAddress($email,$user_rec['name']);
        
        $mailer->Subject = "Changement de mot de passe ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
        
        $link = $this->GetAbsoluteURLFolder().
                '/resetpwd.php?email='.
                urlencode($email).'&code='.
                urlencode($this->GetResetPasswordCode($email)).'&type='.
				urlencode($type);

        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "Une requête de changement de mot de passe a été envoyée chez ".$this->sitename."\r\n".
        "Veuillez cliquer sur le lien suivant pour compléter la requête: \r\n".$link."\r\n".
        "Avec toute notre considération,\r\n".
        "L'équipe Relate\r\n";
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }
    
    function SendNewPassword($user_rec, $new_password)
    {
        $email = $user_rec['email'];
        
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'UTF-8';
        
        $mailer->AddAddress($email,$user_rec['name']);
        
        $mailer->Subject = "Votre nouveau mot de passe sur ".$this->sitename;

        $mailer->From = $this->GetFromAddress();
        
        $mailer->Body ="Bonjour ".$user_rec['name']."\r\n\r\n".
        "Votre mot de passe a été correctement modifié. ".
        "Voici vos nouveaux identifiants:\r\n".
        "username:".$user_rec['username']."\r\n".
        "password:$new_password\r\n".
        "\r\n".
        "Connectez-vous ici : ".$this->GetAbsoluteURLFolder()."/login.php\r\n".
        "\r\n".
        "Avec toute notre considération,\r\n".
        "L'équipe Relate\r\n";
        
        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }    
    
    function ValidateRegistrationSubmission()
    {
        //This is a hidden input field. Humans won't fill this field.
        if(!empty($_POST[$this->GetSpamTrapInputName()]) )
        {
            //The proper error is not given intentionally
            $this->HandleError("Automated submission prevention: case 2 failed");
            return false;
        }
        
        $validator = new FormValidator();
       	$validator->addValidation("name","req","Remplissez le champ 'Prénom'");
       	$validator->addValidation("surname","req","Remplissez le champ 'Nom'");
        $validator->addValidation("email","email","L'adresse email est incorrecte");
		//$validator->addValidation("email","email_estp","Seul les étudiants de l'ESTP sont autorisés à s'enregistrer");
        $validator->addValidation("email","req","Remplissez le champ 'Email'");
        $validator->addValidation("username","req","Remplissez le champ 'Nom d'utilisateur'");
        $validator->addValidation("password","req","Remplissez le champ 'Mot de passe'");
		$validator->addValidation("type","req","Remplissez le champ 'Type'");

        
        if(!$validator->ValidateForm())
        {
            $error='';
            $error_hash = $validator->GetErrors();
            foreach($error_hash as $inpname => $inp_err)
            {
                $error .= /*$inpname.':'.*/$inp_err."\n";
            }
            $this->HandleError($error);
            return false;
        }        
        return true;
    }
	
	function ValidateChangingProfileSubmission()
    {
        //This is a hidden input field. Humans won't fill this field.
        if(!empty($_POST[$this->GetSpamTrapInputName()]) )
        {
            //The proper error is not given intentionally
            $this->HandleError("Automated submission prevention: case 2 failed");
            return false;
        }
        
        $validator = new FormValidator();
        //$validator->addValidation("0.0.user.name","req","Remplissez le champ 'Nom'");
		//à ajouter : toutes les validations necessaires (si besoin est)

        
        if(!$validator->ValidateForm())
        {
            $error='';
            $error_hash = $validator->GetErrors();
            foreach($error_hash as $inpname => $inp_err)
            {
                $error .= /*$inpname.':'.*/$inp_err."\n";
            }
            $this->HandleError($error);
            return false;
        }        
        return true;
    }
    
    function CollectRegistrationSubmission(&$formvars)
    {
        $formvars['name'] = $this->Sanitize($_POST['name']);
        $formvars['surname'] = $this->Sanitize($_POST['surname']);
        $formvars['email'] = $this->Sanitize($_POST['email']);
        $formvars['username'] = $this->Sanitize($_POST['username']);
        $formvars['password'] = $this->Sanitize($_POST['password']);
		$formvars['type'] = $this->Sanitize($_POST['type']);		//ajout
		
    }
    
    function SendUserConfirmationEmail(&$formvars)
    {
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'UTF-8';
        
        $mailer->AddAddress($formvars['email'],$formvars['name']);
        
        $mailer->Subject = "Votre inscription à ".$this->sitename;

        $mailer->From = $this->GetFromAddress();        
        
        $confirmcode = $formvars['confirmcode'];
        $type = $formvars['type'];
		
        $confirm_url = $this->GetAbsoluteURLFolder().'/confirmreg.php?code='.$confirmcode.'&type='.$type;
        
        $mailer->Body = $formvars['name']."\r\n\r\n".
        "Nous vous remercions de votre inscription à ".$this->sitename."\r\n".
        "Veuillez cliquer sur le lien ci-dessous pour valider votre inscription.\r\n".
        "$confirm_url\r\n".
        "\r\n".
        "L'Equipe Relate\r\n";

        if(!$mailer->Send())
        {
            $this->HandleError("Echec de l'envoi de confirmation d'inscription.");
            return false;
        }
        return true;
    }
    function GetAbsoluteURLFolder()
    {
        $scriptFolder = (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
        $scriptFolder .= $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']);
        return $scriptFolder;
    }
    
    function SendAdminIntimationEmail(&$formvars)
    {
        if(empty($this->admin_email))
        {
            return false;
        }
        $mailer = new PHPMailer();
        
        $mailer->CharSet = 'UTF-8';
        
        $mailer->AddAddress($this->admin_email);
        
        $mailer->Subject = "Nouvelle inscription: ".$formvars['name'];

        $mailer->From = $this->GetFromAddress();         
        
        $mailer->Body ="Un nouvel utilisateur s'est inscrit sur ".$this->sitename."\r\n".
        "Nom: ".$formvars['name']."\r\n".
        "Email: ".$formvars['email']."\r\n".
        "UserName: ".$formvars['username'];
        
        if(!$mailer->Send())
        {
        	echo $mailer->ErrorInfo;
            return false;
        }
        return true;
    }
    
    function SaveToDatabase(&$formvars)
    {
		//$this->tablename_user = 'user';	//
		
        if(!$this->DBLogin())
        {
            $this->HandleError("Echec de la connexion à la base de donnée!");
            return false;
        }
        if(!$this->Ensuretable())
        {
            return false;
        }
        
        if(!$this->IsFieldUnique($formvars,'email'))
        {
            $this->HandleError("Cette adresse mail a déjà été enregistrée.");
            return false;
        }
        
        if(!$this->IsFieldUnique($formvars,'username'))
        {
            $this->HandleError("Ce nom d'utilisateur est réservé. Veuillez en choisir un autre");
            return false;
        }        
        if(!$this->InsertIntoDB($formvars))
        {
            $this->HandleError("Echec d'insertion dans la base de donnée !");
            return false;
        }
        return true;
    }
    
    function IsFieldUnique($formvars,$fieldname)
    {
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select username from $this->tablename_user where $fieldname='".$field_val."'";
        $result = mysql_query($qry,$this->connection);   
        if($result && mysql_num_rows($result) > 0)
        {
            return false;
        }
        return true;
    }
    
    function DBLogin()
    {

        $this->connection = mysql_connect($this->db_host,$this->username,$this->pwd);

        if(!$this->connection)
        {   
            $this->HandleDBError("Database Login failed! Please make sure that the DB login credentials provided are correct");
            return false;
        }
        if(!mysql_select_db($this->database, $this->connection))
        {
            $this->HandleDBError('Failed to select database: '.$this->database.' Please make sure that the database name provided is correct');
            return false;
        }
		
        if(!mysql_query("SET NAMES 'UTF8'",$this->connection))
        {
            $this->HandleDBError('Error setting UTF8 encoding');
            return false;
        }
        return true;
    }    
    
    function Ensuretable()
    {
        $result = mysql_query("SHOW COLUMNS FROM $this->tablename_user");   
        if(!$result || mysql_num_rows($result) <= 0)
        {
            return $this->CreateTable();
        }
        return true;
    }

    function CreateTable() // à modifier complètement selon la nouvelle base
    {
		//Gestion de la structure de la bdd
        $qry = "Create Table $this->tablename_user (".
                "id_user INT NOT NULL AUTO_INCREMENT ,".
                "name VARCHAR( 64 ) NOT NULL ,".
		"surname VARCHAR( 64 ) NOT NULL ,".
				"username VARCHAR( 16 ) NOT NULL ,".
				"password VARCHAR( 32 ) NOT NULL ,".
                "email VARCHAR( 64 ) NOT NULL ,".
                //"birthdate DATE ,".
		"month VARCHAR( 16 ) NOT NULL ,".
		"day VARCHAR( 8 ) NOT NULL ,".
		"year VARCHAR( 4 ) NOT NULL ,".
		"gender ENUM('H','F') NOT NULL DEFAULT 'H' ,".
				"adresstown VARCHAR( 32 ) NOT NULL ,".
                "telnumber VARCHAR( 16 ) NOT NULL ,".
		"nationality VARCHAR( 64 ) NOT NULL ,".
		"prestext VARCHAR( 1000 ) NOT NULL ,".
		"seeking VARCHAR( 10 ) NOT NULL ,".
		"dispomonth VARCHAR( 16 ) NOT NULL ,".
		"dispoyear VARCHAR( 4 ) NOT NULL ,".
		"seeking_duration VARCHAR( 10 ) NOT NULL ,".
		"seeking_domain VARCHAR( 30 ) NOT NULL ,".
				"formname VARCHAR( 165 ) NOT NULL ,".
				"formdipname VARCHAR( 165 ) NOT NULL ,".
				"formtown VARCHAR( 165 ) NOT NULL ,".
				"forminprogress VARCHAR( 10 ) NOT NULL ,".
		"formbegmonth VARCHAR( 85 ) NOT NULL ,".
		"formbegyear VARCHAR( 25 ) NOT NULL ,".
		"formendmonth VARCHAR( 85 ) NOT NULL ,".
		"formendyear VARCHAR( 25 ) NOT NULL ,".
		"formaddsth VARCHAR( 1000 ) NOT NULL ,".
				"proexpname VARCHAR( 165 ) NOT NULL ,".
				"proexpnamejob VARCHAR( 165 ) NOT NULL ,".
				"proexptown VARCHAR( 165 ) NOT NULL ,".
				"proexpinprogress VARCHAR( 165 ) NOT NULL ,".
		"proexpbegmonth VARCHAR( 50 ) NOT NULL ,".
		"proexpbegyear VARCHAR( 25 ) NOT NULL ,".
		"proexpendmonth VARCHAR( 50 ) NOT NULL ,".
		"proexpendyear VARCHAR( 25 ) NOT NULL ,".
		"proexpmission VARCHAR( 1000 ) NOT NULL ,".
				"tonguename VARCHAR( 165 ) NOT NULL ,".
		"tonguelvl VARCHAR( 10 ) NOT NULL DEFAULT '0' ,".
				"sportname VARCHAR( 165 ) NOT NULL ,".
		"sportduration VARCHAR( 165 ) NOT NULL ,".
		"sportqual VARCHAR( 1000 ) NOT NULL ,".
		"sportaddsth VARCHAR( 1000 ) NOT NULL ,".
				"artname VARCHAR( 165 ) NOT NULL ,".
		"artduration VARCHAR( 165 ) NOT NULL ,".
		"artqual VARCHAR( 1000 ) NOT NULL ,".
		"artaddsth VARCHAR( 1000 ) NOT NULL ,".
				"assocname VARCHAR( 165 ) NOT NULL ,".
		"assocrole VARCHAR( 165 ) NOT NULL ,".
		"assocqual VARCHAR( 1000 ) NOT NULL ,".
		"assocdescr VARCHAR( 1000 ) NOT NULL ,".
				"smalljobname VARCHAR( 325 ) NOT NULL ,".
				"smalljobrole VARCHAR( 325 ) NOT NULL ,".
				"smalljobtown VARCHAR( 165 ) NOT NULL ,".
				"smalljobinprogress VARCHAR( 10 ) NOT NULL ,".
		"smalljobbegmonth VARCHAR( 50 ) NOT NULL ,".
		"smalljobbegyear VARCHAR( 25 ) NOT NULL ,".
		"smalljobendmonth VARCHAR( 50 ) NOT NULL ,".
		"smalljobendyear VARCHAR( 25 ) NOT NULL ,".
		"smalljobcomp VARCHAR( 1000 ) NOT NULL ,".
				"travelplace VARCHAR( 165 ) NOT NULL ,".
		"travelbegmonth VARCHAR( 50 ) NOT NULL ,".
		"travelbegyear VARCHAR( 25 ) NOT NULL ,".
		"travelendmonth VARCHAR( 50 ) NOT NULL ,".
		"travelendyear VARCHAR( 25 ) NOT NULL ,".
		"travelrem VARCHAR( 1000 ) NOT NULL ,".
		"infolanguages VARCHAR( 165 ) NOT NULL ,".
		"infolanguageslvl VARCHAR( 10 ) NOT NULL DEFAULT '0' ,".
				"studentyear ENUM('1A','2A','3A','cesure','other') NOT NULL DEFAULT 'other' ,".
				"confirmcode VARCHAR(32) ,".
				"mailbox VARCHAR(1024) ,".
				"sentbox VARCHAR(1024) ,".
                "PRIMARY KEY ( id_user )".
                ")";
                
        if(!mysql_query($qry,$this->connection))
        {
            $this->HandleDBError("Error creating the table \nquery was\n $qry");
            return false;
        }
        return true;
    }
    
    function InsertIntoDB(&$formvars)
    {
    
        $confirmcode = $this->MakeConfirmationMd5($formvars['email']);
        
        $formvars['confirmcode'] = $confirmcode;
        
        $insert_query = 'insert into '.$this->tablename_user.'(
                name,
                surname,
                email,
                username,
                password,
                confirmcode
                )
                values
                (
                "' . $this->SanitizeForSQL($formvars['name']) . '",
                "' . $this->SanitizeForSQL($formvars['surname']) . '",
                "' . $this->SanitizeForSQL($formvars['email']) . '",
                "' . $this->SanitizeForSQL($formvars['username']) . '",
                "' . md5($formvars['password']) . '",
                "' . $confirmcode . '"
                )';      
        if(!mysql_query( $insert_query ,$this->connection))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
            return false;
        }        
        return true;
    }
    function MakeConfirmationMd5($email)
    {
        $randno1 = rand();
        $randno2 = rand();
        return md5($email.$this->rand_key.$randno1.''.$randno2);
    }
    function SanitizeForSQL($str)
    {
        if( function_exists( "mysql_real_escape_string" ) )
        {
              $ret_str = mysql_real_escape_string( $str );
        }
        else
        {
              $ret_str = addslashes( $str );
        }
        return $ret_str;
    }
    
 	/*
    Sanitize() function removes any potential threat from the
    data submitted. Prevents email injections or any other hacker attempts.
    if $remove_nl is true, newline chracters are removed from the input.
    */
    function Sanitize($str,$remove_nl=true)
    {
        $str = $this->StripSlashes($str);

        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
            $str = preg_replace($injections,'',$str);
        }

        return $str;
    }    
    function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }   
    
    function echoSelected($field,$value) {
    	if(isset($_POST[$field]) && $_POST[$field] == $value) {
    		echo 'selected="selected"';
    	}
    	else {
    		echo "";
    	}
    }
	
	
}
?>