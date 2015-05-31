<?php 
// Code to get the last number of issue, if the user has not provided it.
if ($argv[1]==="-a")
{
	$a=file_get_contents("issue.txt"); 
	$b=json_decode($a,true);
	$cr = '';
	$upper = $b[0]['number'];
	for ($i=1;$i<$upper+1;$i++)
	{
		$cr = $cr." ".$i;
	}
	echo $cr;
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