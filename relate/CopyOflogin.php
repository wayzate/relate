<?php 
$connection = mysql_connect("localhost","root","toto");
mysql_select_db("test",$connection);
$qry = "SELECT DISTINCT `nom_ville` FROM `test`.`villes`";
$result = mysql_query($qry,$connection);
var_dump($result);
while ($res = mysql_fetch_assoc($result)) {
	echo $res['nom_ville'];
	echo "<br />";
}
?>
<html lang="en">
<head>
<meta charset="utf-8" />
<title>jQuery UI Autocomplete - Default functionality</title>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>

</head>
<body>
<div class="ui-widget">
<label for="tags">Tags: </label>
<input id="tags" />
</div>
</body>
</html>