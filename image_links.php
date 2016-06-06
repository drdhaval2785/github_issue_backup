<?php
/* This script is a supporting script for github_issue_backup version 1.2.0, Date 04 June 2016 (https://github.com/drdhaval2785/github_issue_backup)
	Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
	Purpose - To scrape the image links from issues.
	Usage - `php image_links.php username reponame issuelist`
	Reference - php script 8 in github_issue_backup.sh
	Arguments - This code takes three arguments. One - username, Two - reponame, Three - issuelist.
	Input - username/reponame/*.txt (generated via a cURL script in github_issue_backup.sh)
	Ouput - imagelinks.txt
*/
// Hide error reportings.
error_reporting(0);
// Read arguments
$argv1 = $argv[1];
$argv2 = $argv[2];
$argv3 = $argv[3];
// Initialize an array for storing results.
$link = array();

for ($i=4;$i<count($argv);$i++)
{
	// Create a comma separated value instead of space separated one.
	$argv[3] = $argv[3].",".$argv[$i];
}

// Create an array of issue numbers to handle.
$x = explode(",",$argv[3]);
foreach ($x as $val)
{
	// Read the file and process. Store each link in $link.
	read_file($argv1,$argv2,$val);	
}
// Put the data of $link into imagelinks.txt file
$linkfile = fopen("imagelinks.txt","w+");
if (count($link)>0)
{
	foreach ($link as $value)
	{
		fputs($linkfile,$value."\n");
	}	
}
fclose($linkfile);

// Reads only image links from the issue text.
function read_file($username,$reponame,$number)
{
	$input = file_get_contents("$username/$reponame/html/$number.html");
	$array = explode(" ",$input);
	// grep to identify the image links.
	$reg = preg_grep('/https:\/\/cloud.githubusercontent.com\/assets\/.+[.][a-z]{3,4}/',$array);
	$reg = array_map('trimming',$reg);
}
// Remove unnecessary traces.
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