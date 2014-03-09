<?PHP
require_once("../include/membersite_config.php");

if(isset($_POST['submitted']))
{
	if($fgmembersite->Login())
	{
		$fgmembersite->RedirectToURL("../login-home.php");
	}
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="fr">
<head>
<meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>

<title>Edition du paramètre du compte</title>

    <link rel="STYLESHEET" type="text/css" href="../style/fg_membersite.css" />
    <link rel="STYLESHEET" type="text/css" href="../style/pwdwidget.css" />
  
   <link rel="STYLESHEET" type="text/css" href="../popup/changeprofiletest.css" />
   <script type="text/javascript" src="../popup/jquery-1.8.3.js"></script>

   
</head>
<body>

<!-- Form Code Start -->
<div id='fg_membersite'>
<legend>Connexion</legend>

<input type='hidden' name='submitted' value='1'/>

				<div class='container'>
					<a href="#?w=500" rel="_formation" class="poplight"> Supélec </a>
					<div class="popup_block" id="_formation">
						<div id='fg_membersite'>
							<form id='login'
								action='<?php echo $fgmembersite->GetSelfScript(); ?>'
								method='post' accept-charset='UTF-8'>
								<fieldset>
									<legend>Connexion</legend>

									<input type='hidden' name='submitted' id='submitted' value='1' />

									<div class='short_explanation'>* champs requis</div>

									<div>
										<span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?>
										</span>
									</div>
									<div class='container'>
										<label for='type'>Type*:</label><br /> <input type='radio'
											name='type' id='etudiant' value='etudiant' maxlength="50"
											<?php if($fgmembersite->SafeDisplay('type')=='etudiant') {echo 'checked = "checked"';};?> />Etudiant
										<input type='radio' name='type' id='entreprise'
											value='entreprise' maxlength="50"
											<?php if($fgmembersite->SafeDisplay('type')=='entreprise') {echo 'checked = "checked"';};?> />Entreprise<br />
										<span id='register_type_errorloc' class='error'></span>
									</div>
									<div class='container'>
										<label for='username'>Nom d'utilisateur*:</label><br /> <input
											type='text' name='username' id='username'
											value='<?php echo $fgmembersite->SafeDisplay('username') ?>'
											maxlength="50" /><br /> <span id='login_username_errorloc'
											class='error'></span>
									</div>
									<div class='container'>
										<label for='password'>Mot de passe*:</label><br /> <input
											type='password' name='password' id='password' maxlength="50" /><br />
										<span id='login_password_errorloc' class='error'></span>
									</div>

									<div class='container'>
										<input type='submit' name='Valider' value='Valider' />
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
		$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="images/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');
		
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

</body>
</html>