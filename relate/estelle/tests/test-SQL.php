<?php

require_once("../../estellebis/include/membersite_config.php");

$user_rec = array();


if(!$fgmembersite->CheckLogin()) //le CheckLogin() effectue un "session_start()"
{
    $fgmembersite->RedirectToURL("../login.php");
    exit;
}

$email = $fgmembersite->UserEmail();
$type = $fgmembersite->UserType();

$fgmembersite->GetUserFromEmail($email,$type,$user_rec);

echo $user_rec['name']; //pour tester
echo $user_rec['surname'];
echo $user_rec['day'];
echo $user_rec['month'];
echo $user_rec['year'];
echo $user_rec['gender'];
echo $user_rec['nationality'];
echo $user_rec['telnumber'];
echo $user_rec['email'];
echo $user_rec['adresstown'];
echo $user_rec['listformname'][0];
echo $user_rec['listformname'][1];
echo $user_rec['listformbegyear'][0];
echo $user_rec['listformendyear'][0];
echo $user_rec['listforminprogress'][0];
echo $user_rec['listproexbegyear'][0];
echo $user_rec['listproexendyear'][0];
echo $user_rec['listtonguename'][1];
echo $user_rec['listtonguelvl'][1];

	//for ($i=0; $i<count($user_rec['listsportname']-1); $i++)
//	{
//	echo $user_rec['listsportname'][$i];
//	echo ', ';
//	}
//	$longueur = count($user_rec['listsportname']-1);
//	echo $user_rec['listsportname'][$longueur];

//arsort($user_rec['listformendyear']);
//foreach ($user_rec['listformendyear'] as $val) {
//    echo "$val\n";
//}


?>
