<?php
include 'Parsedown.php';
function parsedown($text)
{
	$text = str_replace('\r\n','<br/>',$text);
	$text = str_replace('href=\"','href="',$text);
	$text = str_replace('\">','">',$text);
	$Parsedown = new Parsedown();
	echo $Parsedown->text($text);
}
$line = '25 mahAyogapaYcaratneASvalAyanopayogyADAnaprakaraRa - eA >>\r\nmahAyogapaYcaratne ASvalAyanopayogyADAnaprakaraRa - eA (space needed)\r\n\r\nIn MW print it is like this- separated by hyphens.\r\n![mw](https://cloud.githubusercontent.com/assets/6392207/4521785/94bf9f5e-4d1b-11e4-9a4f-7092d7c9ac9b.JPG)\r\n';
echo parsedown($line);
?>