# Trial code to get issue number from repo name
curl -s -S 'https://api.github.com/repos/'$1/$2'/issues?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' > issue.txt
x=$(php get_issue_number.php);
echo $x
github_issue_backup.sh $1 $2 $x -p -f
rm issue.txt
