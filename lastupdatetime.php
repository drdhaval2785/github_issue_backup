<?php
/* This script is a supporting script for github_issue_backup version 1.2.0, Date 04 June 2016 (https://github.com/drdhaval2785/github_issue_backup)
	Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
	Purpose - To decide the last time the repository issues were updated by the script user satisfactorily (validated). If there is any new issue updated after this time, only that issue is downloaded and updated (to cut time and data usage).
	Usage - `php lastupdatetime.php username reponame`
	Reference - php script 4 in github_issue_backup.sh
	Arguments - This code takes two arguments. One - username, Two - reponame.
	Input - timelog.txt (generated via a PHP script in github_issue_backup.sh)
	Ouput - Last time when the repository was updated satisfactorily. If there is no such time, it returns a random time of '1980-01-01T00:00:01Z'. (To make sure that everything is downloaded).
*/
// Hide error reportings.
error_reporting(0);
# This code can be replaced by a grep code, but grep proved too difficult for me, so falling back to PHP.
$user = $argv[1];
$repo = $argv[2];
// Read timelog.txt in an array
$filename = "timelog.txt";
$data = file($filename);
$output = array();
// A typical line in timelog.txt is in the following format
// username,reponame,lastupadatetime,validated
// sanskrit-lexicon,CORRECTIONS,2016-06-04T07:12:29Z,validated
// We need to extract the lastupdatetime for a given user and repository.
foreach($data as $datum)
{
	$datum = trim($datum);
	// If the line is in expected format
	if (strpos($datum,',')!==false)
	{
		// separate the entries
		list($usr,$rep,$tim,$valid) = explode(',',$datum);
		// If the user and repo names match and the entry is validated
		if ($user===$usr && $repo===$rep && $valid==="validated")
		{
			// Add time to the output
			$output[] = $tim;
		}
	}
}
// If there is any entry matching the conditions,
if (count($output)>0)
{
	// display the latest update time of the given repository.
	echo max($output);
}
// Else, echo a very old date, suggesting that all issues need to be downloaded afresh.
else
{
	echo '1980-01-01T00:00:01Z';
}
?>
