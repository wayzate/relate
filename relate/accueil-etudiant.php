<?PHP
require_once("include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}
?>

<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Accueil</title>
<link href="css/style.css" rel="stylesheet" type="text/CSS" media="screen" />
</head>
<body>
	<div id="header">
		<div id="top">
			<div id="item">
				<div id="logo"></div>
				</div>
			</div>
		<div id="topombre"></div>
		
		
	</div>
	
	<div id="menu">
	
		<ul>
			<li><a href="http://www.google.fr" class="profil"> Profil </a></li>
			<li><a href="#" class="offres"> Offres </a></li>
			<li><a href="#" class="entreprises"> Entreprises </a></li>
			<li><a href="#" class="etudiants"> Etudiants </a></li>
			<li><a href="#" class="candidatures"> Candidatures </a></li>
			<li><a href="#" class="messages"> Messages </a></li>
		</ul>
	</div>
		
	<div id="conteneur">
    	<div id="textetop">
			<div id="bienvenue">Bienvenue <?php echo $fgmembersite->UserFullName(); ?></div>
        	<div id="deconnexion"><a href='logout.php'> Se déconnecter </a></div>
        </div>
        
		<div id="bloc">
		<ul>
			<li><a href="#"> METTEZ A JOUR<br>VOTRE PROFIL</br></a>
										<p><div id="petit"> <p>
										Editez votre profil étudiant<br> et diffusez votre CV</br>pour être contacté</br>par un recruteur
										</p></div></p></li>
			<li><a href="#"> RECHERCHEZ<br>UNE OFFRE</br></a>
										<p><div id="petit"> <p>
										Recherchez et postulez<br>aux offres de stage</br>ou offres d'emploi</br>correspondant à vos critères
										</p></div></p></li>
			<li><a href="#"> DECOUVREZ<br>LES ENTREPRISES</br></a>
										<p><div id="petit"> <p>
										Consultez les fiches<br>de présentation, les offres</br>et les évènements</br>des entreprises partenaires
										</p></div></p></li>
		</ul>
		</div>
		
		<div id="bloc4">
		
		<div id="topbloc" class="bloc4"><div id="zonetitre">LES DERNIERES PUBLICATIONS<br>DES ENTREPRISES</br></div></div>
		
		<div id="contenu" class="bloc4">
			<TABLE>
			
				<TR> 
				<TH class="dernieresoffres">LES DERNIERES OFFRES</TH> 
				<TH class="evenementsavenir">LES EVENEMENTS A VENIR</TH>
				</TR> 
				
				<TR> 
				
				<TH class="titreoe">
					<TABLE id="interne">
					<TR> 
					<TH class="gauche">
						<div id="imagelogo"><a class="offre1" href="#"></a></div>
						<div id="boutonvoir"><a href="#"> Voir l'offre</a></div>
					</TH> 
					<TH>
						Titre offre 1<br><a class="infosoe">Type de contrat :</br>Ville :</br></a>
					</TH>  
					</TR>
					</TABLE>
					
				</TH>
				
				<TH class="titreoe">
					<TABLE id="interne"> 
					<TR> 
					<TH class="gauche">
						<div id="imagelogo"><a class="evenement1" href="#"></a></div>
						<div id="boutonvoir"><a href="#"> Plus d'infos</a></div>
					</TH> 
					<TH>
						Titre evenement 1<br><a class="infosoe">Date :</br>Ville :</br></a>
					</TH>  
					</TR>
					</TABLE>
				</TH>
				
				</TR>
				
				<TR> 
				
				<TH class="titreoe">
					<TABLE id="interne"> 
					<TR> 
					<TH class="gauche">
						<div id="imagelogo"><a class="offre2" href="#"></a></div>
						<div id="boutonvoir"><a href="#"> Voir l'offre</a></div>
					</TH> 
					<TH>
						Titre offre 2<br><a class="infosoe">Type de contrat :</br>Ville :</br></a>
					</TH>  
					</TR>
					</TABLE>
				</TH>
				
				<TH class="titreoe">
					<TABLE id="interne"> 
					<TR> 
					<TH class="gauche">
						<div id="imagelogo"><a class="evenement2" href="#"></a></div>
						<div id="boutonvoir"><a href="#"> Plus d'infos</a></div>
					</TH> 
					<TH>
						Titre evenement 2<br><a class="infosoe">Date :</br>Ville :</br></a>
					</TH> 
					</TR>
					</TABLE>
				</TH> 
				
				</TR>
				
				<TR> 
				
				<TH class="titreoe">
					<TABLE id="interne"> 
					<TR> 
					<TH class="gauche">
						<div id="imagelogo"><a class="offre3" href="#"></a></div>
						<div id="boutonvoir"><a href="#"> Voir l'offre</a></div>
					</TH> 
					<TH>
						Titre offre 3<br><a class="infosoe">Type de contrat :</br>Ville :</br></a>
					</TH>  
					</TR>
					</TABLE>
				</TH>
				
				<TH class="titreoe">
					<TABLE id="interne"> 
					<TR> 
					<TH class="gauche">
						<div id="imagelogo"><a class="evenement3" href="#"></a></div>
						<div id="boutonvoir"><a href="#"> Plus d'infos</a></div>
					</TH> 
					<TH>
						Titre evenement 3<br><a class="infosoe">Date :</br>Ville :</br></a>
					</TH>  
					</TR>
					</TABLE>
				</TH> 
				
				</TR>
				
				<TR> 
				<TH class="voirtous">Pour voir toutes les offres, cliquez <a href="#">ici</a></TH> 
				<TH class="voirtous">Pour voir touts les évènements, cliquez <a href="#">ici</a></TH>
				</TR>
				
			</TABLE> 
			
		</div>
		
		<div id="basbloc" class="bloc4"></div>
		
		</div>
		
		<div id="bloc5">
		
		<div id="topbloc" class="bloc5"><div id="zonetitre">SUGGESTION<br>D'ENTREPRISES</br></div></div>
		
		<div id="contenu" class="bloc5">
			<TABLE class="bloc5">
				<TR> 
				<TH class="titreoe">
					<TABLE id="interne" class="bloc5">
					<TR> 
					<TH class="gauche">
						<div id="imagelogo"><a class="offre1" href="#"></a></div>
					</TH> 
					<TH>
						Raison sociale 1<br><a class="infosoe">Secteur d'activité</br></a>
						<div id="boutonvoir"><a href="#"> Voir la fiche</a></div>
					</TH>
					</TR>
					</TABLE>
				</TH>
				</TR>
				
				<TR>				
				<TH class="titreoe">
					<TABLE id="interne" class="bloc5">
					<TR> 
					<TH class="gauche">
						<div id="imagelogo"><a class="offre2" href="#"></a></div>
					</TH> 
					<TH>
						Raison sociale 2<br><a class="infosoe">Secteur d'activité</br></a>
						<div id="boutonvoir"><a href="#"> Voir la fiche</a></div>
					</TH>
					</TR>
					</TABLE>
				</TH>
				</TR>
				
				<TR>
				<TH class="titreoe">
					<TABLE id="interne" class="bloc5">
					<TR> 
					<TH class="gauche">
						<div id="imagelogo"><a class="offre3" href="#"></a></div>
					</TH> 
					<TH>
						Raison sociale 3<br><a class="infosoe">Secteur d'activité</br></a>
						<div id="boutonvoir"><a href="#"> Voir la fiche</a></div>
					</TH>
					</TR>
					</TABLE>
				</TH>
				</TR>
				
				<TR> 
				<TH class="voirtous">Pour voir toutes les entreprises, cliquez <a href="#">ici</a></TH> 
				</TR>
				
			</TABLE> 
			
		</div>
		
		<div id="basbloc" class="bloc5"></div>
		
		</div>
		
	</div>
	
</body>
</html>
