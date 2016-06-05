<?php 
/* This script is a supporting script for github_issue_backup version 1.2.0, Date 04 June 2016 (https://github.com/drdhaval2785/github_issue_backup)
	Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
	Purpose - To decide whether there is such a github repository belonging to a given user (as entered by the script user).
	Usage - `php test_repo_existence.php reponame`
	Reference - php script 2 in github_issue_backup.sh
	Arguments - This code takes one argument - reponame.
	Input - repo.txt (generated via a curl script in github_issue_backup.sh)
	Ouput - "EXIT", if there is no such github user, or no such github repository for this github user. "CONT", if there is such a github repository for a given user.
*/
// Hide error reportings.
error_reporting(0);
// Read 'repo.txt'
$a=file_get_contents("repo.txt");
$b=json_decode($a,true);
// If there is no such github user, github API gives "message": "Not Found". We use it to terminate our shell script.
if ($b['message'] === "Not Found")
{
	echo "EXIT";
}
else
{
	// Create a repository list for a given github user.
	$repolist = array();
	for ($i=0;$i<count($b);$i++)
	{
		$repolist[] = $b[$i]['name'];
	}
	// If the repository the script user wants to download doesn't belong in the list of repositories of the github user, we terminate our shell script.
	if (!in_array($argv[1],$repolist))
	{
		echo "EXIT";
	}
	// Else, continue.
	else
	{
		echo "CONT";
	}
}

?>
