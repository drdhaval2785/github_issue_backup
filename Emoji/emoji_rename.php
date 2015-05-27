<?php
// Calling function emoji_names
emoji_names();

// Function emoji_names fetches the names of emojis from links to emojis.
function emoji_names()
{
	// Read the content of txt file having links to emojis in an array.
	$array=file("emojilinks.txt");
	// Removing the unnecessary parts of the links and keeping only emoji names.
	foreach ($array as $value)
	{
		$val = str_replace('http://www.emoji-cheat-sheet.com/graphics/emojis/','',$value);  // This may need modification based on HTM flie you are scraping.
		$val = str_replace('.png','',$val); // This may need modification based on whether the emojis are stored in png / jpg / tiff etc.
		$out[] = $val; // Emoji names put in an array.
	}
	$out = array_map('trim',$out); // Trimming
	$keys = array_keys($out); // Creating an array having keys of $out.
	$emojilist = fopen ("emojilist.txt","w+"); // Opening a file for names of emojis.
	// Initially the emoji images were stored in assets1 folder with names like 0.png, 1.png, 2.png etc. Now we are converting those names back to emoji names e.g. bowtie.png, laugh.png etc.
	for ($x=0; $x<count($array);$x++)
	{
		// Renaming the filenames.
		rename("assets1/$keys[$x].png","assets1/$out[$x].png");
//		echo '"'.$out[$x].'",<br/>'; // May be needed if you want to echo the output.
		fputs($emojilist,$out[$x]."\n");  // Putting the name of emoji to the file emojilist.txt.
	}
	fclose($emojilist); // closing the file emojilist.txt
}

?>