<?php
/* This script is a supporting script for github_issue_backup version 1.2.0, Date 04 June 2016 (https://github.com/drdhaval2785/github_issue_backup)
	Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
	Purpose - To create an HTML index of downloaded issues.
	Usage - `php substitute_images.php username reponame`
	Reference - php script 12 in github_issue_backup.sh
	Arguments - This code takes two arguments. One - username, Two - reponame.
	Input - username/reponame/index.txt (generated via a shell script in github_issue_backup.sh)
	Ouput - username/reponame/html/index.html
*/
// Hide error reportings.
error_reporting(0);

// Header for proper display of UTF-8, CSS links and javascript for syntax highlighting.
$header = "<!DOCTYPE html>
<html class='' lang='en'>
<head prefix='og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# object: http://ogp.me/ns/object# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#'>
<meta http-equiv='content-type' content='text/html; charset=UTF-8'>
<meta charset='utf-8'>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta http-equiv='Content-Language' content='en'>
<title>Index of issues</title>
<link href='github-c486157afcc5f58155a921bc675afb08733fbaa8dcf39ac2104d3.css' media='all' rel='stylesheet'>
<link href='github2-da2e842cc3f0aaf33b727d0ef034243c12ab008fd09b24868b97.css' media='all' rel='stylesheet'>
</head>
<body class='logged_in  env-production windows vis-public'>
";
// Read arguments.
$argv1 = $argv[1];
$argv2 = $argv[2];

// Creating an HTML file to store the output.
$outfile = fopen("$argv[1]/$argv[2]/html/index.html","w+");
// Putting the header inside HTML file.
fputs($outfile,$header);
fclose($outfile);

// Create the output file.
$outfile=fopen("$argv[1]/$argv[2]/html/index.html","a+");
// For issue number 1 to maximum
for($i=1;$i<getmax($argv[1],$argv[2])+1;$i++)
{
	// Reading from the .txt file of individual issue. UserName/RepoName/IssueNumber.txt format.
	$in = file_get_contents("$argv[1]/$argv[2]/$i.txt");
	// Using the separator 'BODY STARTS FROM HERE' to bifurcate the data in two parts. The first part is Issue. The second part is comments.
	$in_separate = explode('BODY STARTS FROM HERE',$in);
	// Issue details stored as an array.
	$issue_details = json_decode($in_separate[0],true);
	// Getting the details from json_decoded array.
	$number = $issue_details["number"];
	$title = $issue_details["title"];
	// Putting title in the HTML file.
	fputs($outfile,"$i - <a href='$number.html' target='_blank'>$title</a><br/>");
}
fputs($outfile,"</body></html>");
fclose($outfile);

// Remove unnecessary parts from data.
$inward = file_get_contents("$argv[1]/$argv[2]/html/index.html");
$outward = str_replace("<a href='.html' target='_blank'></a>","",$inward);
file_put_contents("$argv[1]/$argv[2]/html/index.html",$outward);

// Function to get the maximum number of issues in username/reponame/index.txt file.
// We run the index from 1 to the maximum issue number.
function getmax($arg1,$arg2)
{
	$a=file("$arg1/$arg2/index.txt");
	$a = array_map('intval',$a);
	return max($a);
}
?>