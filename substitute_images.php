<?php
$argv1 = $argv[1];
$argv2 = $argv[2];
$argv3 = $argv[3];

for ($x=1;$x<intval($argv3)+1;$x++)
{
	substitute_images($argv1,$argv2,$x);	
}
unlink("imagelinks.txt");

function substitute_images($username,$reponame,$number)
{
	$input = file_get_contents("$username/$reponame/html/$number.html");
	$index_number = file("imagelinks.txt");
	if (count($index_number)>0)
	{
		$index_number = array_map('trim',$index_number);
		$keys = array_keys($index_number);
		foreach ($keys as $value) { $imagekeys[] = "images/".$value.".png"; }
		$output = str_replace($index_number,$imagekeys,$input);
		$outfile = fopen("$username/$reponame/html/$number.html","w+");
		fputs($outfile,$output);
		fclose($outfile);
		
	}
}



?>