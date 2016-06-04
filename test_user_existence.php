<?php 
// Hide error reportings.
error_reporting(0);
// Code to test whether the user exists or not.
$a=file_get_contents("user.txt");
$b=json_decode($a,true);
if ($b['message'] === "Not Found")
{
	echo "EXIT";
}
else
{
	echo "CONT";
}
?>
