<?php
/* This script is a supporting script for github_issue_backup version 1.2.0, Date 04 June 2016 (https://github.com/drdhaval2785/github_issue_backup)
	Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
	Purpose - To update timelog.txt, after successful termination of script.
	Usage - `php timelogupdater.php`
	Reference - php script 13 in github_issue_backup.sh
	Arguments - This code takes no arguments.
	Input - timelog.txt (generated via a php script in github_issue_backup.sh)
	Ouput - The same files with latest time of the given repository marked with a tag `validated` i.e. the whole download completed without hinderence. 
	Notes - For any future downloads, only the issues updated after this validated time would be downloaded and processed.
	See https://github.com/drdhaval2785/github_issue_backup/issues/36 for details
*/
// Hide error reportings.
error_reporting(0);

// From the list of items in timelog.txt, returns only the data of repositories which match the given username.
function repo($username,$item)
{
	$output = array();
	for($i=0;$i<count($item);$i++)
	{
		// If the usernames match, store the repository name in $output.
		if ($item[$i][0] === $username)
		{
			$output[] = $item[$i][1];
		}
	}
	// Return only the unique repository names (to avoid duplication).
	$output = array_unique($output);
	$output = array_values($output);
	return $output;
}
// For a given username and repository name, return the latest validated time.
function maxtime($username,$reponame,$item)
{
	$output = array();
	for($i=0;$i<count($item);$i++)
	{
		// If username and repository name match, store the time in $output.
		if($item[$i][0] === $username && $item[$i][1] === $reponame)
		{
			$output[] = $item[$i][2];
		}
	}
	// Return the latest validated time from $output.
	return max($output);
}
// Read from timelog.txt and update it.
// Discard earlier time and keep only the latest validated time for each repository.
function timelogupdater()
{
	// array of `username,reponame,time,validated`
	$data = file('timelog.txt');
	$i=0;	
	foreach($data as $datum)
	{
		if (strpos($datum,',')!==false)
		{
			// Explode into constitutent parts.
			list($user[$i],$repo[$i],$tim[$i],$val[$i]) = explode(',',$datum);
			// $item is an array of those parts.
			$item[$i] = array($user[$i],$repo[$i],$tim[$i],$val[$i]);
			$i++;
		}
	}
	// Find out unique usernames from the timelog.txt
	$uniqueuser = array_unique($user);
	$uniqueuser = array_values($uniqueuser);
	// Open the file to write our output (the same as input file)
	$fout = fopen('timelog.txt','w','utf-8');
	// For each username
	foreach($uniqueuser as $unius)
	{
		// Find repository names of the given user (in timelog.txt)
		$repoforauser = repo($unius,$item);
		// For a given repository name
		foreach($repoforauser as $repous)
		{
			// Find the latest time when the repository was satisfactorily updated.
			$maxtime = maxtime($unius,$repous,$item);
			// Put that into the output file with a `validated` tag at the end.
			// `validated` ensures that the download script reached its end satisfactorily, and was not terminated / exited in between.
			fputs($fout,$unius.",".$repous.",".$maxtime.",validated\n");
		}
	}
	fclose($fout);
}
// Run the code actually.
timelogupdater();
?>