<?php
$header = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<!--... Defining UTF-8 as our default character set, so that devanagari is displayed properly. -->
<meta charset="UTF-8">
<!--... Defining CSS -->
<link rel="stylesheet" type="text/css" href="mystyle.css">
<!--... including Ajax jquery. -->
</head> 
<body>
';
mkdir("$argv[1]/$argv[2]/html/");
copy("mystyle.css","$argv[1]/$argv[2]/html/mystyle.css");
for($i=1;$i<$argv[3]+1;$i++)
{
	$input = file("$argv[1]/$argv[2]/$i.txt");
	$outfile = fopen("$argv[1]/$argv[2]/html/$i.html","w+");
	//$separate = explode('[/n',$input);
	//print_r($input);
//	echo $header;
	fputs($outfile,$header);
	$issuenumber = $input[1];
	$issuenumber = trim($issuenumber);
	$issuenumber = str_replace('"url": ','',$issuenumber);
	$issuenumber = str_replace(array('"',',','\r\n'),array('','','<br/>'),$issuenumber);
	$issuenumber = preg_replace('/https?:\/\/[^\s"<>]+/', '<a href="$0" target="_blank">$0</a>', $issuenumber);

//	echo "<h1>$issuenumber</h1><hr/>";
	fputs($outfile,"<h1>$issuenumber</h1><hr/>");
	foreach ($input as $value)
	{
		if (strpos($value,'"title"')!==false)
		{
			$title = $value;
			$title = trim($title);
			$title = str_replace('"title": ','',$title);
			$title = str_replace(array('"',',','\r\n'),array('','','<br/>'),$title);
			$title = preg_replace('/https?:\/\/[^\s"<>]+/', '<a href="$0" target="_blank">$0</a>', $title);
//			echo "<h2>Title - $title</h2><hr/>";
			fputs($outfile,"<h2>Title - $title</h2><hr/>");
		}
		elseif (strpos($value,'"login"')!==false)
		{
			$username = $value;
			$username = trim($username);
			$username = str_replace('"login": ','',$username);
			$username = str_replace(array('"',',','\r\n'),array('','','<br/>'),$username);
			$username = str_replace($username,"<a href='https://github.com/$username' target='_blank'>".$username."</a>", $username);		
//			echo "<b>$username :</b><br/>";
			fputs ($outfile,"<b>$username :</b><br/>");
		}
		elseif (strpos($value,'"body"')!==false)
		{
			$body = $value;
			$body = trim($body);
			$body = str_replace('"body": ','',$body);
			$body = str_replace(array('"',',','\r\n'),array('','','<br/>'),$body);
			$body = preg_replace('/https?:\/\/[^\s"<>]+/', '<a href="$0" target="_blank">$0</a>', $body);
//			echo "<p>$body</p><hr/>";
			fputs($outfile,"<p>$body</p><hr/>");
		}
	}	
//echo "</body></html>";
fputs($outfile,"</body></html>");
fclose($outfile);
}
?>