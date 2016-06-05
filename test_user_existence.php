<?php 
/* This script is a supporting script for github_issue_backup version 1.2.0, Date 04 June 2016 (https://github.com/drdhaval2785/github_issue_backup)
	Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
	Purpose - To decide whether there is such a github user by the given name (as entered by the script user).
	Usage - `php test_user_existence.php`
	Reference - php script 1 in github_issue_backup.sh
	Arguments - This code takes no arguments.
	Input - user.txt (generated via a curl script in github_issue_backup.sh)
	Ouput - "EXIT", if there is no such github user. "CONT", if there is such a github user.
*/
// Hide error reportings.
error_reporting(0);
// Read 'user.txt'
$a=file_get_contents("user.txt");
$b=json_decode($a,true);
// If there is no such user, github API returns a single member json data, having "message": "Not Found".
// We use it to show EXIT. Shell script uses this EXIT to terminate the script and shows error to the script user.
if ($b['message'] === "Not Found")
{
	echo "EXIT";
}
// Else continue the shell script.
else
{
	echo "CONT";
}
?>
