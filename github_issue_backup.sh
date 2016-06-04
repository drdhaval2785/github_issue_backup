# github_issue_backup version 1.2.0, Date 04 June 2016 (https://github.com/drdhaval2785/github_issue_backup)
# Author - Dr. Dhaval Patel (http://youtu.be/kzsPG5vl95w) (drdhaval2785@gmail.com)
# This method uses api.github.com with client_id and client_secret for backing up issues. This program takes six arguments.
# Generic codeline is github_issue_backup.sh UserName RepoName IssueNumber [ OutputFolder | -p ] [ -l | -f ] [ -y | -n ]
# See readme.md for details on the arguments
# Typical command looks like the below
# github_issue_backup.sh drdhval2785 SanskritVerb 1:10,13,15 e:/backup -l -y
# or
# github_issue_backup.sh drdhaval2785 SanskritVerb -a -p -f -y

# Define a timestamp function
timestamp() {
  date +"%Y-%m-%d_%H-%M-%S"
}

# Test whether the user exists, and exiting if it doesn't.
curl -s 'https://api.github.com/users/'$1'?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' > user.txt
z=$(php test_user_existence.php)
if [ "$z" == "EXIT" ]
then
echo "User doesn't exist. Failure."
echo
exit
fi
rm -r user.txt

# Test whether the repository exists, and exiting if it doesn't.
# We presume that the repo names would not be more than 100 (a reasonable assumption). 
curl -s -S 'https://api.github.com/users/'$1'/repos?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' > repo.txt
w=$(php test_repo_existence.php)
if [ "$w" == "EXIT" ]
then
echo "Repository doesn't exist. Failure."
echo
exit
fi


# Getting the names of repositories of a particular user, if the user has passed argument "-a" to fetch issues of all his repositories.
# Default if the user has given repository as argument 2.
y=$2
if [ $2 == "-a" ]
then
# in this php function we echo the names of repositories with a space in between e.g. `abc def ghi` etc.
y=$(php get_repo_name.php);
if [ $y == "EXIT" ]
then
echo "Repository doesn't exist. Failure."
echo
exit
fi
# Remembered the repo names in $y. Therefore, no need to retain the file now.
rm -r repo.txt
fi

# For each entry in $y (i.e. repository names), we do the following code.
for yy in $y;
do
	echo Processing $1/$yy repository
	mkdir -p $1/$yy/html/images || exit 1
	mkdir -p $1/$yy/html/files || exit 1
	mkdir -p $1/$yy/header || exit 1
	echo Created directory $1/$yy.
	echo Created directory $1/$yy. > $1/$yy/log.txt
	timestamp >> $1/$yy/log.txt
	# Analyse the timelog.txt file to get the latest time when the repository was updated.
	lastupdatetime=$(php lastupdatetime.php $1 $yy)

	# Make a list of last page in github API for issues
	curl -sI 'https://api.github.com/repos/'$1/$yy'/issues?state=all&since='$lastupdatetime'&per_page=100&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' | grep '^Link' > $1/$yy/header/head.txt
	echo Gathered total number of pages to download.
	echo Gathered total number of pages to download. > $1/$yy/log.txt
	# Get the number of the last page (to navigate)
	lastnum=$(php getpagenumber.php $1 $yy)

	echo Started fetching data from $1/$yy repository,
	timestamp
	echo Started fetching data from $1/$yy repository. >> $1/$yy/log.txt 
	timestamp >> $1/$yy/log.txt

	# Copying the emoji and syntaxhighlighter support if the user has asked for it.
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

	# Making an iteration through all pages (github API allows only 100 entries per page now)
	b=`expr 1`
	for b in $lastnum;
	do
		# Getting the issue number of a particular repository, if the user has passed argument "-a" to fetch all issues.
		curl -s -S 'https://api.github.com/repos/'$1/$yy'/issues?state=all&since='$lastupdatetime'&page='$b'&per_page=100&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' > issue.txt
		x=$(php get_issue_number.php $3 $1 $yy);
		#echo $x
		if [ "$x" == "EXIT" ]
		then
		echo "Directory already up to date. Nothing to update."
		echo
		break
		fi
		if [ "$x" == "EXIT1" ]
		then
		echo "Directory does not have any issue. Nothing to update."
		echo
		break
		fi
		
		a=`expr 1`
		# making iteration till the third argument (issue number till which the user wants to fetch the issues).
		for a in $x;
		do
			echo issue $a
			echo issue $a >> $1/$yy/log.txt
			timestamp >> $1/$yy/log.txt
		   # $1 is the username, $yy is the repositoryname, $a is the issue number.
		   # An explanation of the arguments passed in this curl line is in order.
		   # state=all fetches all the issues (Available options are open/closed/all).
		   # page=1 means the first page of the output.
		   # per_page=1000 would mean the the output would have 1000 entries maximum. If your issue has more than 1000 comments, thie can be increased to suitable number.
		   # client_id and client_secret are the OAuth tokens which we obtained from github API for authorization.
		   # >$1/$2/$a.txt writes the data fetched by curl to the file which is numbered as per issue number e.g. drdhaval2785/SanskritVerb/1.txt
		   # This completes the noting of issue in our file.
			curl -s -S 'https://api.github.com/repos/'$1/$yy'/issues/'$a'?state=all&page=1&per_page=100&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' > $1/$yy/$a.txt
			# This is the separator by which we will separate the issue and comments in presentable.php.
			echo BODY STARTS FROM HERE >> $1/$yy/$a.txt 
		   # >>$1/$2/$a.txt appends the data fetched by curl to the file which is numbered as per issue number e.g. drdhaval2785/SanskritVerb/1.txt
		   # This completes the noting of comments on a particular issue in our file.
			curl -s -S 'https://api.github.com/repos/'$1/$yy'/issues/'$a'/comments?state=all&page=1&per_page=100&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' >> $1/$yy/$a.txt
			# At the end of this activity, the data in 1.txt would be of the format issue+comments thereon.
			# incrementing for the next iteration.

			# Preparing HTML for display.
			php presentable.php $1 $yy $a $5
		done
		if [ "$6" != "-n" ]
		then
			# Creating a local copy of images in issue.
			php image_links.php $1 $yy $x
			echo Fetched links of images.
			echo Fetched links of images. >> $1/$yy/log.txt

			# Creating a local copy of files in issue.
			php filelinks.php $1 $yy $x
			echo Fetched links of files.
			echo Fetched links of files. >> $1/$yy/log.txt
			timestamp >> $1/$yy/log.txt

			# Fetching the images and storing in $1/$2/html/images/ folder.
			echo Please wait. Fetching all images and storing them in $1/$yy/html/images directory. It may take some time.
			while read line # Read a line
			do
				fname=$line
				echo Fetching $line
				curl -s -S $line > $1/$yy/html/images/"${fname##*/}"
			done < "imagelinks.txt"
			echo Fetched images. >> $1/$yy/log.txt
			timestamp >> $1/$yy/log.txt

			# Fetching the files and storing in $1/$2/html/files/ folder.
			echo Please wait. Fetching all files and storing them in $1/$yy/html/files directory. It may take some time.
			while read line # Read a line
			do
				echo Fetching $line
				fname=$line
				curl -s -S -L $line > $1/$yy/html/files/"${fname##*/}"
			done < "filelinks.txt"
			echo Fetched files. >> $1/$yy/log.txt
			timestamp >> $1/$yy/log.txt

			# substituting the image links with local links
			php substitute_images.php $1 $yy $x
			echo Substituted the image links with local links.
			echo Substituted the image links with local links. >> $1/$yy/log.txt
			timestamp >> $1/$yy/log.txt

			# substituting the file links with local links
			php substitute_files.php $1 $yy $x
			echo Substituted the file links with local links.
			echo Substituted the file links with local links. >> $1/$yy/log.txt
			timestamp >> $1/$yy/log.txt
		fi
		# Create index of issues based on filenames. Output in username/reponame/html/index.html
		ls $1/$yy/[0-9]*.txt | grep -Po '\b[0-9]*\b' > $1/$yy/index.txt
		echo Creating index of issues.
		echo Creating index of issues >> $1/$yy/log.txt
		php index_creator.php $1 $yy

		# Update timelog.txt
		echo Updated timelog.txt after successful completion.
		echo Updated timelog.txt >> $1/$yy/log.txt
		timestamp >> $1/$yy/log.txt
		php timelogupdater.php

		# Completion message
		echo completed execution at 
		timestamp
		echo Completed execution >> $1/$yy/log.txt
		timestamp >> $1/$yy/log.txt

		if [ "$4" != "-p" ]
		then
		mkdir -p $4/$1
		mv -f $1/$yy $4/$1
		rm -r $1
		echo Moved directory $1/$yy to $4/$1/$yy. >> $4/$1/$yy/log.txt
		timestamp >> $4/$1/$yy/log.txt
		fi

		# Removing intermediate files.
		rm issue.txt
		rm imagelinks.txt
		rm filelinks.txt
		
		echo 
		echo
	done
done
