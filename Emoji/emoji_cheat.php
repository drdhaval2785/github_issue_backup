<?php

$link = array();
read_file();	
$emojifile = fopen("emojilinks.txt","w+");
if (count($link)>0)
{
	foreach ($link as $value)
	{
		fputs($emojifile,'http://www.emoji-cheat-sheet.com/'.$value."\n");
	}	
}
fclose($emojifile);

function read_file()
{
	$input = file_get_contents("emoji_cheat_sheet.htm");
	$array = explode(" ",$input);
	$reg = preg_grep('/data-src="graphics\/emojis\//',$array);
	$reg = array_map('trimming',$reg);
}

function trimming($text)
{ 
	global $link;
	$text = str_replace('data-src="','',$text);
	$text = str_replace('"></span>','',$text);
	$text = trim($text);
	$link[] = $text;
}

?>