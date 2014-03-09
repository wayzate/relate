<?php

require_once("../include/membersite_config.php");

$user_rec = array();


if(!$fgmembersite->CheckLogin()) //le CheckLogin() effectue un "session_start()"
{
    $fgmembersite->RedirectToURL("../login.php");
    exit;
}

$email = $fgmembersite->UserEmail();
$type = $fgmembersite->UserType();

$fgmembersite->GetUserFromEmail($email,$type,$user_rec);

arsort($user_rec['listformendyear']);
foreach ($user_rec['listformendyear'] as $val) {
    echo "$val\n";
}


?>