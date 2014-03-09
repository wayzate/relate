<?PHP
require_once("../../../relate/include/membersite_config.php");
require_once("../../../relate/messenger/mailboxsystem.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("../index.php");
    exit;
}

$user_rec = array();
if(!$fgmembersite->GetUserIDFromEmail($fgmembersite->UserEmail(),$fgmembersite->UserType(),$user_rec))
{	
	return false;
}

$id_user = $user_rec['id'];
$typeOfUser = $user_rec['type'];


$sb = new Mailbox($fgmembersite);
$sentbox = array();

$sb->getSentbox($sentbox,$id_user);

?>

<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Messages</title>
		<link rel="STYLESHEET" type="text/CSS" href=../../bootstrap3/css/bootstrap.css media="screen" />
		<link rel="STYLESHEET" type="text/CSS" href=../../bootstrap3/css/style-home.css media="screen" />
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
			
				<ul class="nav nav-tabs">
					<li><a href="messages_recus.php">Messages reçus <span class="badge">2</span> </a></li>
					<li  class="active"><a href="#">Messages envoyés</a></li>
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
			
				<div class="list-group">
						<?php 
						foreach($sentbox as $i => $mess) {		
							if($mess) {
							$objet = ($mess['title'] == "" || !isset($mess['title']))?"- Aucun objet -":$mess['title'];
								echo "<a href='message_envoye.php?m=$i' class='list-group-item'>
											<div class='row'>
												<div class='col-md-4'>
													À<span class='bold'> {$mess['toName']} {$mess['toSurname']}</span>
												</div>
												<div class='col-md-5'>
												$objet
												</div>
												<div class='col-md-3'>
												{$mess['date']}
												</div>
											</div>
										</a>
										<br />";
							}
						}

						?>
						<!--<a href="message_envoye.html" class="list-group-item">
							<div class="col-md-4">
							Destinataire
							</div>
							<div class="col-md-7">
							Objet
							</div>
							Date
						</a>
						
						<a href="message_envoye.html" class="list-group-item">
							<div class="col-md-4">
							Destinataire
							</div>
							<div class="col-md-7">
							Objet
							</div>
							Date
						</a>

						<a href="message_envoye.html" class="list-group-item">
							<div class="col-md-4">
							Destinataire
							</div>
							<div class="col-md-7">
							Objet
							</div>
							Date
						</a>

						<a href="message_envoye.html" class="list-group-item">
							<div class="col-md-4">
							Destinataire
							</div>
							<div class="col-md-7">
							Objet
							</div>
							Date
						</a>

						<a href="message_envoye.html" class="list-group-item">
							<div class="col-md-4">
							Destinataire
							</div>
							<div class="col-md-7">
							Objet
							</div>
							Date
						</a>-->
				</div>
					
					<ul class="pagination">
						<li class="disabled"><a href=#>&laquo;</a></li>
						<li class="active"><a href=#>1</a></li>
						<li><a href=#>2</a></li>
						<li><a href=#>3</a></li>
						<li><a href=#>4</a></li>
						<li><a href=#>5</a></li>
						<li><a href=#>&raquo;</a></li>
					</ul>
			</div>
			
			<div class="col-md-3">
					<a href="profil_etudiant.html">
						<div class="well">
							<div>
								<h3>Editez votre profil</h3>
							<p><h5>Modifiez vos informations personnelles constituant votre profil étudiant.</h5><p>
							</div>
						</div>
					</a>
					
					<a href="offres.html">
						<div class="well">
							<div>
								<h3>Recherchez une offre</h3>
							<p><h5>Recherchez et postulez aux offres de stage ou d'emploi correspondant à vos critères.</h5><p>
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
