<?php 
// Hide error reportings.
error_reporting(0);
// Code to test whether the repository exists or not.
$a=file_get_contents("repo.txt");
$b=json_decode($a,true);
if ($b['message'] === "Not Found")
{
	echo "EXIT";
}
else
{
	$repolist = array();
	for ($i=0;$i<count($b);$i++)
	{
		$repolist[] = $b[$i]['name'];
	}
	if (!in_array($argv[1],$repolist))
	{
		echo "EXIT";
	}
	else
	{
		echo "CONT";
	}
}

?>
