<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document sans titre</title>
</head>
<body>
<?php
class Date
{
   function AffichageCorrect($num)
{
	global $date;
	if ($num < 10) {$date = '0$num';}
	else {$date = '$num';}
	return $date;
}
}

echo AffichageCorrect(8);
echo AffichageCorrect(10);
echo AffichageCorrect(12);
?>
</body>
</html>