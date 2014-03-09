<?PHP
require_once("./include/membersite_config.php");

//Pour être sûr que l'utilisateur est toujours loggué...
if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}

//Pour pouvoir effectuer des requêtes mySQL
if(!$fgmembersite->DBLogin())
{
	$this->HandleError("Echec d'authentification base de donnée !");
	return false;
}


?>


<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<!--  Début de l'entête -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Entreprise</title>
	  <link rel="STYLESHEET" type="text/CSS" href="css/style.css" media="screen" />
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
      <script type="text/javascript" src="popup/jquery-1.8.3.js"></script>
</head>
<!--  Fin de l'entête -->

<body>

<!-- Searchbar : society DB -->
<form id='searchbarSociety' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
<fieldset>
<legend>Rechercher une société</legend>
<input type="text" maxlength=256 name="search"/> 
<?php 
if(isset($_POST['search'])) {
	$societyName = $fgmembersite->SanitizeForSQL($_POST['search']);
	$query = "SELECT * FROM principale.society WHERE raisonSociale LIKE '%$societyName%'";
	
	$result = mysql_query($query,$fgmembersite->connection);
	var_dump($query,$result);
	if(!$result) {
		echo "ECHEC de la requete de recherche";
	}
	else {
		$k = 0;
		
		while($resultatEntreprise = mysql_fetch_assoc($result)) {
			var_dump($resultatEntreprise);	
		}	 
	}
	
}
?>

</fieldset>
</form>

<!-- End of the searchbar  -->

<!-- Displaying random societies -->
<label>Entreprise au hasard</label>
<?php
//Request : fetching of all the societies in DB
$query = "SELECT * FROM principale.society";

$result = mysql_query($query,$fgmembersite->connection);
if(!$result) {
	echo "Echec de la requête";
}

$k=0;
while($society[$k++] = mysql_fetch_assoc($result)) {};
unset($k);
//End of request

//Start generating random societies
$nbSociety = sizeof($society) - 2;

$k=0;
while($k<9) {
	$j = rand(0,$nbSociety);
	
	if(isset($society[$j])) {
		var_dump($society[$j]);
		unset($society[$j]);
		$k++;
	}
}

var_dump($nbSociety,$result,$query);

?>
<!-- End of displaying random societies -->
</body>
</html>
