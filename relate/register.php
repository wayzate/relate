<?PHP
require_once("./include/membersite_config.php");

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
<title>S'inscrire</title>
    <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
    <link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css" />
    <script src="scripts/pwdwidget.js" type="text/javascript"></script>      
</head>
<script language="javascript"  type="text/javascript">

function filtre_type(value)
{
	if (value=="etudiant") document.getElementById("divEtudiant").style.display="block"
	else document.getElementById("divEtudiant").style.display = "none";
}

function ajoutEmail(value)
{
	if( value=="X") {
		document.getElementById("email").value = "@polytechnique.fr"
	}
	else {
		if( value=="ECP") {
			document.getElementById("email").value = "@ecp.fr"
		}
		else {
			if( value=="MdP") {
				document.getElementById("email").value = "@mdp.fr"
			}
		}
	}
}


</script>

<body>

<!-- Form Code Start -->
<div id='fg_membersite'>
<form id='register' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Inscription</legend>

<input type='hidden' name='submitted' id='submitted' value='1'/>

<input type='text'  class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>' />

<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
<div class='container'>
    <label for='type' >Type:</label><br/>
    

	<input type='radio' name='type' id='etudiant' value='etudiant' maxlength="50" onchange='filtre_type(this.value)'
		<?php if($fgmembersite->SafeDisplay('type')=='etudiant') {echo 'checked = "checked"';};?> />Etudiant
	<input type='radio' name='type' id='entreprise' value='entreprise' maxlength="50" onchange='filtre_type(this.value)'
		<?php if($fgmembersite->SafeDisplay('type')=='entreprise') {echo 'checked = "checked"';};?> />Entreprise<br />


    
 <!--    <select name='type' onchange='filtre_type(this.options[this.selectedIndex].value)'>
    	<option value='etudiant'  <?php /*if($fgmembersite->SafeDisplay('type')=='etudiant') {echo 'selected = "selected"';};*/?> >&Eacutetudiant</option>
    	<option value='entreprise' <?php /*if($fgmembersite->SafeDisplay('type')=='entreprise') {echo 'selected = "selected"';};*/?> >Entreprise</option>
    </select> -->
    <span id='register_type_errorloc' class='error'></span>
</div>

<div class='container' id='divEtudiant' style='display: none;'>
	<label for='type' >&Eacutecole:</label><br/>
	<select name="mainSchool" onchange='ajoutEmail(this.options[this.selectedIndex].value)'>
		<option value=''>Choisissez votre école</option>
		<option value="X">Ecole Polytechnique</option>
		<option value="ECP">Ecole Centrale Paris </option>
		<option value="MdP">Mines de Paris</option>
	</select>
</div>
<div class='container'>
    <label for='name' >Votre prénom: </label><br/>
    <input type='text' name='name' id='name' value='<?php echo $fgmembersite->SafeDisplay('name') ?>' maxlength="50" /><br/>
    <span id='register_name_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='surname' >Votre nom: </label><br/>
    <input type='text' name='surname' id='surname' value='<?php echo $fgmembersite->SafeDisplay('surname') ?>' maxlength="50" /><br/>
    <span id='register_name_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='email' >Votre adresse mail:</label><br/>
    <input type='text' name='email' id='email' value='<?php echo $fgmembersite->SafeDisplay('email') ?>' maxlength="50" /><br/>
    <span id='register_email_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='username' >Votre nom d'utilisateur:</label><br/>
    <input type='text' name='username' id='username' value='<?php echo $fgmembersite->SafeDisplay('username') ?>' maxlength="50" /><br/>
    <span id='register_username_errorloc' class='error'></span>
</div>
<div class='container' style='height:80px;'>
    <label for='password' >Votre mot de passe:</label><br/>
    <div class='pwdwidgetdiv' id='thepwddiv' ></div>
    <noscript>
    <input type='password' name='password' id='password' maxlength="50" />
    </noscript>    
    <div id='register_password_errorloc' class='error' style='clear:both'></div>
</div>

<div class='container'>
    <input type='submit' name='Valider' value='Valider' />
</div>

</fieldset>
</form>
</div>
<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

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

<!--
Form Code End (see html-form-guide.com for more info.)
-->

</body>
</html>