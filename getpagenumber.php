<?php
/* This script is a supporting script for github_issue_backup version 1.2.0, Date 04 June 2016 (https://github.com/drdhaval2785/github_issue_backup)
	Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
	Purpose - To decide the number of pages we need to navigate, if the issues are > 100 (maximum limit by github API for a single page).
	Usage - `php getpagenumber.php username reponame`
	Reference - php script 5 in github_issue_backup.sh
	Arguments - This code takes two arguments. One - username, Two - reponame.
	Input - username/reponame/header/head.txt (generated via a cURL script in github_issue_backup.sh)
	Ouput - A space separated list of pages to navigate e.g. `1 2 3 ... 20`. If there is only one page, it returns `1`.
*/
// Hide error reportings.
error_reporting(0);
// Read the arguments into variables.
$user = $argv[1];
$repo = $argv[2];
// Read username/reponame/header/head.txt
$filename = $user."/".$repo."/header/head.txt";
$data = file_get_contents($filename);
// Split the header for data we are interested in (last page number).
$split = preg_split('/page[=]([0-9]+)[>][;][ ]rel[=]["]last["]/',$data,0,PREG_SPLIT_DELIM_CAPTURE);
// If there is such a last page in header
if (count($split) > 1)
{
	$num = intval($split[1]);
	// display 1 to lastpagenumber with space in between.
	echo implode(' ',range(1,$num));
}
// Else display 1 (Only one page to navigate).
else
{
	echo "1";
}
?>