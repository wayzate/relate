<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Offres</title>
		<link rel="STYLESHEET" type="text/CSS" href=../../bootstrap3/css/bootstrap.css media="screen" />
		<link rel="STYLESHEET" type="text/CSS" href=../../bootstrap3/css/style-home.css media="screen" />
		<link rel="STYLESHEET" type="text/CSS" href=../../../relate/autocomplete/style_autocomplete.css media="screen" />
		<style type="text/css">a:link{text-decoration:none}</style>
		<style type="text/css">ul{list-style-type: none} </style>
		<!--<link rel="stylesheet" type="text/css" href="../../bootstrap-dropdown-checkbox/css/bootstrap-dropdown-checkbox.css">-->
		<!--<script type="text/javascript" src="http://codeorigin.jquery.com/jquery-1.10.2.js"></script>-->
		<script type="text/javascript" src="http://codeorigin.jquery.com/jquery-2.0.3.js"></script>
		<script type="text/javascript" src="../../bootstrap3/js/bootstrap.js"></script>
		<!--<script type="text/javascript" src="../../bootstrap-dropdown-checkbox/js/bootstrap-dropdown-checkbox.js"></script>-->
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
							<li class="active"><a href="#">
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
			
			
			
				<nav class="navbar navbar-default" role="navigation" id="padding30">
					<br>
					<ul class="nav nav-justified">
						<li>
							<div class="dropdown">
								<a class="dropdown-toggle btn" data-toggle="dropdown" href="#">
									Type
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu dropdown-menu-form" role="menu">
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										Stage 
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										CDD 
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										CDI 
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										VIE 
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										Alternance 
									</label>
									</div>
									</li>
								</ul>
							</div>
						</li>
					
						<li>
							<div class="dropdown">
								<a class="dropdown-toggle btn" data-toggle="dropdown" href="#">
									Durée
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu dropdown-menu-form" role="menu">
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										1 à 2 mois
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										2 à 3 mois
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										3 à 5 mois 
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										5 à 6 mois 
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										6 à 9 mois 
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										9 à 12 mois 
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										plus d'1 an 
									</label>
									</div>
									</li>
								</ul>
							</div>
						</li>
						
						<li>
							<div class="dropdown">
								<a class="dropdown-toggle btn" data-toggle="dropdown" href="#">
									Disponibilité
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu dropdown-menu-form" role="menu">
									<li>
										<div class="form-group">
											<div class="input-group">
												<input type="month" class="form-control">
											</div>
										</div>
									</li>
								</ul>
							</div>
						</li>
						
						<li>
							<div class="dropdown">
								<a class="dropdown-toggle btn" data-toggle="dropdown" href="#">
									Secteur d'activité
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu dropdown-menu-form" role="menu">
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										Audit/Conseil
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										Banque/Finance/Assurance
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										BTM/Immobilier 
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										Distribution/Consommation 
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										Energie/Environnement 
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										Industrie/Chimie 
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										Pharmacie/Santé 
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										Informatique/IT
									</label>
									</div>
									</li>
									<li>
										<div class="checkbox">
									<label>
										<input type="checkbox">
										Transport/Electronique
									</label>
									</div>
									</li>
								</ul>
							</div>
						</li>
						
						<li>
							<div class="dropdown">
								<a class="dropdown-toggle btn" data-toggle="dropdown" href="#">
									France
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu dropdown-menu-form" role="menu">
									<li>
										<div class="checkbox">
										<label>
											<input type="checkbox">
											Région parisienne 
										</label>
										</div>
									</li>
									<li>
										<div class="checkbox">
										<label>
											<input type="checkbox">
											Région nord-ouest 
										</label>
										</div>
									</li>
									<li>
										<div class="checkbox">
										<label>
											<input type="checkbox">
											Région nord-est 
										</label>
										</div>
									</li>
									<li>
										<div class="checkbox">
										<label>
											<input type="checkbox">
											Région sud-est
										</label>
										</div>
									</li>
									<li>
										<div class="checkbox">
										<label>
											<input type="checkbox">
											Région sud-ouest  
										</label>
										</div>
									</li>
								</ul>
							</div>
						</li>
						
						<li>
							<div class="dropdown">
								<a class="dropdown-toggle btn" data-toggle="dropdown" href="#">
									Etranger
									<b class="caret"></b>
								</a>
								<ul class="dropdown-menu dropdown-menu-form" role="menu">
									<li>
										<div class="checkbox">
										<label>
											<input type="checkbox">
											Afrique
										</label>
										</div>
									</li>
									<li>
										<div class="checkbox">
										<label>
											<input type="checkbox">
											Amérique du Nord
										</label>
										</div>
									</li>
									<li>
										<div class="checkbox">
										<label>
											<input type="checkbox">
											Amérique du Sud 
										</label>
										</div>
									</li>
									<li>
										<div class="checkbox">
										<label>
											<input type="checkbox">
											Asie
										</label>
										</div>
									</li>
									<li>
										<div class="checkbox">
										<label>
											<input type="checkbox">
											Europe
										</label>
										</div>
									</li>
									<li>
										<div class="checkbox">
										<label>
											<input type="checkbox">
											Océanie 
										</label>
										</div>
									</li>
								</ul>
							</div>
						</li>
						
						<li>
							<button type="submit" class="btn btn-primary">Chercher</button>
						</li>
					</ul>
					<br>
					<div class="row">
						<div class="col-md-6">
							<div class="input-group">
								<input type="text" class="form-control" size="30" value="" id="inputString" onkeyup="lookup(this.value);" onblur="fill();" placeholder="Une entreprise en particulier ?"/>
										<div class="suggestionsBox" id="suggestions" style="display: none;">
											<span class="glyphicon glyphicon-eject" id="upArrow"></span>
											<div class="suggestionList" id="autoSuggestionsList">
												&nbsp;
											</div>
										</div>
									<span class="input-group-btn">
									<button class="btn btn-default" type="button">Chercher</button>
									</span>
							</div>
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<input type="text" class="form-control" placeholder="Une offre en particulier ?">
									<span class="input-group-btn">
									<button class="btn btn-default" type="button">Chercher</button>
									</span>
							</div>
						</div>
						<br>
					</div>
				</nav>
	
<script>
$('.dropdown-menu').click(function(event){
     event.stopPropagation();
 });
 </script>
 
<!--<script>
var myData = [{id: 1, label: "Option 1" },
				{id: 2, label: "Option 2" },
				{id: 3, label: "Option 3" },
				{id: 4, label: "Option 4" },
				{id: 5, label: "Option 5" }];

$(".myDropdownCheckbox1").dropdownCheckbox({
  queryUrl: "/bootstrap-dropdown-checkbox/json/exemple_1.json",
  data: myData,
  title: "Critère 1",
  autosearch: true,
  btnClass: "btn btn-default",
  hideHeader: false
  
});

$(".myDropdownCheckbox2").dropdownCheckbox({
  data: myData,
  templateButton: '<a class="dropdown-checkbox-toggle" data-toggle="dropdown-checkbox" href="#">Critère 2<b class="caret"></b></button>'
});
</script>-->
				
				<br>

				<div class="list-group">
						
						<a href="offre.html" class="list-group-item">
							<div class="row">
								<div class="col-md-2">
								<img src="..." alt="..." class="img-thumbnail">
								</div>
								<div class="col-md-4">
								Entreprise
								</div>
								<div class="col-md-4">
								Titre de l'offre
								</div>
								<div class="col-md-2">
								Ville
								</div>
							</div>
						</a>
						
						<a href="offre.html" class="list-group-item">
							<div class="row">
								<div class="col-md-2">
								<img src="..." alt="..." class="img-thumbnail">
								</div>
								<div class="col-md-4">
								Entreprise
								</div>
								<div class="col-md-4">
								Titre de l'offre
								</div>
								<div class="col-md-2">
								Ville
								</div>
							</div>
						</a>

						<a href="offre.html" class="list-group-item">
							<div class="row">
								<div class="col-md-2">
								<img src="..." alt="..." class="img-thumbnail">
								</div>
								<div class="col-md-4">
								Entreprise
								</div>
								<div class="col-md-4">
								Titre de l'offre
								</div>
								<div class="col-md-2">
								Ville
								</div>
							</div>
						</a>

						<a href="offre.html" class="list-group-item">
							<div class="row">
								<div class="col-md-2">
								<img src="..." alt="..." class="img-thumbnail">
								</div>
								<div class="col-md-4">
								Entreprise
								</div>
								<div class="col-md-4">
								Titre de l'offre
								</div>
								<div class="col-md-2">
								Ville
								</div>
							</div>
						</a>

						<a href="offre.html" class="list-group-item">
							<div class="row">
								<div class="col-md-2">
								<img src="..." alt="..." class="img-thumbnail">
								</div>
								<div class="col-md-4">
								Entreprise
								</div>
								<div class="col-md-4">
								Titre de l'offre
								</div>
								<div class="col-md-2">
								Ville
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
