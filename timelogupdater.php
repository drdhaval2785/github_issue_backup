<?php
function repo($username,$item)
{
	$output = array();
	for($i=0;$i<count($item);$i++)
	{
		if ($item[$i][0] === $username)
		{
			$output[] = $item[$i][1];
		}
	}
	$output = array_unique($output);
	$output = array_values($output);
	return $output;
}
function maxtime($username,$reponame,$item)
{
	$output = array();
	for($i=0;$i<count($item);$i++)
	{
		if($item[$i][0] === $username && $item[$i][1] === $reponame)
		{
			$output[] = $item[$i][2];
		}
	}
	return max($output);
}
function timelogupdater()
{
	$data = file('timelog.txt');
	$i=0;	
	foreach($data as $datum)
	{
		if (strpos($datum,',')!==false)
		{
			list($user[$i],$repo[$i],$tim[$i],$val[$i]) = explode(',',$datum);
			$item[$i] = array($user[$i],$repo[$i],$tim[$i],$val[$i]);
			$i++;
		}
	}
	$uniqueuser = array_unique($user);
	$uniqueuser = array_values($uniqueuser);
	$fout = fopen('timelog.txt','w','utf-8');
	foreach($uniqueuser as $unius)
	{
		$repoforauser = repo($unius,$item);
		foreach($repoforauser as $repous)
		{
			$maxtime = maxtime($unius,$repous,$item);
			fputs($fout,$unius.",".$repous.",".$maxtime.",validated\n");
		}
	}
	fclose($fout);
}
timelogupdater();
?>