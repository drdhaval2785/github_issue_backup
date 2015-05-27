<?php
# Code starts 

// Creating a blank array $link
$link = array();
// Calling the function readfile which creates an array $link which has website links of emojis.
read_file();	
// Opening the file emojilinks.txt to store the website links to the emojis.
$emojifile = fopen("emojilinks.txt","w+");
// This if block prevents unnecessary notice in display if the $link doesn't have any member.
if (count($link)>0)
{
	// Appending the website address to emoji names and storing them as links.
	foreach ($link as $value)
	{
		fputs($emojifile,'http://www.emoji-cheat-sheet.com/'.$value."\n"); // e.g. http://www.emoji-cheat-sheet.com/bowtie.png
	}	
}
// Closing the file having links to emojis.
fclose($emojifile);

# Code ends.


# Functions start.
// Function read_file reads the HTML file and stores the array of emoji names.
function read_file()
{
	// Read the htm file
	$input = file_get_contents("emoji_cheat_sheet.htm");
	// Create an array of all words of the file. (breaking from spaces)
	$array = explode(" ",$input);
	// Getting the match from regex to fetch only the links to image files. This has to be suitably modified by seeing the source file of HTML in case we want to use for other purposes.
	$reg = preg_grep('/data-src="graphics\/emojis\//',$array);
	// Applying function trimming to each of the member of the array.
	$reg = array_map('trimming',$reg);
}

// Function trimming trims and stores the emoji names in $link.
function trimming($text)
{ 
	global $link; // Making it global to use outside the function scope.
	$text = str_replace('data-src="','',$text); // This may need modification based on HTM flie you are scraping.
	$text = str_replace('"></span>','',$text); // This may need modification based on HTM flie you are scraping.
	$text = trim($text);
	$link[] = $text; // Creating an array.
}
# Functions end.
?>