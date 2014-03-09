<?PHP
require_once("./include/membersite_config.php");

if(isset($_POST['submitted']))
{
   if($fgmembersite->Login())
   {
        $fgmembersite->RedirectToURL("login-home.php");
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
      <title>Connexion</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>

<!-- Form Code Start -->
<div id='fg_membersite'>
<form id='login' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Connexion</legend>

<input type='hidden' name='submitted' id='submitted' value='1'/>

<!-- <div class='short_explanation'>* champs requis</div> -->

<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
<div class='container'>
    <label for='type' >Type:</label><br/>
    <input type='radio' name='type' id='etudiant' value='etudiant' maxlength="50" <?php if($fgmembersite->SafeDisplay('type')=='etudiant') {echo 'checked = "checked"';};?>/>Etudiant
    <input type='radio' name='type' id='entreprise' value='entreprise' maxlength="50" <?php if($fgmembersite->SafeDisplay('type')=='entreprise') {echo 'checked = "checked"';};?>/>Entreprise<br />
    <span id='register_type_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='username' >Nom d'utilisateur:</label><br/>
    <input type='text' name='username' id='username' value='<?php echo $fgmembersite->SafeDisplay('username') ?>' maxlength="50" /><br/>
    <span id='login_username_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='password' >Mot de passe:</label><br/>
    <input type='password' name='password' id='password' maxlength="50" /><br/>
    <span id='login_password_errorloc' class='error'></span>
</div>

<div class='container'>
    <input type='submit' name='Valider' value='Valider' />
</div>
<div class='short_explanation'><a href='reset-pwd-req.php'>Mot de passe oublié ?</a></div>
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
<!--
Form Code End (see html-form-guide.com for more info.)
-->

</body>
</html>