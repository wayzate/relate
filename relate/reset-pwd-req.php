<?PHP
require_once("./include/membersite_config.php");

$emailsent = false;
if(isset($_POST['submitted']))
{
   if($fgmembersite->EmailResetPasswordLink())
   {
        $fgmembersite->RedirectToURL("reset-pwd-link-sent.html");
        exit;
   }
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
      <title>Requête de réinitialisation de mot de passe</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
</head>
<body>
<!-- Form Code Start -->
<div id='fg_membersite'>
<form id='resetreq' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
<fieldset >
<legend>Réinitialisation de mot de passe</legend>

<input type='hidden' name='submitted' id='submitted' value='1'/>

<div class='short_explanation'>* champs requis</div>

<div><span class='error'><?php echo $fgmembersite->GetErrorMessage(); ?></span></div>
<div class='container'>
    <label for='type' >Type*:</label><br/>
    <input type='radio' name='type' id='etudiant' value='etudiant' maxlength="50" <?php if($fgmembersite->SafeDisplay('type')=='etudiant') {echo 'checked = "checked"';};?> />Etudiant
    <input type='radio' name='type' id='entreprise' value='entreprise' maxlength="50" <?php if($fgmembersite->SafeDisplay('type')=='entreprise') {echo 'checked = "checked"';};?>/>Entreprise<br />
    <span id='register_type_errorloc' class='error'></span>
</div>
<div class='container'>
    <label for='username' >Email*:</label><br/>
    <input type='text' name='email' id='email' value='<?php echo $fgmembersite->SafeDisplay('email') ?>' maxlength="50" /><br/>
    <span id='resetreq_email_errorloc' class='error'></span>
</div>
<div class='short_explanation'>Un email vous permettant de modifier votre mot de passe vous a été envoyé</div>
<div class='container'>
    <input type='submit' name='Valider' value='Valider' />
</div>

</fieldset>
</form>
<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

<script type='text/javascript'>
// <![CDATA[

    var frmvalidator  = new Validator("resetreq");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();

    frmvalidator.addValidation("email","req","Please provide the email address used to sign-up");
    frmvalidator.addValidation("email","email","Please provide the email address used to sign-up");
   frmvalidator.addValidation("type","req","Veuillez nous indiquer si vous êtes un étudiant ou une entreprise");
// ]]>
</script>

</div>
<!--
Form Code End (see html-form-guide.com for more info.)
-->

</body>
</html>