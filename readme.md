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
N.B. - The video was made for version 1.0.0 which took three arguments. Version 1.1.0 takes five arguments as explained below. The essense remains the same.

* Step 1 : Open your commandline with cURL installed (Git bash has cURL inbuilt. I use Git bash for this purpose. Windows CMD also works well in my machine).
* Step 2 : cd to the directory where you have placed github_issue_backup.sh file
* Step 3 : In the commandline write `github_issue_backup.sh UserName RepoName IssueNumber [ OutputFolder | -p ] [ -l | -f ] [ -y | -n ]` e.g. `github_issue_backup.sh drdhval2785 SanskritVerb 1:10,13,15 e:/backup -l -y` to fetch the issues in the current repository. (Don't forget to change username, reponame, Issue number and Destination folder according to your need)
* Step 4 : Press enter to execute the command.
* Step 5 : By default, text data would be placed in username/reponame directory in the working directory and HTML data would be placed in username/reponame/html directory. If you have selected any OutputFolder, the data would be stored there.

# Documentation for fetching data
* This method uses api.github.com with client_id and client_secret for backing up issues. 
* Generic codeline is `github_issue_backup.sh UserName RepoName IssueNumber [ OutputFolder | -p ] [ -l | -f ] [ -y | -n ]`

e.g. `github_issue_backup.sh drdhval2785 SanskritVerb 1:10,13,15 e:/backup -l -y`

## This program takes 6 arguments.
1. The first argument is the user/org name. e.g. drdhaval2785
2. The second argument is repo name. e.g. SanskritVerb. If you want to backup all the repository of a user / organization, write `-a` to fetch all the repositories.
3. Third argument is the issue number. You can enter the issue numbers separated by a comma e.g. `1,2,3,15,18`. You can also write ranges separated by `:` e.g. `1:10,15,20`. If you want to backup all the issues write `-a` to download ALL issues of the repository.
4. Fourth argument is Output Folder (in case you want to store the output somewhere other than the working directory). If you want to get the output in the working directory itself, write `-p` i.e. parent. 
5. Fifth argument is the mode. `-l` would do limited version i.e. Syntax Highlighting and Emoji support would not be there. `-f` would give full support (but at the cost of 6 MB odd additional libraries being pasted in each directory).
6. Sixth argument is for downloading images. `-y` would download the images and `-n` would not download the images. This argument is optional. If it is not set, it would download images.

## Examples of usage

1. `github_issue_backup.sh drdhaval2785 SanskritVerb 1:10,13,15 -p -l` would fetch issues 1 to 10, 13 and 15 of drdhaval2785/SanskritVerb repository without Syntax Highlighting and Emoji, and store it in working directory.
2. `github_issue_backup.sh drdhaval2785 SanskritVerb 1:10,13,15 -p -f` would fetch issues 1 to 10, 13 and 15 of drdhaval2785/SanskritVerb repository with Syntax Highlighting and Emoji, and store it in working directory.
3. `github_issue_backup.sh drdhaval2785 SanskritVerb 1:10,13,15 e:/output -l` would fetch issues 1 to 10, 13 and 15 of drdhaval2785/SanskritVerb repository without Syntax Highlighting and Emoji, and store it in e:/output directory.
4. `github_issue_backup.sh drdhaval2785 SanskritVerb 1:10,13,15 e:/output -f` would fetch issues 1 to 10, 13 and 15 of drdhaval2785/SanskritVerb repository with Syntax Highlighting and Emoji, and store it in e:/output directory.
5. `github_issue_backup.sh drdhaval2785 SanskritVerb -a e:/output -l` would fetch all issues of drdhaval2785/SanskritVerb repository without Syntax Highlighting and Emoji, and store it in e:/output directory.
6. `github_issue_backup.sh drdhaval2785 SanskritVerb -a e:/output -f` would fetch all issues of drdhaval2785/SanskritVerb repository with Syntax Highlighting and Emoji, and store it in e:/output directory.
7. `github_issue_backup.sh drdhaval2785 -a -a e:/output -l` would fetch all issues of all repositories of user drdhaval2785 without Syntax Highlighting and Emoji, and store it in e:/output directory.
8. `github_issue_backup.sh drdhaval2785 -a -a e:/output -f` would fetch all issues of all repositories of user drdhaval2785 with Syntax Highlighting and Emoji, and store it in e:/output directory.
9. `github_issue_backup.sh drdhaval2785 -a -a -p -l` would fetch all issues of all repositories of user drdhaval2785 without Syntax Highlighting and Emoji, and store it in working directory.
10. `github_issue_backup.sh drdhaval2785 -a -a -p -f` would fetch all issues of all repositories of user drdhaval2785 with Syntax Highlighting and Emoji, and store it in working directory.

To fetch the data of all issues of all repositories of any given user / organization, option 10 is the safest one to work with (though a bit costly on space).

In any of the above examples let's say 10th example, if the user doesn't want to download the images (to decrease backup size) he can add `-n` at the end like `github_issue_backup.sh drdhaval2785 -a -a -p -f -n`.

## cURL explanation
There are two lines in cURL which need a bit of explanation:

```curl 'https://api.github.com/repos/'$1/$2'/issues/'$a'?state=all&page=1&per_page=1000&client_id=1dd1dddcb68d6148c249&client_secret=7577e3bd5cb5ad20bea86430a8ed5a29df5fa455' > $1/$2/$a.txt```

An explanation of the arguments passed in this cURL line is in order.

state=all fetches all the issues (Available options are open/closed/all).

`page=1` means the first page of the output.

`per_page=1000` would mean the the output would have 1000 entries maximum. If your issue has more than 1000 comments, this can be increased to suitable number.

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
4. Version 1.0.3 launched on 29 May 2015 with cURL progress meter silenced.
5. Version 1.0.4 launched on 29 May 2015 with facility to backup all the repos of a user / organization. See issue 24.
6. Version 1.1.0 launched on 30 May 2015 with facility to backup specific issue numbers rather than whole data.
7. Version 1.1.1 launched on 1 June 2014 with facility to download / not to download images.
