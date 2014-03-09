<?PHP
require_once("../../../relate/include/membersite_config.php");
require_once("../../../relate/messenger/mailboxsystem.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("../index.php");
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
	
	$fgmembersite->RedirectToURL("messages_envoyes.php");
}

?>

<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Nouveau message</title>
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
			
				<ul class="nav nav-tabs nav-right">
					<li><a href="messages_recus.php">Messages reçus <span class="badge">1</span> </a></li>
					<li><a href="messages_envoyes.php">Messages envoyés</a></li>
					<!--<ul class="nav navbar-nav navbar-right">
						<li><div class="btn-group">
							<a href="messages_recus.html">
								<button type="button" class="btn btn-default">Retour aux messages reçus</button>
							</a>
							</div>
						</li>
					</ul>-->
				</ul>
			
				<br>
				<div class="panel panel-default" id="nouveaumessage">
					<form id="sendmess"  action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
							<div class="panel-heading">
								<h4>Nouveau message</h4>
							</div>
								<div class="list-group">
									<li class="list-group-item">
										<div class="input-group">
											<span class="input-group-addon">@</span>
											<input type="text" class="form-control" name='to' placeholder="Destinataire" maxlength="64" <?php if(isset($_GET['to'])) {echo "value='{$_GET['to']}'";}?>/>
										</div>
										<br>
										<div class="input-group">
											<span class="input-group-addon">#</span>
											<input type="text" class="form-control" name='title' placeholder="Objet" maxlength="128" <?php if(isset($_GET['sub'])) {echo "value='Re : {$_GET['sub']}'";}?>/>
										</div>
										<br>
										<textarea class="form-control" rows="7" name="content" maxlength="2000"></textarea>
									</li>
								</div>
							<div class="panel-footer">
								<div class="row">
									<div class="col-md-1">
										<!--<a href="messages_recus.php">-->
											<div class="btn-group-lg">
												<input type='hidden' name='from' value='<?php echo $email;?>' />
												<input type="submit" class="btn btn-primary" value='Envoyer' name="submitted"/>
											</div>
										<!--</a>-->
									</div>
									<div class="col-md-10">
									
									</div>
									<a href="messages_recus.php">Annuler</a>
								</div>
							</div>
						</form>
				</div>
				
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
