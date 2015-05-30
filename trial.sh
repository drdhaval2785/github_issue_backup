x=$1
# Getting the issue number of a particular repository, if the user has passed argument "-a" to fetch all issues.
curl -s -S 'https://api.github.com/repos/drdhaval2785/github_issue_backup/issues?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' > issue.txt
x=$(php get_issue_number.php $x);
rm issue.txt
echo $x
