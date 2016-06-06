<?php
/* This script is a supporting script for github_issue_backup version 1.2.0, Date 04 June 2016 (https://github.com/drdhaval2785/github_issue_backup)
	Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
	Purpose - To substitute local reference to images in the HTML files, rather than web.
	Usage - `php substitute_files.php username reponame issuelist`
	Reference - php script 11 in github_issue_backup.sh
	Arguments - This code takes three arguments. One - username, Two - reponame, Three - issuelist.
	Input - username/reponame/html/*.html (generated via a presentable.php script in github_issue_backup.sh)
	Ouput - The same files with reference to local files in username/reponame/html/files folder, rather than web reference.
*/
// Hide error reportings.
error_reporting(0);
// Read arguments.
$argv1 = $argv[1];
$argv2 = $argv[2];
$argv3 = $argv[3];

// Space -> Comma separated.
for ($i=4;$i<count($argv);$i++)
{
	$argv[3] = $argv[3].",".$argv[$i];
}

// Array of issues to be handled.
$x = explode(",",$argv[3]);
// For each issue, substitute web file links with local file links.
foreach ($x as $val)
{
	substitute_files($argv1,$argv2,$val);	
}

// Function to do replacement of web links with local links.
function substitute_files($username,$reponame,$number)
{
	// Read HTML file
	$input = file_get_contents("$username/$reponame/html/$number.html");
	// Store all web file links into an array.
	$filename = file("filelinks.txt");
	// If there is any entry in filelinks.txt, go ahead.
	// Otherwise, there is nothing to substitute.
	if (count($filename)>0)
	{
		// Trim.
		$filename = array_map('trim',$filename);
		foreach($filename as $fn)
		{
			// Find a replacement.
			$rep = preg_replace('/^(https.+)\/([^\/]+)$/',"files/$2",$fn);
			// Do replacement.
			$input = str_replace($fn,$rep,$input);
		}
		// Put the altered string in the same input HTML file.
		$outfile = fopen("$username/$reponame/html/$number.html","w+");
		fputs($outfile,$input);
		fclose($outfile);
	}
}
?>