# github_issue_backup version 1.0.0, Date 24 May 2015 (https://github.com/drdhaval2785/github_issue_backup)
# Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
# This method uses api.github.com with client_id and client_secret for backing up issues. This program takes three arguments.
# Typical command looks like the below
# github_issue_backup.sh drdhval2785 SanskritVerb 150
# The first argument is the user/org name. The second argument is repo name. Third argument is the number till which you want to backup issues.
# Generic codeline is github_issue_backup.sh UserName RepoName IssueToWhichDataIsToBeFetched
# Define a timestamp function
timestamp() {
  date +"%Y-%m-%d_%H-%M-%S"
}

y=$2
# Getting the issue number of a particular repository, if the user has passed argument "-a" to fetch all issues.
if [ $2 == "-a" ]
then
curl -s -S 'https://api.github.com/users/'$1'/repos?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' > repo.txt
y=$(php get_repo_name.php);
rm repo.txt
fi

for yy in $y;
do
	echo Processing $1/$yy repository
	mkdir -p $1/$yy/html/images || exit 1
	echo Created directory $1/$yy.
	echo Created directory $1/$yy. > $1/$yy/log.txt
	timestamp >> $1/$yy/log.txt

	echo Started fetching data from $1/$yy repository,
	timestamp
	echo Started fetching data from $1/$yy repository. >> $1/$yy/log.txt 
	timestamp >> $1/$yy/log.txt

	if [ "$5" != "-l" ]
	then
	# copying the necessary code for syntax highlighting. See http://alexgorbatchev.com/SyntaxHighlighter/download/ for the downloaded folder.
	cp -r syntaxhighlighter $1/$yy/html
	echo Copied syntaxhighlighter folder to $1/$yy folder. >> $1/$yy/log.txt
	timestamp >> $1/$yy/log.txt
	# copying the necessary code for Emojis. See https://github.com/drdhaval2785/github_issue_backup/issues/11 and https://github.com/chobie/Emoji for details.
	cp -r Emoji $1/$yy/html
	echo Copied Emoji folder to $1/$yy folder. >> $1/$yy/log.txt
	timestamp >> $1/$yy/log.txt
	fi

	x=$3
	# Getting the issue number of a particular repository, if the user has passed argument "-a" to fetch all issues.
	if [ $3 == "-a" ]
	then
	curl -s -S 'https://api.github.com/repos/'$1/$yy'/issues?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' > issue.txt
	x=$(php get_issue_number.php);
	rm issue.txt
	fi
	echo $x
	a=`expr 1`
	# making iteration till the third argument (issue number till which the user wants to fetch the issues).
	while [ $a -lt `expr $x + 1` ]
	do
		echo Started issue number $a.
		echo Started issue number $a. >> $1/$yy/log.txt
		timestamp >> $1/$yy/log.txt
		echo Printing issue $a to $1/$yy/$a.txt.
	   # $1 is the username, $2 is the repositoryname, $a is the issue number.
	   # An explanation of the arguments passed in this curl line is in order.
	   # state=all fetches all the issues (Available options are open/closed/all).
	   # page=1 means the first page of the output.
	   # per_page=1000 would mean the the output would have 1000 entries maximum. If your issue has more than 1000 comments, thie can be increased to suitable number.
	   # client_id and client_secret are the OAuth tokens which we obtained from github API for authorization.
	   # >$1/$2/$a.txt writes the data fetched by curl to the file which is numbered as per issue number e.g. drdhaval2785/SanskritVerb/1.txt
	   # This completes the noting of issue in our file.
		curl -s -S 'https://api.github.com/repos/'$1/$yy'/issues/'$a'?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' > $1/$yy/$a.txt
		echo Appending comments on issue $a to $1/$yy/$a.txt.
		# This is the separator by which we will separate the issue and comments in presentable.php.
		echo BODY STARTS FROM HERE >> $1/$yy/$a.txt 
	   # >>$1/$2/$a.txt appends the data fetched by curl to the file which is numbered as per issue number e.g. drdhaval2785/SanskritVerb/1.txt
	   # This completes the noting of comments on a particular issue in our file.
		curl -s -S 'https://api.github.com/repos/'$1/$yy'/issues/'$a'/comments?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' >> $1/$yy/$a.txt
		# At the end of this activity, the data in 1.txt would be of the format issue+comments thereon.
		# incrementing for the next iteration.
		echo preparing $a.html for display.
		php presentable.php $1 $yy $a $5 # Trying to suppress the emojis / syntaxhighlighter optionally to decrease the size of folder. Default is -f = FULL. Otherwise he may enter -l as fourth argument.
		echo Completed issue number $a.
		echo Completed issue number $a. >> $1/$yy/log.txt
		timestamp >> $1/$yy/log.txt
		a=`expr $a + 1`
	done
	echo Fetched links of images.
	php image_links.php $1 $yy $x
	echo Fetched links of images. >> $1/$yy/log.txt
	timestamp >> $1/$yy/log.txt
	# Fetching the images and storing in $1/$2/html/images/ folder.
	echo Fetching the images and storing in $1/$yy/html/images/ folder.
	i=0
	while read line # Read a line
	do
		curl -s -S $line > $1/$yy/html/images/$i.png
		i=$(($i + 1))
	done < "imagelinks.txt"
	echo Fetched images. >> $1/$yy/log.txt
	timestamp >> $1/$yy/log.txt
	# substituting the image links with local links
	php substitute_images.php $1 $yy $x
	echo Substituted the image links with local links.
	echo Substituted the image links with local links. >> $1/$yy/log.txt
	timestamp >> $1/$yy/log.txt
	echo Completed execution >> $1/$yy/log.txt
	timestamp >> $1/$yy/log.txt
	if [ "$4" != "-p" ]
	then
	rm -r $4/$1/$yy
	mkdir -p $4/$1 || exit 1
	mv -f $1/$yy $4/$1
	rm -r $1
	echo Moved directory $1/$yy to $4/$1/$yy. >> $4/$1/$yy/log.txt
	timestamp >> $4/$1/$yy/log.txt
	fi
	
	echo
	echo 
done

echo completed execution at 
timestamp
