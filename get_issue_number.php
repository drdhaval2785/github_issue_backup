<?php 
// Code to get the last number of issue, if the user has not provided it.
$a=file_get_contents("issue.txt"); 
$b=json_decode($a,true); 
echo $b[0]['number'];
?>