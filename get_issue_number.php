<?php 
/* This script is a supporting script for github_issue_backup version 1.2.0, Date 04 June 2016 (https://github.com/drdhaval2785/github_issue_backup)
	Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
	Purpose - To get the issue numbers which need to be downloaded.
	Usage - `php get_issue_number.php issuelist username reponame`
	Reference - php script 6 in github_issue_backup.sh
	Arguments - This code takes three arguments. One - issuelist, Two - username, Three - reponame.
	Input - issue.txt (generated via a cURL script in github_issue_backup.sh)
	Ouput - A space separated list of issues to download e.g. `1 2 3 ... 20`.
*/
// Hide error reportings.
error_reporting(0);

// If the user has entered `-a` i.e. all issues to download.
if ($argv[1]==="-a")
{
	// Adding a new line in timelog.txt
	if (is_file('timelog.txt'))
	{
		$tlog = fopen('timelog.txt','a+','utf-8');
		fputs($tlog,"\n");
		fclose($tlog);
	}
	$user = $argv[2];
	$repo = $argv[3];
	// Read issue.txt
	$a=file_get_contents("issue.txt");
	$b=json_decode($a,true);
	$cr = '';
	// Setting initial time to 1980
	$times[] = '1980-01-01T00:00:01Z';
	// Scraping updated_at times and $cr (issues to be downloaded in decreasing order, separated by a space)
	if (count($b)>0)
	{
		$upper = $b[0]['number'];
		for ($j=$upper;$j>=0;$j--)
		{
			$cr = $b[$j]['number']." ".$cr;
			$times[] = $b[$j]['updated_at'];
		}
	}
	$cr = trim($cr);
	// If there is non empty $cr, display it.
	if ($cr!=="")
	{
		echo $cr;
	}
	// If there is no issue at all in the given repository, return EXIT1, which will display proper error message to the script user.
	elseif (count($b)===0)
	{
		echo 'EXIT1';
	}
	// If there is no issue to be updated, return EXIT, which will exit the script with proper message, that there is nothing to update.
	else
	{
		echo 'EXIT';
	}
	// Writing a timelog.
	// Fourth item `validated` would be written only after the whole shell script has run satisfactorily.
	// This will ensure that user terminated / exit script timings are not added to validated database.
	$tlog = fopen('timelog.txt','a+','utf-8');
	fputs($tlog,$user.",".$repo.",".max($times).",");
	fclose($tlog);
}
// If the user has entered in 1:10 or 1,44,23 or 1:10,12,14 etc specifications for downloading only specific issues / range of issues
elseif (strpos($argv[1],',')!==false || strpos($argv[1],':')!==false)
{
	// Segregate on basis of commas e.g. 1:10,12,14 -> array('1:10','12','14')
	$br = explode(",",$argv[1]);
	$cr = '';
	foreach ($br as $val)
	{
		// If there is a colon, create a range e.g. 1:10 -> 10 9 8 7 6 5 4 3 2 1
		if (strpos($val,":")!==false)
		{
			$lower0 = explode(":",$val); $lower=$lower0[0];
            $upper0 = explode(":",$val);$upper = $upper0[1];			
			for ($i=$lower;$i<$upper+1;$i++)
			{
				$cr = $cr." ".$i;
			}
		}
		// Else, add it to the end
		else
		{
			$cr = $cr." ".$val;
		}
	}
	// Trim and display
	$cr = trim($cr);
	echo $cr;
}
// If there is no comma or colon, it means that the user has entered single issue.
else
{
	// Display single issue.
	echo $argv[1];
}
?>