<?php
require_once("./include/membersite_config.php");

if($fgmembersite->ClearAllUnlinkedInDB()){
	$fgmembersite->RedirectToURL("login-home.php");
	exit;
}
else {echo "echec";}
?>