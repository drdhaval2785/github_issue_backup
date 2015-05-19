# What does this program do:
This command fetches the issues and comments from your github repository and stores it in a txt file for backup. It also displays it in an HTML file which looks similar to github environment.
e.g. [text file](http://drdhaval2785.github.io/github_issue_backup/sanskrit-lexicon/CORRECTIONS/2.txt) and [html file](http://drdhaval2785.github.io/github_issue_backup/sanskrit-lexicon/CORRECTIONS/html/2.html)

# Requirements
[PHP](http://php.net/)

[Git Bash](https://msysgit.github.io/) (or some linux platform)

[cURL](http://curl.haxx.se/) - N.B. Git has cURL inbuilt, so you won't need separate cURL if you use Git.


# User instructions:
* Step 1 : Open your commandline with cUrl installed (Git bash has cUrl inbuilt. I use Git bash for this purpose. Windows CMD also works well in my machine).
* Step 2 : cd to the directory where you have placed github_issue_backup.sh file
* Step 3 : In the commandline write `github_issue_backup.sh UserName RepoName IssueNumberToWhichDataIsToBeFetched`

e.g. `github_issue_backup.sh drdhaval2785 github_issue_backup 5` to fetch the issues in the current repository.

(Don't forget to change username, reponame, Issue number and Destination folder according to your need)

* Step 4 : Press enter to execute the command.
* Step 5 : The text data would be placed in username/reponame directory in the working directory and HTML data wouild be placed in username/reponame/html directory.

# Documentation for fetching data
* This method uses api.github.com with client_id and client_secret for backing up issues. 
* Generic codeline is `github_issue_backup.sh UserName RepoName IssueToWhichDataIsToBeFetched`

e.g. `github_issue_backup.sh drdhval2785 SanskritVerb 150`

* This program takes three arguments.
The first argument is the user/org name. The second argument is repo name. Third argument is the issue number till which you want to backup issues.

There are two lines in curl which needs a bit of explanation:

```curl 'https://api.github.com/repos/'$1/$2'/issues/'$a'?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' > $1/$2/$a.txt```

An explanation of the arguments passed in this curl line is in order.

state=all fetches all the issues (Available options are open/closed/all).

page=1 means the first page of the output.

per_page=1000 would mean the the output would have 1000 entries maximum. If your issue has more than 1000 comments, thie can be increased to suitable number.

client_id and client_secret are the OAuth tokens which we obtained from github API for authorization.

`>$1/$2/$a.txt` writes the data fetched by curl to the file which is numbered as per issue number e.g. drdhaval2785/SanskritVerb/1.txt

```curl 'https://api.github.com/repos/'$1/$2'/issues/'$a'/comments?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' >> $1/$2/$a.txt```
   
`>>$1/$2/$a.txt` appends the data fetched by curl to the file which is numbered as per issue number e.g. drdhaval2785/SanskritVerb/1.txt

# Documentation of parsing the data
The present parsing script is presentable.php which is a wrapper on [ParseDown](http://parsedown.org/) for better handling of Github Flavored Markdown and minor variations to handle how github API gives the data.
The environment in HTML is derived from the CSS files which github uses.