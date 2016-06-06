<?php
/* This script is a supporting script for github_issue_backup version 1.2.0, Date 04 June 2016 (https://github.com/drdhaval2785/github_issue_backup)
	Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
	Purpose - To substitute local reference to images in the HTML files, rather than web.
	Usage - `php substitute_images.php username reponame issuelist`
	Reference - php script 10 in github_issue_backup.sh
	Arguments - This code takes three arguments. One - username, Two - reponame, Three - issuelist.
	Input - username/reponame/html/*.html (generated via a presentable.php script in github_issue_backup.sh)
	Ouput - The same files with reference to local images in username/reponame/html/images folder, rather than web reference.
*/
// Hide error reportings.
error_reporting(0);
// Read arguments
$argv1 = $argv[1];
$argv2 = $argv[2];
$argv3 = $argv[3];

// Space -> Comma separated.
for ($i=4;$i<count($argv);$i++)
{
	$argv[3] = $argv[3].",".$argv[$i];
}

// array of issues to handle.
$x = explode(",",$argv[3]);
// For each issue, substitute image links with local reference.
foreach ($x as $val)
{
	substitute_images($argv1,$argv2,$val);	
}

// Function to substitute image weblinks with local links.
function substitute_images($username,$reponame,$number)
{
	// Read HTML file.
	$input = file_get_contents("$username/$reponame/html/$number.html");
	// Store image web links in an array.
	$filename = file("imagelinks.txt");
	// If there are any entries in imagelinks.txt, go ahead. 
	// Otherwise, there is nothing to substitute.
	if (count($filename)>0)
	{
		// Trim
		$filename = array_map('trim',$filename);
		foreach($filename as $fn)
		{
			// Find replacement for https://cloud...... into images/*.*
			$rep = preg_replace('/^(h.+\/\/cloud.+)\/([^\/]+)$/',"images/$2",$fn);
			// Do actual replacement
			$input = str_replace($fn,$rep,$input);
		}
		// Put the modified output into the same file as input.
		$outfile = fopen("$username/$reponame/html/$number.html","w+");
		fputs($outfile,$input);
		fclose($outfile);
	}
}
?>