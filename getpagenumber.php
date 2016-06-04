<?php
# This code can be replaced by a grep code, but it proved too difficult for me.
$user = $argv[1];
$repo = $argv[2];
$filename = $user."/".$repo."/header/head.txt";
$data = file_get_contents($filename);
$split = preg_split('/page[=]([0-9]+)[>][;][ ]rel[=]["]last["]/',$data,0,PREG_SPLIT_DELIM_CAPTURE);
if (count($split) > 1)
{
	$num = intval($split[1]);
	echo implode(' ',range(1,$num));
}
else
{
	echo "1";
}
?>