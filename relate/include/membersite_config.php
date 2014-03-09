<?PHP
require_once("fg_membersite.php");

$fgmembersite = new FGMembersite();

//Provide your site name here
$fgmembersite->SetWebsiteName('Relate');

//Provide the email address where you want to get notifications
$fgmembersite->SetAdminEmail('matthieu.marielouise@gmail.com');

//Provide your database login details here:
//hostname, user name, password, database name and table name
//note that the script will create the table (for example, fgusers in this case)
//by itself on submitting register.php for the first time
$fgmembersite->InitDB(/*hostname*/'localhost',
                      /*username*/'root',
                      /*password*/'toto',
                      /*database name*/'principale',
                      /*table name of users*/'user',
					  /*table name of students*/'student',
					  /*table name of societies*/'society',
					  /*Nombre d'ajout de section max */ 20,
					  /* nombre de rubriques à multiplicité dans le profile */9
					  );

//For better security. Get a random string from this link: http://tinyurl.com/randstr
// and put it here
$fgmembersite->SetRandomKey('qSRcVS6DrTzrPvr');

?>