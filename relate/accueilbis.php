<?PHP
require_once("./include/membersite_config.php");

if(isset($_POST['submitted']))
{
	if($fgmembersite->Login())
	{
		$fgmembersite->RedirectToURL("accueil_etudiant.php");
	}
}

if($fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login-home.php");
    exit;
}

if(isset($_POST['submitted']))
{
   if($fgmembersite->RegisterUser())
   {
        $fgmembersite->RedirectToURL("thank-you.html");
   }
}
?>

<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>Relate</title>

	<link rel="STYLESHEET" type="text/CSS" href="css/styleaccueilbis.css" media="screen" />
    <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
    <link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css" />
    <link rel="STYLESHEET" type="text/css" href="popup/changeprofiletest.css" />
    <script type="text/javascript" src="popup/jquery-1.8.3.js"></script>
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
    <script src="scripts/pwdwidget.js" type="text/javascript"></script> 
   
</head>

<body>

<div id="conteneur">

<!-- ************************ HEADER ************************ -->

<div id="header">

	<div id="top">
		<div id="item">
			<div id="logo"></div>
		</div>
	</div>
    
	<div id="topombre"></div>
    
</div>
		
<!-- ************************ DEBUT CONTENU ************************ -->
	<div id="contenu">
    
    
<!-- ************************ DEBUT CONTENU TOP ************************ -->
	<div id="contenutop">
    

<!-- ************************ CONNEXION ************************ -->
          
    <div id='fg_membersite'>
       
<input type='hidden' name='submitted' value='1'/>

				<div class='container'>
					<a href="#?w=500" rel="_connexion" class="poplight" id="texttop"> Se connecter </a>
					<div class="popup_block" id="_connexion">
						<div id='fg_membersite'>
							<form id='login'
								action='<?php echo $fgmembersite->GetSelfScript(); ?>'
								method='post' accept-charset='UTF-8'>
								<fieldset>
									<legend>Connexion</legend>

									<input type='hidden' name='submitted' id='submitted' value='1' />

									<div>
										<span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?>
										</span>
									</div>
									<div class='type'>
										<input type='radio'
											name='type' id='etudiant' value='etudiant' maxlength="50"
											<?php if($fgmembersite->SafeDisplay('type')=='etudiant') {echo 'checked = "checked"';};?> />Etudiant
										<input type='radio' name='type' id='entreprise'
											value='entreprise' maxlength="50"
											<?php if($fgmembersite->SafeDisplay('type')=='entreprise') {echo 'checked = "checked"';};?> />Entreprise<br />
										<span id='register_type_errorloc' class='error'></span>
									</div>
									<div class='container'>
										<label for='username'>Nom d'utilisateur</label><br /> <input
											type='text' name='username' id='username'
											value='<?php echo $fgmembersite->SafeDisplay('username') ?>'
											maxlength="50" /><br /> <span id='login_username_errorloc'
											class='error'></span>
									</div>
									<div class='container'>
										<label for='password'>Mot de passe</label><br /> <input
											type='password' name='password' id='password' maxlength="50" /><br />
										<span id='login_password_errorloc' class='error'></span>
									</div>
									<div class='container'>
										<input type='submit' name='Valider' value='Valider' class='boutonvalider'/>
									</div>
									<div class='short_explanation'>
										<a href='reset-pwd-req.php'>Mot de passe oublié ?</a>
									</div>
								</fieldset>
							</form>
							<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

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
    

<!-- ************************ INSCRIPTION ************************ -->
    
    <div id='fg_membersite'>
       
<input type='hidden' name='submitted' value='1'/>

				<div class='container'>
					<a href="#?w=500" rel="_inscription" class="poplight" id="texttop"> S'inscrire </a>
					<div class="popup_block" id="_inscription">
<div id='fg_membersite'>
<form id='register' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Inscription</legend>

<input type='hidden' name='submitted' id='submitted' value='1'/>

<input type='text'  class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>' />

<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
<div class='type'>
    <input type='radio' name='type' id='etudiant' value='etudiant' maxlength="50" <?php if($fgmembersite->SafeDisplay('type')=='etudiant') {echo 'checked = "checked"';};?> />Etudiant
    <input type='radio' name='type' id='entreprise' value='entreprise' maxlength="50" <?php if($fgmembersite->SafeDisplay('type')=='entreprise') {echo 'checked = "checked"';};?>/>Entreprise<br />
    <span id='register_type_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='name' >Votre prénom </label><br/>
    <input type='text' name='name' id='name' value='<?php echo $fgmembersite->SafeDisplay('name') ?>' maxlength="50" /><br/>
    <span id='register_name_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='email' >Votre adresse mail</label><br/>
    <input type='text' name='email' id='email' value='<?php echo $fgmembersite->SafeDisplay('email') ?>' maxlength="50" /><br/>
    <span id='register_email_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='username' >Votre nom d'utilisateur</label><br/>
    <input type='text' name='username' id='username' value='<?php echo $fgmembersite->SafeDisplay('username') ?>' maxlength="50" /><br/>
    <span id='register_username_errorloc' class='error'></span>
</div>
<div class='container' style='height:80px;'>
    <label for='password' >Votre mot de passe</label><br/>
    <div class='pwdwidgetdiv' id='thepwddiv' ></div>
    <noscript>
    <input type='password' name='password' id='password' maxlength="50" />
    </noscript>    
    <div id='register_password_errorloc' class='error' style='clear:both'></div>
</div>

<div class='container'>
    <input type='submit' name='Valider' value='Valider' class='boutonvalider'/>
</div>
								</fieldset>
							</form>
						</div>
					</div>

	</div>
    
</div>

<!-- ************************ FIN CONTENU TOP ************************ -->
	</div>
    

<!-- ************************ DEBUT BLOCS ************************ -->

<div id="blocs">

<!-- ************************ BLOC 1 ************************ -->

<div id="bloc">

	<div id="hautbloc"></div>
    
    <div id="contenubloc">
    	<a class="titre"> Relate, pour un contact privilégié entre entreprises et étudiants des meilleures grandes écoles françaises</a>
        <img src="theme/handshake.jpg">
    </div>
    
    <div id="basbloc"></div>
    
</div>

<!-- ************************ BLOC 2 ************************ -->

<div id="bloc">

	<div id="hautbloc"></div>
    
    <div id="contenubloc">
    	<a class="titre"> Témoignages et Partenaires </a>
        </br></br></br></br></br>
    </div>
    
    <div id="basbloc"></div>
    
</div>


<!-- ************************ FIN BLOCS ************************ -->

</div>


<!-- ************************ FIN CONTENU ************************ -->

</div>


<script type='text/javascript'>
// <![CDATA[
    var pwdwidget = new PasswordWidget('thepwddiv','password');
    pwdwidget.MakePWDWidget();
    
    var frmvalidator  = new Validator("register");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();
    frmvalidator.addValidation("name","req","Veuillez indiquer votre nom");

    frmvalidator.addValidation("email","req","Veuillez indiquer votre adresse mail");

    frmvalidator.addValidation("email","email","Veuillez indiquer une adresse mail valide");

    frmvalidator.addValidation("username","req","Veuillez indiquer votre nom d'utilisateur");
    
    frmvalidator.addValidation("password","req","Veuillez indiquer votre mot de passe");

    frmvalidator.addValidation("type","req","Veuillez nous indiquer si vous êtes un étudiant ou une entreprise");
// ]]>
</script>


<script type="text/javascript">
$(document).ready(function(){
						   		   
	//When you click on a link with class of poplight and the href starts with a # 
	$('a.poplight[href^=#]').click(function() {
		var popID = $(this).attr('rel'); //Get Popup Name
		var popURL = $(this).attr('href'); //Get Popup href to define size
				
		//Pull Query & Variables from href URL
		var query= popURL.split('?');
		var dim= query[1].split('&');
		var popWidth = dim[0].split('=')[1]; //Gets the first query string value

		//Fade in the Popup and add close button
		$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="theme/boutonquittersimple.png" class="btn_close" title="Close Window" alt="Close" /></a>');
		
		//Define margin for center alignment (vertical + horizontal) - we add 80 to the height/width to accomodate for the padding + border width defined in the css
		var popMargTop = ($('#' + popID).height() + 80) / 2;
		var popMargLeft = ($('#' + popID).width() + 80) / 2;
		
		//Apply Margin to Popup
		$('#' + popID).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		//Fade in Background
		$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer 
		
		return false;
	});
	
	
	//Close Popups and Fade Layer
	$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
	  	$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();  
	}); //fade them both out
		
		return false;
	});

	
});

</script>
    
    
<!-- ************************ FOOTER ************************ -->

<div id="footer">

	<div id="footombre"></div>
    
	<div id="foot">
    	<div id="textefoot">
        	<li><a class="relate"> Relate ©  2013</a></li>
			<li><a href="#"> Contactez-nous </a></li>
			<li><a href='conditions_utilisation.html'> Conditions d'utilisation </a></li>
			<li><a href='mentions_legales.html'> Mentions légales </a></li>
        </div>
	</div>

</div>


<!-- ************************ FIN CONTENEUR ************************ -->

</div>
	
    
</body>
</html>
