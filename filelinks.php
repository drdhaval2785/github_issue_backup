<?php
/* This script is a supporting script for github_issue_backup version 1.2.0, Date 04 June 2016 (https://github.com/drdhaval2785/github_issue_backup)
	Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
	Purpose - To scrape the file links from issues.
	Usage - `php filelinks.php username reponame issuelist`
	Reference - php script 9 in github_issue_backup.sh
	Arguments - This code takes three arguments. One - username, Two - reponame, Three - issuelist.
	Input - username/reponame/*.txt (generated via a cURL script in github_issue_backup.sh)
	Ouput - filelinks.txt
*/
// Hide error reportings.
error_reporting(0);
// Read arguments.
$argv1 = $argv[1];
$argv2 = $argv[2];
$argv3 = $argv[3];
// Initialize an array to store results.
$link = array();

// Space separated to comma separated.
for ($i=4;$i<count($argv);$i++)
{
	$argv[3] = $argv[3].",".$argv[$i];
}

// Array of issues to examine.
$x = explode(",",$argv[3]);
// Read and extract file links.
foreach ($x as $val)
{
	read_file($argv1,$argv2,$val);	
}
// Put file links into filelinks.txt
$linkfile = fopen("filelinks.txt","w+");
if (count($link)>0)
{
	foreach ($link as $value)
	{
		fputs($linkfile,$value."\n");
	}	
}
fclose($linkfile);

// Extract file links based on a regex and store in an array.
function read_file($username,$reponame,$number)
{
	$input = file_get_contents("$username/$reponame/$number.txt");
	$array = explode(" ",$input);
	$reg = preg_grep("/https:\/\/github.com\/$username\/$reponame\/files/",$array);
	$reg = array_map('trimming',$reg);
}
// Trim the file link and store in an array.
function trimming($text)
{ 
	global $link;
	$text = preg_split('/[(]([^)]*)[)]/',$text,-1,PREG_SPLIT_DELIM_CAPTURE)[1];
	$link[] = $text;
}

?>