<?PHP
require_once("../../relate/include/membersite_config.php");

$fgmembersite->LogOut();

if(isset($_POST['submitted_login']))
{
   if($fgmembersite->Login())
   {
        $fgmembersite->RedirectToURL("etudiant/accueil_etudiant.php");
   }
}

if($fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("etudiant/accueil_etudiant.php");
    exit;
}

if(isset($_POST['submitted_register']))
{
   if($fgmembersite->RegisterUser())
   {
        $fgmembersite->RedirectToURL("inscription.html");
   }
}

?> 
  
  <xml version="1.0" encoding="UTF-8">
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
      "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml">
  
      <head>
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Relate</title>
          <link rel="STYLESHEET" type="text/CSS" href="../bootstrap3/css/bootstrap.css" media="screen" />
          <link rel="STYLESHEET" type="text/CSS" href="../bootstrap3/css/style-home.css" media="screen" />
		  <script type="text/javascript" src="http://codeorigin.jquery.com/jquery-2.0.3.js"></script>
		  <script type="text/javascript" src="../bootstrap3/js/bootstrap.js"></script>
		  <script type='text/javascript' src="../../relate/scripts/gen_validatorv31.js"></script>
		  <!--<script type="text/javascript" src="../../relate/scripts/pwdwidget.js"></script>-->   
          <style type="text/css">a:link{text-decoration:none}</style>
      </head>
      
<script language="javascript"  type="text/javascript">
// This script automatically complete the mail address of the user
var valName = "",valSurname = "",valEmailOfSchool = "";


function filtreType(val)
{
	if (val=="etudiant") document.getElementById("divEtudiant").style.display="block"
	else document.getElementById("divEtudiant").style.display = "none";
}

function changeName(val) {
  valName = val+'.';
  changeEmailField();
} 
function changeSurname(val) {
  valSurname = val;
  changeEmailField();
} 

function changeEcole(val)
{
  var h =  {"X":"@polytechnique.fr","ECP":"@ecp.fr","MdP":"@mdp.fr","Supelec":"@supelec.fr"};
  mailSchool = h[val];

  if(mailSchool != null) 
  {
    // document.getElementById("email").value = mailSchool;
    valEmailOfSchool = mailSchool;
  }
  changeEmailField();

}

function changeEmailField() {
  valEmail = valName + valSurname + valEmailOfSchool;
  document.getElementById("email").value = valEmail.toLowerCase();
}



</script>
  
      <body>
          
       <div class="container" id="top_page">
		
		<div class="container">
             <div class="page-header">
				<div class="row">
                  <div class="col-md-8">
                       <h1><a href="index.php">Relate</a></h1>
                  </div>
                  <div class="col-md-2">
                      <a type="button" class="btn" href="#inscription_modal" data-toggle="modal"><h4 id="style_a">S'inscrire</h4></a>
                      <div class="modal fade" id="inscription_modal">
                          <div class="modal-dialog" style="width:50%;" id="fg_membersite">
                              <a class="close" data-dismiss="modal" aria-hidden="true">&times;</a>
                              <div class="panel panel-default">
								  <form class="form-horizontal" id='register' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-chars>
									  <div class="panel-heading">
										  <h3>Inscription</h3>
									  </div>
									  <div class="panel-body">
										  <input type='hidden' name='submitted_register' id='submitted_register' value='1'/>
										  <input type='hidden'  class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>' />
										  <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
                                          <div class="form-group">
                                              <label for="input" class="col-sm-4 control-label">Vous êtes</label>
                                              <div class="col-sm-4">
                                                  <div class="radio">
													<input type='radio' name='type' id='etudiant' value='etudiant' maxlength="50" onchange='filtreType(this.value)'/>Etudiant
                                                  </div>
                                              </div>
                                              <div class="col-sm-4">
                                                  <div class="radio">
													<input type='radio' name='type' id='entreprise' value='entreprise' maxlength="50" onchange='filtreType(this.value)'/>Entreprise
                                                  </div>
                                              </div>
                                              <span id='register_type_errorloc' class='error'></span>
                                          </div>
											  <div class="form-group" id='divEtudiant' style='display: none;'>
												<label for='mainSchool' class="col-sm-4 control-label">Votre école</label><br/>
												

                        <div class="col-sm-8">
													<select class="form-control" name="mainSchool" id="mainSchool" onchange='changeEcole(this.options[this.selectedIndex].value)' style="position: relative; top: -18px; height: 34px;">
														<option value="">Choisissez votre école</option>
														<option value="X">Ecole Polytechnique</option>
														<option value="ECP">Ecole Centrale Paris </option>
														<option value="MdP">Mines de Paris</option>
														<option value="Supelec">Supélec</option>
													</select>
												</div>
											  </div>
                                              <div class="form-group">
                                                  <label for="name" class="col-sm-4 control-label">Votre prénom</label>
                                                  <div class="col-sm-8">
                                                    <input type='text' class="form-control" name='name' id='name' onkeyup='changeName(this.value)' value="" maxlength="50" placeholder="Prénom"/>
													<span id='register_name_errorloc' class='error'></span>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="surname" class="col-sm-4 control-label">Votre nom</label>
                                                  <div class="col-sm-8">
                                                    <input type='text' class="form-control" name='surname' id='surname' onkeyup='changeSurname(this.value)' value="" maxlength="50" placeholder="Nom"/>
													<span id='register_surname_errorloc' class='error'></span>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="email" class="col-sm-4 control-label">Votre adresse mail</label>
                                                  <div class="col-sm-8">
                                                    <input type='text' class="form-control" name='email' id='email'  value="" maxlength="50" placeholder="Adresse mail"/>
													<span id='register_email_errorloc' class='error'></span>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="username" class="col-sm-4 control-label">Votre nom d'utilisateur</label>
                                                  <div class="col-sm-8">
                                                    <input type='text' class="form-control" name='username' id='username' value="" maxlength="50" placeholder="Nom d'utilisateur"/>
													<span id='register_username_errorloc' class='error'></span>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for="password" class="col-sm-4 control-label" value="">Votre mot de passe</label>
                                                  <div class="col-sm-8">
                                                    <!--<div class='pwdwidgetdiv' id='thepwddiv' ></div>
													<noscript>-->
													<input type='password' class="form-control" name='password' id='password' maxlength="50" placeholder="Mot de passe"/>
													<!--</noscript>-->    
													<div id='register_password_errorloc' class='error' style='clear:both'></div>
                                                  </div>
                                              </div>
                                  </div>
                                  <div class="panel-footer">
                                      <a href="index.php" class="btn" data-dismiss="modal">Annuler</a>
                                      <!--<a href="inscription.html" class="btn btn-primary">S'inscrire</a>-->
                                      <input type='submit' class="btn btn-primary" name='Valider' value="S'inscrire" />
                                  </div>
                              </div>
                              </form>
                          </div>
                      </div>
                  </div>
                  <script type='text/javascript'>
// <![CDATA[
    //var pwdwidget = new PasswordWidget('thepwddiv','password');
    //pwdwidget.MakePWDWidget();
    
    var frmvalidator  = new Validator("register");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();
    frmvalidator.addValidation("name","req","Veuillez indiquer votre prénom");
    
    frmvalidator.addValidation("surname","req","Veuillez indiquer votre nom");

    frmvalidator.addValidation("email","req","Veuillez indiquer votre adresse mail");

    frmvalidator.addValidation("email","email","Veuillez indiquer une adresse mail valide");

    frmvalidator.addValidation("username","req","Veuillez indiquer votre nom d'utilisateur");
    
    frmvalidator.addValidation("password","req","Veuillez indiquer votre mot de passe");

    frmvalidator.addValidation("type","req","Veuillez nous indiquer si vous êtes un étudiant ou une entreprise");
// ]]>
</script>               
                  <div class="col-md-2">
                      <a  type="button" class="btn" href="#connexion_modal" data-toggle="modal"><h4 id="style_a">Se connecter</h4></a>
                      <div class="modal fade" id="connexion_modal">
                          <div class="modal-dialog" style="width:50%;" id="fg_membersite">
                              <a class="close" data-dismiss="modal">&times;</a>
                              <form class="form-horizontal" id='login' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
								  <div class="panel panel-default">
									  <div class="panel-heading">
										  <h3>Connexion</h3>
									  </div>
									  <div class="panel-body">
										  <input type='hidden' name='submitted_login' id='submitted_login' value='1'/>
										  <!-- <div class='short_explanation'>* champs requis</div> -->
										  <div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
                                          <div class="form-group">
                                              <label for='type' class="col-sm-4 control-label">Vous êtes</label>
                                              <div class="col-sm-4">
                                                  <div class="radio">
                                                        <input type='radio' name='type' id='etudiant' value='etudiant' maxlength="50" <?php if($fgmembersite->SafeDisplay('type')=='etudiant') {echo 'checked = "checked"';};?>/>Etudiant
                                                  </div>
                                              </div>
                                              <div class="col-sm-4">
                                                  <div class="radio">
                                                        <input type='radio' name='type' id='entreprise' value='entreprise' maxlength="50" <?php if($fgmembersite->SafeDisplay('type')=='entreprise') {echo 'checked = "checked"';};?>/>Entreprise
                                                  </div>
                                                   <span id='login_type_errorloc' class='error'></span>
                                              </div>
                                          </div>
                                              <div class="form-group">
                                                  <label for='username' class="col-sm-4 control-label">Votre nom d'utilisateur</label>
                                                  <div class="col-sm-8">
                                                    <input type='text' class="form-control" name='username' id='username' value='<?php echo $fgmembersite->SafeDisplay('username') ?>' maxlength="50" placeholder="Nom d'utilisateur" />
													<span id='login_username_errorloc' class='error'></span>
                                                  </div>
                                              </div>
                                              <div class="form-group">
                                                  <label for='password' class="col-sm-4 control-label">Votre mot de passe</label>
                                                  <div class="col-sm-8">
                                                    <input type='password' class="form-control" name='password' id='password' maxlength="50" placeholder="Mot de passe" />
													<span id='login_password_errorloc' class='error'></span>
                                                  </div>
                                              </div>
											  <!--<div class="alert alert-warning" id="check_alert">Veuillez remplir tous les champs</div>-->
                                  </div>
                                  <div class="panel-footer">
                                      <a href="index.php" class="btn" data-dismiss="modal">Annuler</a>
                                      <!--<a class="btn btn-primary" id="link">Se connecter</a>-->
                                      <input type='submit' class="btn btn-primary" name='Valider' value='Se connecter'/>
								  </div>
								</div>
                          </form>
                      </div>
                  </div>
<!--<script>
var chk_etudiant = document.getElementById("check_etudiant");
var chk_entreprise = document.getElementById("check_entreprise");
var link = document.getElementById("link");
var alerte = document.getElementById("check_alert");
alerte.style.display = "none";
var scrt_var = "#";

chk_etudiant.onclick = function(){
	chk_etudiant.checked = true;
	chk_entreprise.checked = false;
}

chk_entreprise.onclick = function(){
	chk_entreprise.checked = true;
	chk_etudiant.checked = false;
}

link.onclick = function(){
	if((chk_etudiant.checked) && !(chk_entreprise.checked)){  
	var scrt_var = "ETUDIANT/accueil_etudiant.html";	
    link.setAttribute("href",scrt_var);
    }
	if((chk_entreprise.checked) && !(chk_etudiant.checked)){
    var scrt_var = "ENTREPRISE/accueil_entreprise.html";	
    link.setAttribute("href",scrt_var);
    }
	if(!(chk_entreprise.checked) && !(chk_etudiant.checked)){
    var scrt_var = "#";	
    link.setAttribute("href",scrt_var);
	alerte.style.display = "inline";
    }
}

</script>
-->
<script type='text/javascript'>
// <![CDATA[

    var frmvalidator  = new Validator("login");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();

    frmvalidator.addValidation("username","req","Veuillez fournir un nom d'utilisateur");
    
    frmvalidator.addValidation("password","req","Veuillez fournir un mot de passe");

// ]]>
</script>
                              
              </div>
			</div>
          </div>
        </div>

			<div class="container">
                          <div class="jumbotron">
                              <h1>Relate,</h1>
                              <h3>pour un contact privilégié entre entreprises et étudiants des meilleures grandes écoles</h3>
                              <div class="row">
                                  <div class="col-md-5">
                                          <div class="panel panel-default">
                                              <div class="panel-body">
                                                  <h4 id="interligne">
                                                  <span class="glyphicon glyphicon-user"></span>
                                                  Créez votre profil étudiant visible par les entreprises
                                                  </h4>
                                              </div>
                                          </div>
                                      
                                          <div class="panel panel-default">
                                              <div class="panel-body">
                                                  <h4 id="interligne">
                                                  <span class="glyphicon glyphicon-search"></span>
                                                  Consultez les offres de stages correspondant à vos critères
                                                  </h4>
                                              </div>
                                          </div>
                                      
                                          <div class="panel panel-default">
                                              <div class="panel-body">
                                                  <h4 id="interligne">
                                                  <span class="glyphicon glyphicon-send"></span>
                                                  Postulez directement aux offres depuis Relate
                                                  </h4>
                                              </div>
                                          </div>
                                      <a href="#" class="btn btn-primary btn-lg" role="button">Découvrez Relate</a>
                                      
                                  </div>
                                  <div class="col-md-7">
										<div class="panel panel-default">
                                              <div class="panel-body">
                                                  <img src=../theme/handshake.jpg class="img-responsive" alt="Responsive image" style="height:326px;">
                                              </div>
                                          </div>
									  <a href="#inscription_modal" class="btn btn-success btn-lg" data-toggle="modal">Rejoignez gratuitement Relate</a>
                                  </div>
                              </div>
                          </div>
  
                          <div class="col-md-6">
                              <div class="panel panel-default">
                                  <div class="panel-heading">
                                      <h3 id="text_center">Lisez des témoignages sur Relate</h3>
                                  </div>
                                  <div class="panel-body">
                                      <!--<div class="col-md-6">-->
                                          <div class="panel panel-default"><img src="..." alt="..." class="img-thumbnail">BlaBla 1</div>
                                          <div class="panel panel-default"><img src="..." alt="..." class="img-thumbnail">BlaBla 2</div>
                                          <div class="panel panel-default"><img src="..." alt="..." class="img-thumbnail">BlaBla 3</div>
                                      <!--</div>
                                      <div class="col-md-6">
                                          <h4>Quelques entreprises partenaires</h4>
                                          <a href=#><div class="panel panel-default"><img src="..." alt="..." class="img-thumbnail">Entreprise 1</div></a>
                                          <a href=#><div class="panel panel-default"><img src="..." alt="..." class="img-thumbnail">Entreprise 2</div></a>
                                          <a href=#><div class="panel panel-default"><img src="..." alt="..." class="img-thumbnail">Entreprise 3</div></a>
                                      </div>-->
                                  </div>
                                  <div class="panel-footer" id="text_center">
                                          <a href="#">
										  <span class="glyphicon glyphicon-play"></span>
										  Voir tous les témoignages</a>
                                          <!--<a href="evenements.html">Voir toutes les entreprises</a>-->
                                  </div>
                                  
                              </div>
                          </div>
                          
                          <div class="col-md-6">
                              <div class="panel panel-default">
                                  <div class="panel-heading">
                                      <h3 id="text_center">Découvrez les entreprises partenaires</h3>
                                      
                                  </div>
                                  <div class="panel-body" id="text_center">
                                      <div class="col-md-4">
                                          <a href=#><img src="..." alt="..." class="img-thumbnail"></a>
                                          <br>
                                          <a href=#><img src="..." alt="..." class="img-thumbnail"></a>
                                          <br>
                                          <a href=#><img src="..." alt="..." class="img-thumbnail"></a>
										  <br>
                                          <a href=#><img src="..." alt="..." class="img-thumbnail"></a>
                                      </div>
                                      <div class="col-md-4">
                                          <a href=#><img src="..." alt="..." class="img-thumbnail"></a>
                                          <br>
                                          <a href=#><img src="..." alt="..." class="img-thumbnail"></a>
                                          <br>
                                          <a href=#><img src="..." alt="..." class="img-thumbnail"></a>
										  <br>
                                          <a href=#><img src="..." alt="..." class="img-thumbnail"></a>
                                      </div>
                                      <div class="col-md-4">
                                          <a href=#><img src="..." alt="..." class="img-thumbnail"></a>
                                          <br>
                                          <a href=#><img src="..." alt="..." class="img-thumbnail"></a>
                                          <br>
                                          <a href=#><img src="..." alt="..." class="img-thumbnail"></a>
										  <br>
                                          <a href=#><img src="..." alt="..." class="img-thumbnail"></a>
                                      </div>
                                  </div>
                                  <div class="panel-footer" id="text_center">
                                          <a href="entreprises.html">
										  <span class="glyphicon glyphicon-play"></span>
										  Voir toutes les entreprises</a>
                                  </div>
                                  
                              </div>
                          </div>
                  </div>
              
              <nav class="nav navbar-default">
				
					<ul  class="nav nav-justified">
						<li><a href="#">Relate</a></li>
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
