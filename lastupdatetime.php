<?php
# This code can be replaced by a grep code, but it proved too difficult for me.
$user = $argv[1];
$repo = $argv[2];
$filename = "timelog.txt";
$data = file($filename);
$output = array();
foreach($data as $datum)
{
	$datum = trim($datum);
	if (strpos($datum,',')!==false)
	{
		list($usr,$rep,$tim,$valid) = explode(',',$datum);
		if ($user===$usr && $repo===$rep && $valid==="validated")
		{
			$output[] = $tim;
		}
	}
}
if (count($output)>0)
{
	echo max($output);
}
else
{
	echo '1980-01-01T00:00:01Z';
}
?>
