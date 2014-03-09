<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Entreprises</title>
		<link rel="STYLESHEET" type="text/CSS" href=../../bootstrap3/css/bootstrap.css media="screen" />
		<link rel="STYLESHEET" type="text/CSS" href=../../bootstrap3/css/style-home.css media="screen" />
		<link rel="STYLESHEET" type="text/CSS" href=../../../relate/autocomplete/style_autocomplete.css media="screen" />
		<style type="text/css">a:link{text-decoration:none}</style>
		<style type="text/css">ul{list-style-type: none} </style>
		<script type="text/javascript" src="../../../relate/autocomplete/jquery-1.2.1.pack.js"></script>
		<script type="text/javascript">
	function lookup(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestions').hide();
		} else {
			$.post("../../../relate/autocomplete/rpc_entreprise.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestions').show();
					$('#autoSuggestionsList').html(data);
				}
			});
		}
	} // lookup
	
	function fill(thisValue) {
		$('#inputString').val(thisValue);
		setTimeout("$('#suggestions').hide();", 200);
	}
		</script>
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
							<li class="active"><a href="#">
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
							<li><a href="messages_recus.php">
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
					<div class="navbar-brand">Trier par :</div>
					<li class="active"><a href="#">Ordre alphabétique</a></li>
					<li><a href="entreprises_sectact.html">Secteur d'activité</a></li>
					<ul class="nav navbar-nav navbar-right">
						<li>
							<form class="navbar-form navbar-left" role="search">
								<div class="form-group">
									<input type="text" class="form-control" size="30" value="" id="inputString" onkeyup="lookup(this.value);" onblur="fill();" placeholder="Rechercher une entreprise"/>
								</div>
								<!--<button type="submit" class="btn btn-default">Go !</button>-->
								<div class="suggestionsBox" id="suggestions" style="display: none;">
									<span class="glyphicon glyphicon-eject" id="upArrow"></span>
									<div class="suggestionList" id="autoSuggestionsList">
										&nbsp;
									</div>
								</div>
							</form>
						</li>
					</ul>
				</ul>
				
				<br>
			
				<ul class="pagination">
						<li class="disabled"><a href=#>&laquo;</a></li>
						<li><a href=#>A</a></li>
						<li><a href=#>B</a></li>
						<li class="active"><a href=#>C</a></li>
						<li><a href=#>D</a></li>
						<li><a href=#>E</a></li>
						<li><a href=#>..</a></li>
						<li><a href=#>Y</a></li>
						<li><a href=#>Z</a></li>
						<li><a href=#>&raquo;</a></li>
					</ul>
					
				<br>
				
				<div class="list-group">
						
						<a href="entreprise.html" class="list-group-item">
							<div class="row">
								<div class="col-md-2">
								<img src="..." alt="..." class="img-thumbnail">
								</div>
								<div class="col-md-5">
								Raison sociale
								</div>
								<div class="col-md-5">
								Secteur d'activité
								</div>
							</div>
						</a>
						
						<a href="entreprise.html" class="list-group-item">
							<div class="row">
								<div class="col-md-2">
								<img src="..." alt="..." class="img-thumbnail">
								</div>
								<div class="col-md-5">
								Raison sociale
								</div>
								<div class="col-md-5">
								Secteur d'activité
								</div>
							</div>
						</a>

						<a href="entreprise.html" class="list-group-item">
							<div class="row">
								<div class="col-md-2">
								<img src="..." alt="..." class="img-thumbnail">
								</div>
								<div class="col-md-5">
								Raison sociale
								</div>
								<div class="col-md-5">
								Secteur d'activité
								</div>
							</div>
						</a>

						<a href="entreprise.html" class="list-group-item">
							<div class="row">
								<div class="col-md-2">
								<img src="..." alt="..." class="img-thumbnail">
								</div>
								<div class="col-md-5">
								Raison sociale
								</div>
								<div class="col-md-5">
								Secteur d'activité
								</div>
							</div>
						</a>

						<a href="entreprise.html" class="list-group-item">
							<div class="row">
								<div class="col-md-2">
								<img src="..." alt="..." class="img-thumbnail">
								</div>
								<div class="col-md-5">
								Raison sociale
								</div>
								<div class="col-md-5">
								Secteur d'activité
								</div>
							</div>
						</a>
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
					<!--<a href=#>
						<div class="well">
							<div>
								<h3>Recherchez une offre</h3>
							<p><h5>Recherchez et postulez aux offres de stage ou d'emploi correspondant à vos critères.</h5><p>
							</div>
						</div>
					</a>-->
					
					<a href="evenements.html">
						<div class="well">
							<div>
								<h3>Parcourez les évènements des entreprises</h3>
							<p><h5>Prenez connaissance des évènements et actualités des entreprises.</h5><p>
							</div>
						</div>
					</a>
					
					<div class="panel panel-default">
							<div class="panel-heading">
								<h3>Suggestion d'entreprises</h3>
							</div>
							<div class="panel-body">
								<a href="entreprise.html"><div class="panel panel-default" id="shadow"><img src="..." alt="..." class="img-thumbnail">Entreprise 1</div></a>
								<a href="entreprise.html"><div class="panel panel-default" id="shadow"><img src="..." alt="..." class="img-thumbnail">Entreprise 2</div></a>
								<a href="entreprise.html"><div class="panel panel-default" id="shadow"><img src="..." alt="..." class="img-thumbnail">Entreprise 3</div></a>
							</div>
					</div>
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
