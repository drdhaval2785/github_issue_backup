# What does this program do:
Ever wanted to store all the discussion on your github repositories for viewing offline or for backup ?
If yes, this is the tool for you !
This command fetches the issues and comments from your github repository and stores it in a `.txt` file for backup. It also displays it in an HTML file which looks similar to GitHub environment,
e.g. [text file](http://drdhaval2785.github.io/github_issue_backup/sanskrit-lexicon/CORRECTIONS/2.txt) and [html file](http://drdhaval2785.github.io/github_issue_backup/sanskrit-lexicon/CORRECTIONS/html/2.html).

# Requirements
1. [PHP](http://php.net/) - Setup your [PHP for commandline](https://www.youtube.com/watch?v=neBVQBL_2P0)

2. [Git Bash](https://msysgit.github.io/) (or any Linux platform)

3. [cURL](http://curl.haxx.se/) 

Note 1 - Git has cURL inbuilt, so you won't need separate cURL if you use Git. 

Note 2 - If you have installed Git, your windows CMD.exe also become able to execute this program. (So, you can stick to your favourite commandline).


# User instructions:
[Demonstration video](http://youtu.be/kzsPG5vl95w).
N.B. - The video was made for version 1.0.0 which took three arguments. Version 1.0.1 takes five arguments as explained below. The essense remains the same.

* Step 1 : Open your commandline with cURL installed (Git bash has cURL inbuilt. I use Git bash for this purpose. Windows CMD also works well in my machine).
* Step 2 : cd to the directory where you have placed github_issue_backup.sh file
* Step 3 : In the commandline write `github_issue_backup.sh UserName RepoName IssueNumberToWhichDataIsToBeFetched [OutputFolder | -p ] [ -l | -f ]` e.g. `github_issue_backup.sh drdhaval2785 github_issue_backup 5 e:/backup -f` to fetch the issues in the current repository. (Don't forget to change username, reponame, Issue number and Destination folder according to your need)
* Step 4 : Press enter to execute the command.
* Step 5 : By default, text data would be placed in username/reponame directory in the working directory and HTML data would be placed in username/reponame/html directory. If you have selected any OutputFolder, the data would be stored there.

# Documentation for fetching data
* This method uses api.github.com with client_id and client_secret for backing up issues. 
* Generic codeline is `github_issue_backup.sh UserName RepoName IssueNumberToWhichDataIsToBeFetched [OutputFolder | -p ] [-l | -f]`

e.g. `github_issue_backup.sh drdhval2785 SanskritVerb 150 e:/backup -l`

## This program takes 5 arguments.
1. The first argument is the user/org name. 
2. The second argument is repo name. 
3. Third argument is the issue number till which you want to backup issues. 
4. Fourth argument is Output Folder (in case you want to store the output somewhere other than the working directory). If you want to get the output in the working directory itself, write `-p` i.e. parent. 
5. Fifth argument is the mode. `-l` would do limited version i.e. Syntax Highlighting and Emoji support would not be there. `-f` would give full support (but at the cost of 6 MB odd additional libraries being pasted).

There are two lines in cURL which need a bit of explanation:

```curl 'https://api.github.com/repos/'$1/$2'/issues/'$a'?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' > $1/$2/$a.txt```

An explanation of the arguments passed in this cURL line is in order.

state=all fetches all the issues (Available options are open/closed/all).

`page=1` means the first page of the output.

`per_page=1000` would mean the the output would have 1000 entries maximum. If your issue has more than 1000 comments, this can be increased to suitable number.

`client_id` and `client_secret` are the OAuth tokens which we obtained from github API for authorization.

`>$1/$2/$a.txt` writes the data fetched by curl to the file which is numbered as per issue number e.g. drdhaval2785/SanskritVerb/1.txt

```curl 'https://api.github.com/repos/'$1/$2'/issues/'$a'/comments?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' >> $1/$2/$a.txt```
   
`>>$1/$2/$a.txt` appends the data fetched by cURL to the file which is numbered as per issue number e.g. drdhaval2785/SanskritVerb/1.txt

# Acknowledgements
1. The present parsing script is [presentable.php](https://github.com/drdhaval2785/github_issue_backup/blob/master/presentable.php) which is a wrapper on [ParseDown](http://parsedown.org/) for better handling of Github Flavored Markdown and minor variations to handle how github API gives the data.
2. The environment in HTML is derived from the CSS files which github uses - [CSS1](https://github.com/drdhaval2785/github_issue_backup/blob/master/github-c486157afcc5f58155a921bc675afb08733fbaa8dcf39ac2104d3.css) and [CSS2](https://github.com/drdhaval2785/github_issue_backup/blob/master/github2-da2e842cc3f0aaf33b727d0ef034243c12ab008fd09b24868b97.css)
3. The syntax highlighting is taken from [SyntaxHighlighter](http://alexgorbatchev.com/SyntaxHighlighter/download/).

# Changelog
1. Version 1.0.0 launched on 24 May 2015.
2. Version 1.0.1 launched on 29 May 2015 with output folder and mode arguments.
3. Version 1.0.2 launched on 29 May 2015 with logfile.
