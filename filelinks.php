<?php
$argv1 = $argv[1];
$argv2 = $argv[2];
$argv3 = $argv[3];
$link = array();

for ($i=4;$i<count($argv);$i++)
{
	$argv[3] = $argv[3].",".$argv[$i];
}

$x = explode(",",$argv[3]);
foreach ($x as $val)
{
	read_file($argv1,$argv2,$val);	
}
$linkfile = fopen("filelinks.txt","w+");
if (count($link)>0)
{
	foreach ($link as $value)
	{
		fputs($linkfile,$value."\n");
	}	
}
fclose($linkfile);


function read_file($username,$reponame,$number)
{
	$input = file_get_contents("$username/$reponame/$number.txt");
	$array = explode(" ",$input);
	$reg = preg_grep("/https:\/\/github.com\/$username\/$reponame\/files/",$array);
	$reg = array_map('trimming',$reg);
}
function trimming($text)
{ 
	global $link;
	$text = preg_split('/[(]([^)]*)[)]/',$text,-1,PREG_SPLIT_DELIM_CAPTURE)[1];
	$link[] = $text;
}

?>