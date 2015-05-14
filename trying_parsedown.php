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
function strip_quote_body($text)
{
	$text = trim($text);
	$text = preg_replace('/^(["])/','',$text);
	$text = preg_replace('/(["],?)$/','',$text);
	$text = str_replace('&quot;','',$text);
//	$text = str_replace(array('"',',','&quot;'),array('','',''),$text);
	return $text;
}
$line = '"##I have compared the Vowel and Consonant patterns of MW against that of PWG. \r\n\r\nThe result is attached <a href=\"https://github.com/drdhaval2785/SanskritSpellCheck/blob/master/MWagainstPWG/MWagainstPWG.html\">herewith</a>. \r\n\r\nCode for checking is attached <a href=\"https://github.com/drdhaval2785/SanskritSpellCheck/blob/master/faultfinder.php\">here</a>. \r\n<a href=\"https://docs.google.com/document/d/1G4HoDz9nuj2GPeHQopNVSnDEGrnXtoAuXFugj4sQHZg/edit?usp=sharing\">Google doc</a> for logic behind approach: \r\nVideo tutorial for code running - http://youtu.be/qLqYUZUGM6M\r\n\r\nInput data : <a href=\"https://github.com/drdhaval2785/SanskritSpellCheck/blob/master/MWslp.txt\">MW</a>\r\n and \r\n<a href=\"https://github.com/drdhaval2785/SanskritSpellCheck/blob/master/PWKslp.txt\">PWG</a> \r\n\r\nI am checking the HTML file thoroughly.\r\nThere are many issues found out by this approach.\r\n\r\nHere is the <a href=\"https://www.youtube.com/watch?v=rKZ_OsSHwsY&feature=youtu.be\">video tutorial</a> about how to use the HTML file for error finding.",';
$line = strip_quote_body($line);
echo parsedown($line);

?>