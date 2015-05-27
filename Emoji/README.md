# Modified Emoji library
This is a modification of the Emoji library to expand the number of emojis supported.

The data which we fetch is http://www.emoji-cheat-sheet.com/

The images are stored in assets1 folder. 

(assets folder has the images from original emoji library).


# User instruction for Emoji library

````php
<?php

$emoji = new Emoji();
echo $emoji->render("Hello, I'm :octocat:");
````

# Documentation about fetching the data

This library already has the data of http://www.emoji-cheat-sheet.com/.

In case you want to build the data de novo

1. Click on emoji_library_creator.sh or type `emoji_library_creator.sh` in command line.
2. This will generate three outputs `emojilinks.txt`, `emojilist.txt` and `assets1` folder.
3. Replace the array $emoji in Emoji.php with the data in `emojilist.txt`. N.B. You can use the `emoji_rename.php` file from browser to get the data in the desired format. Right now the $emoji is already in the substituted form.
4. Change the next line in Emoji.php to     `protected $default_options = array(
        "emoji.path" => "Emoji/assets1/"
    );`
5. Now your class is ready to work.

# Documentation about extending the work.

In case we want to extend the class to other emojis,
1. Store the HTML in the folder (In the present case we have stored `emoji_cheat_sheet.htm`).
2. In `emoji_library_creator.sh`, change `mkdir -p assets1 || exit 1` to `mkdir -p assets2 || exit 1` or any other suitable name of folder.
3. In `emoji_cheat.php` function read_file, substitute 	`$input = file_get_contents("emoji_cheat_sheet.htm");` with `$input = file_get_contents("YourFileName.htm");`
4. In `emoji_cheat.php` function read_file, substitute 		`$reg = preg_grep('/data-src="graphics\/emojis\//',$array);` with suitable regex to catch your image links.
5. In `emoji_cheat.php` function trimming, substitute the trimming to remove any undesired data from the link.
6. In `emoji_rename.php` function emoji_names, substitute `$val = str_replace('http://www.emoji-cheat-sheet.com/graphics/emojis/','',$value); $val = str_replace('.png','',$val);` suitably to keep only the emoji name and remove the other unwanted parts of the link.
7. After making these changes, run `emoji_library_creator.sh`.

N.B. - This documentation about extending is not tested. If any bug arises, it has to be dealt with by developer.


