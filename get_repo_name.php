<?php 
/* This script is a supporting script for github_issue_backup version 1.2.0, Date 04 June 2016 (https://github.com/drdhaval2785/github_issue_backup)
	Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
	Purpose - To decide the repositories of a given github user (If the script user has not provided it)
	Usage - `php get_repo_name.php`
	Reference - php script 3 in github_issue_backup.sh
	Arguments - This code takes no arguments.
	Input - repo.txt (generated via a curl script in github_issue_backup.sh)
	Ouput - "EXIT", if there is no such github user, or no github repository for this github user. "repo1 repo2 repo3 ....", if there are github repositories for a given github user.
*/
// Hide error reportings.
error_reporting(0);
// Read 'repo.txt'
$a=file_get_contents("repo.txt"); 
$b=json_decode($a,true);
// If there is no such user or no repositories, return EXIT
if ($b[0]['message'] === "Not Found")
{
	echo "EXIT";
}
// Else, give list of repositories with a space in between.
// Space separated list is chosen, because of its ease of use in shell scripts as arrays.
else
{
	for ($i=0;$i<count($b);$i++)
	{
	echo $b[$i]['name']." ";	
	}
}
?>