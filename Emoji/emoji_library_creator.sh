# Fetching the images and storing in $1/$2/html/images/ folder.
echo Making directory assets1
mkdir -p assets1 || exit 1
echo Creating the lists of weblinks to various emojis of http://www.emoji-cheat-sheet.com/
php emoji_cheat.php
echo Fetching the images and storing in assets1 folder.
i=0
while read line # Read a line
do
	curl $line > assets1/$i.png
	i=$(($i + 1))
done < "emojilinks.txt"
echo Renaming the emojis back to their original names.
php emoji_rename.php
echo Completed execution
