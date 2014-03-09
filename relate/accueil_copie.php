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
<link href="css/style_copie.css" rel="stylesheet" type="text/CSS" media="screen" />
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
			<li><a href='profil_etudiant.php' class="profil"> Profil </a></li>
			<li><a href="#" class="offres"> Offres </a></li>
			<li><a href="#" class="entreprises"> Entreprises </a></li>
			<li><a href="#" class="etudiants"> Etudiants </a></li>
			<li><a href="#" class="candidatures"> Candidatures </a></li>
			<li><a href='messages.php' class="messages"> Messages </a></li>
		</ul>
	</div>
		
	<div id="conteneur">
    	<div id="textetop">
			<div id="bienvenue">Bienvenue <?php echo $fgmembersite->UserFullName(); ?></div>
        	<div id="deconnexion"><a href='logout.php'> Se déconnecter </a></div>
        </div>
        
		<div id="bloc">
		<ul>
			<li><a href="#">
            				<div class="img1"></div>
                            			METTEZ A JOUR VOTRE PROFIL
										<p><div id="petit"> <p>
										Editez votre profil étudiant et diffusez votre CV pour être contacté par un recruteur
										</p></div></p></a></li>
			<li><a href="#"><div class="img2"></div>
            							RECHERCHEZ UNE OFFRE
										<p><div id="petit"> <p>
										Recherchez et postulez aux offres de stage ou offres d'emploi correspondant à vos critères
										</p></div></p></a></li>
			<li><a href="#"><div class="img3"></div>
            							DECOUVREZ LES ENTREPRISES
										<p><div id="petit"> <p>
										Consultez les fiches de présentation, les offres et les évènements des entreprises partenaires
										</p></div></p></a></li>
		</ul>
		</div>
		
        
		<div id="blocbas" class="bloc4">
		
		<div class="zonetitre">LES DERNIERES PUBLICATIONS DES ENTREPRISES</div>
		
		<div id="contenu">
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
		
		</div>
		
		<div id="blocbas" class="bloc5">
		
		<div class="zonetitre">SUGGESTION D'ENTREPRISES</div>
		
		<div id="contenu">
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
		
	</div>
    
    </div>
	
</body>
</html>
