<?php 
// Code to get the last number of issue, if the user has not provided it.
$a=file_get_contents("repo.txt"); 
$b=json_decode($a,true); 
for ($i=0;$i<count($b);$i++)
{
echo $b[$i]['name']." ";	
}
?>