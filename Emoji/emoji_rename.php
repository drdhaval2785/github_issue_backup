<?php

emoji_names();
function emoji_names()
{
	$array=file("emojilinks.txt");
	foreach ($array as $value)
	{
		$val = str_replace('http://www.emoji-cheat-sheet.com/graphics/emojis/','',$value);
		$val = str_replace('.png','',$val);
		$out[] = $val;
	}
	$out = array_map('trim',$out);
	$keys = array_keys($out);
	$emojilist = fopen ("emojilist.txt","w+");
	for ($x=0; $x<count($array);$x++)
	{
//		rename("assets1/$keys[$x].png","assets1/$out[$x].png");
		echo '"'.$out[$x].'",<br/>';
		fputs($emojilist,$out[$x]."\n");
	}
	fclose($emojilist);
}

?>