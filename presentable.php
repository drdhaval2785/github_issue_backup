<?php
// Including Parsedown.php from https://github.com/erusev/parsedown
include 'Parsedown.php';
include 'Emoji\src\Emoji\Emoji.php';
// Copying the stylesheets of github to the directory where we would be storing the HTML files.
copy("github-c486157afcc5f58155a921bc675afb08733fbaa8dcf39ac2104d3.css","$argv[1]/$argv[2]/html/github-c486157afcc5f58155a921bc675afb08733fbaa8dcf39ac2104d3.css");
copy("github2-da2e842cc3f0aaf33b727d0ef034243c12ab008fd09b24868b97.css","$argv[1]/$argv[2]/html/github2-da2e842cc3f0aaf33b727d0ef034243c12ab008fd09b24868b97.css");
// Header for proper display of UTF-8, CSS links and javascript for syntax highlighting.
$header = "<!DOCTYPE html>
<html class='' lang='en'>
<head prefix='og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# object: http://ogp.me/ns/object# article: http://ogp.me/ns/article# profile: http://ogp.me/ns/profile#'>
<meta http-equiv='content-type' content='text/html; charset=UTF-8'>
<meta charset='utf-8'>
<meta http-equiv='X-UA-Compatible' content='IE=edge'>
<meta http-equiv='Content-Language' content='en'>
<title>$argv[1]/$argv[2]/$argv[3]</title>
<link href='github-c486157afcc5f58155a921bc675afb08733fbaa8dcf39ac2104d3.css' media='all' rel='stylesheet'>
<link href='github2-da2e842cc3f0aaf33b727d0ef034243c12ab008fd09b24868b97.css' media='all' rel='stylesheet'>
<meta http-equiv='x-pjax-version' content='4426702614c8182f33d1780ad1169662'>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shCore.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shAutoloader.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushAppleScript.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushAS3.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushBash.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushColdFusion.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushCpp.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushCSharp.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushCss.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushDelphi.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushDiff.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushErlang.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushGroovy.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushJava.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushJavaFX.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/syntaxhighlighter/scripts/shBrushJScript.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushPerl.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushPhp.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushPlain.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushPowerShell.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushPython.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushRuby.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushSass.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushScala.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushSql.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushVb.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shBrushXml.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shCore.js'></script>
	<script type='text/javascript' src='syntaxhighlighter/scripts/shLegacy.js'></script>
	<link type='text/css' rel='stylesheet' href='syntaxhighlighter/styles/shCoreDefault.css'/>
	<script type='text/javascript'>SyntaxHighlighter.all();</script>
</head>
<body class='logged_in  env-production windows vis-public'>
";
$argv1 = $argv[1];
$argv2 = $argv[2];

// The third argument of commandline.
$i=intval($argv[3]);
// Creating an HTML file to store the output.
$outfile = fopen("$argv[1]/$argv[2]/html/$i.html","w+");
// Putting the header inside HTML file.
fputs($outfile,$header);
// Reading from the .txt file of individual issue. UserName/RepoName/IssueNumber.txt format.
$in = file_get_contents("$argv[1]/$argv[2]/$i.txt");
// Using the separator 'BODY STARTS FROM HERE' to bifurcate the data in two parts. The first part is Issue. The second part is comments.
$in_separate = explode('BODY STARTS FROM HERE',$in);
// Issue details stored as an array.
$issue_details = json_decode($in_separate[0],true);
// Getting the details from json_decoded array.
$issuenumber = $issue_details["html_url"];
$title = $issue_details["title"];
// Putting title in the HTML file.
fputs($outfile,"<h1 align='center'><a href='$issuenumber' target='_blank'>$title</h1><hr/>");
// Getting the username who created the issue and time of creation of an issue.
$username = $issue_details["user"]["login"];
$time = $issue_details["created_at"];
// Trimming the time for comfortable display.
$time = str_replace(array("T","Z"),array(" ",""),$time);
// Getting the comment id.
$comment_id = $issue_details["id"];
// Putting the heading of an issue inside HTML.
fputs($outfile,issueheader($username,$time,$comment_id));
// Getting the body of an issue.
$body = $issue_details["body"];
// Parsing the body with ParseDown. https://github.com/erusev/parsedown/ for details.
$body = parsedown($body);
// Adding mentioning, issue number linking etc.
$body = github_flavor($body);
// syntax highlighting. See https://github.com/drdhaval2785/github_issue_backup/issues/19 and commit https://github.com/drdhaval2785/github_issue_backup/commit/7e7ce4d033c4cc4599f3a5cf8111d2212b0403b9
$body = syntax_highlight($body);
// emoji
$body = emoji_display($body);
// Putting the body in the HTML.
fputs($outfile,commentbody($body));

// Fetching details of comments in form of an array.	
$comment_details = json_decode($in_separate[1],true);
// Running the loop for all members of the array.
foreach ($comment_details as $value)
{
	// As explained above.
	$username = $value["user"]["login"];
	$time = $value["created_at"];
	$time = str_replace(array("T","Z"),array(" ",""),$time);
	$comment_id = $value["id"];
	fputs($outfile,commentheader($username,$time,$comment_id));
	$body = $value["body"];
	$body = parsedown($body);
	$body = github_flavor($body);
	$body = syntax_highlight($body);
	$body = emoji_display($body);
	fputs($outfile,commentbody($body));	
}
// Putting the endings in HTML
fputs($outfile,"</body></html>");
// Closing the HTML file
fclose($outfile);

	
	
function parsedown($text)
{
	// sometimes users place a space between ] and ( for linking in markup. To overcome that.
	$text = str_replace('] (','](',$text); // Links error correction
	// To overcome aberrent behaviour in case the parser places a \ before " of href
	$text = str_replace('href=\"','href="',$text);
	// Same as above
	$text = str_replace('\">','">',$text);
	// Calling parsedown class and parsing.
	$Parsedown = new Parsedown();
	return $Parsedown->text($text);
}
function github_flavor($text)
{
	global $argv1, $argv2;
	$text = preg_replace('/@([^\W_ ]*)([\W_ ])/', '<strong><a href="https://github.com/$1" target="_blank">@$1</a></strong>$2', $text); // mentioning
	$text = preg_replace('/[ ]#([0-9]*)[ ]/'," <a href='$1.html' target='_blank'>#$1</a> ",$text);
	$text = preg_replace('/^#([0-9]*)[ ]/',"<a href='$1.html' target='_blank'>#$1</a> ",$text);
	return $text;
}
function syntax_highlight($text)
{
	$text = str_replace('<pre><code class="language-','<pre class="brush: ',$text);
//	$text = str_replace('</code></pre>','</pre>',$text);
	return $text;
}
function emoji_display($text)
{
	$emoji = new Emoji();
	$output = $emoji->render($text);
	$output = str_replace('<img src="./assets/','<img src="Emoji/assets/',$output);
	return $output;
}

function commentheader($username,$time,$comment_id)
{
	// Copy pasted the github div class etc for heading of issue / comment
	$output = '<div id="#issuecomment-'.$comment_id.'" class="comment previewable-edit timeline-comment js-comment js-task-list-container owner-comment current-user" data-body-version="3d69eb2502aec4738da37c0867d635da">
  <div class="timeline-comment-header ">
  <div class="timeline-comment-header-text">
    <strong>
      <a href="https://github.com/'.$username.'" class="author">'.$username.'</a>
    </strong>
    commented on 
    <a href="#issuecomment-'.$comment_id.'" class="timestamp">
      <time>'.$time.'</time>
    </a>
  </div>
</div>';
return $output;	
}
function issueheader($username,$time,$comment_id)
{
	// Copy pasted the github div class etc for heading of issue / comment
	$output = '<div id="#issue-'.$comment_id.'" class="comment previewable-edit timeline-comment js-comment js-task-list-container owner-comment current-user" data-body-version="3d69eb2502aec4738da37c0867d635da">
  <div class="timeline-comment-header ">
  <div class="timeline-comment-header-text">
    <strong>
      <a href="https://github.com/'.$username.'" class="author">'.$username.'</a>
    </strong>
    commented on 
    <a href="#issue-'.$comment_id.'" class="timestamp">
      <time>'.$time.'</time>
    </a>
  </div>
</div>';
return $output;	
}

function commentbody($text)
{
	// Copy pasted the github div class etc for body of issue / comment
	$output = '<div class="edit-comment-hide">
      <div class="comment-body markdown-body markdown-format js-comment-body">
          '.$text.'
  </div>
  </div>
  </div>
  <br/>
';
return $output;
}

?>