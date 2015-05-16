# This method uses api.github.com with client_id and client_secret for backing up issues. This program takes three arguments.
# Typical command looks like the below
# github_issue_backup.sh drdhval2785 SanskritVerb 150
# The first argument is the user/org name. The second argument is repo name. Third argument is the number till which you want to backup issues.
# Generic codeline is github_issue_backup.sh UserName RepoName IssueToWhichDataIsToBeFetched
# Define a timestamp function
timestamp() {
  date +"%Y-%m-%d_%H-%M-%S"
}
echo started execution at 
timestamp
echo creating directory $1/$2
mkdir -p $1/$2/html || exit 1
a=`expr 1`
# making iteration till the third argument (issue number till which the user wants to fetch the issues).
while [ $a -lt `expr $3 + 1` ]
do
	echo started issue number $a
	echo printing issue $a to $1/$2/$a.txt
   # $1 is the username, $2 is the repositoryname, $a is the issue number.
   # An explanation of the arguments passed in this curl line is in order.
   # state=all fetches all the issues (Available options are open/closed/all).
   # page=1 means the first page of the output.
   # per_page=1000 would mean the the output would have 1000 entries maximum. If your issue has more than 1000 comments, thie can be increased to suitable number.
   # client_id and client_secret are the OAuth tokens which we obtained from github API for authorization.
   # >$1/$2/$a.txt writes the data fetched by curl to the file which is numbered as per issue number e.g. drdhaval2785/SanskritVerb/1.txt
   # This completes the noting of issue in our file.
	curl 'https://api.github.com/repos/'$1/$2'/issues/'$a'?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' > $1/$2/$a.txt
	echo Appending comments on issue $a to $1/$2/$a.txt
	echo BODY STARTS FROM HERE >> $1/$2/$a.txt 
   # >>$1/$2/$a.txt appends the data fetched by curl to the file which is numbered as per issue number e.g. drdhaval2785/SanskritVerb/1.txt
   # This completes the noting of comments on a particular issue in our file.
	curl 'https://api.github.com/repos/'$1/$2'/issues/'$a'/comments?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' >> $1/$2/$a.txt
	# At the end of this activity, the data in 1.txt would be of the format issue+comments thereon.
	# incrementing for the next iteration.
	echo preparing $a.html for display
	php presentable.php $1 $2 $a
	echo completed issue number $a
	a=`expr $a + 1`
done
echo completed execution at 
timestamp