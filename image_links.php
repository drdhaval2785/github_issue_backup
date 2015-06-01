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
$linkfile = fopen("imagelinks.txt","w+");
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
	$input = file_get_contents("$username/$reponame/html/$number.html");
	$array = explode(" ",$input);
	$reg = preg_grep('/https:\/\/cloud.githubusercontent.com\/assets/',$array);
	$reg = array_map('trimming',$reg);
}
function trimming($text)
{ 
	global $link;
	$text = str_replace('src="','',$text);
	$text = str_replace('PNG"','PNG',$text);
	$text = str_replace('png"','png',$text);
	$text = str_replace('JPG"','JPG',$text);
	$text = str_replace('jpg"','jpg',$text);
	$text = trim($text);
	$link[] = $text;
}

?>