<?php
$argv1 = $argv[1];
$argv2 = $argv[2];
$argv3 = $argv[3];

for ($i=4;$i<count($argv);$i++)
{
	$argv[3] = $argv[3].",".$argv[$i];
}

$x = explode(",",$argv[3]);
foreach ($x as $val)
{
	substitute_images($argv1,$argv2,$val);	
}
#unlink("imagelinks.txt");

function substitute_images($username,$reponame,$number)
{
	$input = file_get_contents("$username/$reponame/html/$number.html");
	$filename = file("imagelinks.txt");
	if (count($filename)>0)
	{
		$filename = array_map('trim',$filename);
		foreach($filename as $fn)
		{
			$rep = preg_replace('/^(h.+\/\/cloud.+)\/([^\/]+)$/',"images/$2",$fn);
			$input = str_replace($fn,$rep,$input);
		}
		$outfile = fopen("$username/$reponame/html/$number.html","w+");
		fputs($outfile,$input);
		fclose($outfile);
	}
}
?>