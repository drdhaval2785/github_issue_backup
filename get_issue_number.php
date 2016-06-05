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
	$a=file_get_contents("issue.txt");
	$b=json_decode($a,true);
	$cr = '';
	$times[] = '1980-01-01T00:00:01Z';
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
	if ($cr!=="")
	{
		echo $cr;
	}
	elseif (count($b)===0)
	{
		echo 'EXIT1';
	}
	else
	{
		echo 'EXIT';
	}
	// Writing a timelog.
	$tlog = fopen('timelog.txt','a+','utf-8');
	fputs($tlog,$user.",".$repo.",".max($times).",");
	fclose($tlog);
}
elseif (strpos($argv[1],',')!==false)
{
	$br = explode(",",$argv[1]);
	$cr = '';
	foreach ($br as $val)
	{
		if (strpos($val,":")!==false)
		{
			$lower0 = explode(":",$val); $lower=$lower0[0];
            $upper0 = explode(":",$val);$upper = $upper0[1];			
			for ($i=$lower;$i<$upper+1;$i++)
			{
				$cr = $cr." ".$i;
			}
		}
		else
		{
			$cr = $cr." ".$val;
		}
	}
	$cr = trim($cr);
	echo $cr;
}
else
{
	echo $argv[1];
}

?>