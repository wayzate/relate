<?PHP
require_once("../../../relate/include/membersite_config.php");
require_once("../../../relate/messenger/mailboxsystem.php");

if(!$fgmembersite->CheckLogin() )
{
    $fgmembersite->RedirectToURL("../index.php");
    exit;
}

if(!isset($_GET['n']) && !isset($_GET['m']))
{
	$fgmembersite->RedirectToURL("messages_recus.php");
    exit;
}	

$user_rec = array();
if(!$fgmembersite->GetUserIDFromEmail($fgmembersite->UserEmail(),$fgmembersite->UserType(),$user_rec))
{	
	return false;
}

if(isset($_GET['m'])) {
	$boite_mail = new Mailbox($fgmembersite);
	
	if(!$boite_mail->delMessage($fgmembersite->SanitizeForSQL($_GET['m']),"dest") )
	{
		echo "Echec de la suppression<br/>";
		return false;
	}
	
	//$fgmembersite->RedirectToURL("messages.php");
}

$id_user = $user_rec['id'];
$typeOfUser = $user_rec['type'];

if(isset($_GET['n'])) {
	$imess = $_GET['n'];
	$mb = new Mailbox($fgmembersite);
	$box = array();

	$mb->getMailbox($box,$id_user);

}
else{
	$imess = $_GET['m'];
	$qry = "SELECT message.id, message.title, message.id_user_from, message.id_user_to, message.read, message.date, message.content, user.name AS toName, user.surname AS toSurname, user.email AS toEmail 
	FROM message, user
	WHERE (
	id_user_from =$id_user
	AND user.id = message.id_user_to
	) ";
	$result = mysql_query($qry,$fgmembersite->connection);
	if(!$result) {
		echo "Erreur, requête $qry non effectuée";
		return false;
	}

	$k=0;
	$box = array();
	while ($box[$k++] = mysql_fetch_assoc($result)) {};
}

?>

<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Message</title>
		<link rel="STYLESHEET" type="text/CSS" href=../../bootstrap3/css/bootstrap.css media="screen" />
		<link rel="STYLESHEET" type="text/CSS" href=../../bootstrap3/css/style-home.css media="screen" />
		<script type="text/javascript" src="http://codeorigin.jquery.com/jquery-2.0.3.js"></script>
		<script type="text/javascript" src="../../bootstrap3/js/bootstrap.js"></script>
		<style type="text/css">a:link{text-decoration:none}</style>
		<style type="text/css">ul{list-style-type: none} </style>
	</head>

	<body>
		
	<div class="container" id="top_page">
		
		<div class="container">
			<div class="page-header">
				<div class="row">
					<div class="col-md-8">
						<h1><a href="accueil_etudiant.php">Relate</a></h1>
					</div>
					<div class="col-md-2">
						<br>
						<form class="navbar-form navbar-left" role="search">
							<div class="form-group">
							<input type="text" class="form-control" placeholder="Rechercher">
							</div>
							<!--<button type="submit" class="btn btn-default">Submit</button>-->
						</form>
					</div>
					<div class="col-md-2">
						<br>
						<h4><a href="../index.php">Se déconnecter</a></h4>
					</div>
				</div>
			</div>
		</div>
		
		<nav class="nav navbar-default" role="navigation">
			<div class="navbar-header">
				<!--<ul class="nav navbar-nav">-->
				<ul class="nav nav-justified">
							<li><a href="profil_etudiant.html">
								<ul><li class="menu" id="menu_profil"></li>
									<li>PROFIL</li>
								</ul>
								</a>
							</li>
							<li><a href="offres.php">
								<ul><li class="menu" id="menu_offres"></li>
									<li>OFFRES</li>
								</ul>
								</a>
							</li>
							<li><a href="entreprises.php">
								<ul><li class="menu" id="menu_entreprises"></li>
									<li>ENTREPRISES</li>
								</ul>
								</a>
							</li>
							<li><a href="etudiants.php">
								<ul><li class="menu" id="menu_etudiants"></li>
									<li>ETUDIANTS</li>
								</ul>
								</a>
							</li>
							<li><a href="candidatures.html">
								<ul><li class="menu" id="menu_candidatures"></li>
									<li>CANDIDATURES</li>
								</ul>
								</a>
							</li>
							<li class="active"><a href="messages_recus.php">
								<ul><li class="menu" id="menu_messages"></li>
									<li>MESSAGES</li>
								</ul>
								</a>
							</li>
				</ul>
			</div>
		</nav>
		
		<br>
			
		<div class="container">
			
			
		<div class="row">
			<div class="col-md-9">
			
				<ul class="nav nav-tabs nav-right">
					<li><a href="messages_recus.php">Messages reçus <span class="badge">1</span> </a></li>
					<li><a href="messages_envoyes.php">Messages envoyés</a></li>
					<ul class="nav navbar-nav navbar-right">
						<li><div class="btn-group">
							<a href="message_nouveau.php">
								<button type="button" class="btn btn-success">Nouveau message
								<span class="glyphicon glyphicon-envelope">
								</button>
							</a>
							</div>
						</li>
					</ul>
				</ul>
			
				<br>
				
				<?php 
				$mess = $box[$imess];
				if(isset($_GET['n'])){
					if($mess) {
						echo "<div class='panel panel-default'>
							<div class='panel-heading'>
								<ul>
									<li>
										<div class='row'>
											<div class='col-md-10'>
												<a href=# class='bold'>{$mess['fromName']} {$mess['fromSurname']}</a>
											</div>
											<div class='col-md-2'>
												<a href='message_nouveau.php?to={$mess['fromEmail']}&sub={$mess['title']}'>
													<button type='button' class='btn btn-primary'>Répondre</button>
												</a>
											</div>
									</li>
									<br>
									<li>
										<div class='row'>
											<div class='col-md-9'>
											{$mess['title']}
											</div>
											<div class='col-md-3'>
											{$mess['date']}
											</div>
										</div>	
									</li>
								<ul>

							</div>
							<div class='panel-body'>
								<p>{$mess['content']}</p>
							</div>
							<div class='panel-footer'>
								<div class='row'>
									<div class='col-md-2'>
										<a href='message_nouveau.php?to={$mess['fromEmail']}&sub={$mess['title']}'>
										<span class='glyphicon glyphicon-play'></span>
										Répondre</a>
									</div>
									<div class='col-md-8'></div>
									<div class='col-md-2'>
										<a href='#suppression_message_modal' data-toggle='modal'>
										<span class='glyphicon glyphicon-play'></span>
										Supprimer</a>
												  <div class='modal fade' id='suppression_message_modal'>
													  <div class='modal-dialog' style='width:50%;'>
														  <a class='close' data-dismiss='modal' aria-hidden='true'>&times;</a>
														  <div class='panel panel-default'>
															  <div class='panel-heading'>
																  <h3>Suppression de message</h3>
															  </div>
															  <div class='panel-body' style='text-align: center;'>
																  <p>Etes-vous sûr(e) de vouloir supprimer ce message ?</p>
																  <a href='?m={$mess['id']}' class='btn btn-default'>Oui</a>
																  <a href='#' class='btn btn-default'>Non</a>
															  </div>
														  </div>
													  </div>
												  </div>
									</div>
								</div>
							</div>
							
				</div>";
					}
				}
				?>
				
			</div>
			
			<div class="col-md-3">
					<a href="entreprises.html">
						<div class="well">
							<div>
								<h3>Découvrez les entreprises</h3>
							<p><h5>Consultez les fiches de présentation, les offres et les évènements des entreprises partenaires.</h5><p>
							</div>
						</div>
					</a>
					
					<a href="etudiants.html">
						<div class="well">
							<div>
								<h3>Echangez avec d'autres étudiants</h3>
							<p><h5>Partagez votre point de vue et demandez conseil à des étudiants comme vous.</h5><p>
							</div>
						</div>
					</a>
					
					<a href="candidatures.html">
						<div class="well">
							<div>
								<h3>Consultez vos candidatures</h3>
							<p><h5>Suivez le déroulement de vos candidatures.</h5><p>
							</div>
						</div>
					</a>
			</div>
		</div>
		</div>
			
		<nav class="nav navbar-default">
				
					<ul  class="nav nav-justified">
						<li><a href="accueil_etudiant.html">Relate</a></li>
						<li><a href=#>Copyright 2014</a></li>
						<li><a href=#>Conditions d'utilisation</a></li>
						<li><a href=#>Mentions légales</a></li>
						<li><a href=#>Contactez-nous</a></li>
						<li><a href=#top_page>Haut de page</a></li>
					</ul>
					
			</nav>
			
		</div>
	
	</body>

</html>
