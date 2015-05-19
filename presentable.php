<?php
// Including Parsedown.php from https://github.com/erusev/parsedown
include 'Parsedown.php';
// Copying the stylesheets of github to the directory where we would be storing the HTML files.
copy("github-c486157afcc5f58155a921bc675afb08733fbaa8dcf39ac2104d3.css","$argv[1]/$argv[2]/html/github-c486157afcc5f58155a921bc675afb08733fbaa8dcf39ac2104d3.css");
copy("github2-da2e842cc3f0aaf33b727d0ef034243c12ab008fd09b24868b97.css","$argv[1]/$argv[2]/html/github2-da2e842cc3f0aaf33b727d0ef034243c12ab008fd09b24868b97.css");
// Header for proper display of UTF-8 and CSS links
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
</head>
<body class='logged_in  env-production windows vis-public'>
";

// The third argument of commandline.
$i=intval($argv[3]);
	// Calling function findissue to store data in usable format to stopgap.txt.
	findissue("$argv[1]/$argv[2]/$i.txt");
	// storing data in array from stopgap.txt.
	$input = file("stopgap.txt");
	// Creating html file by number of issue.
	$outfile = fopen("$argv[1]/$argv[2]/html/$i.html","w+");
	// putting header in HTML file
	fputs($outfile,$header);
	// in the .txt file the sixth line is the issue number
	$issuenumber = $input[5];
	// trimming $issuenumber
	$issuenumber = strip_useless($issuenumber);
	$issuenumber = strip_quote($issuenumber);
	foreach ($input as $value)
	{
		// Searching and putting the Title in HTML file
		if (strpos($value,'"title"')!==false)
		{
			$title = $value;
			// trimming
			$title = strip_useless($title);
			$title = strip_quote($title);
			fputs($outfile,"<h1 align='center'><a href='$issuenumber' target='_blank'>$title</h1><hr/>");
		}
		// Searching for username and storing as an array $usernameoutput
		elseif (strpos($value,'"login"')!==false)
		{
			$username = $value;
			// trimming
			$username = strip_useless($username);
			$username = strip_quote($username);
			$usernameoutput[]=$username;
		}
		// Searching for time of creation of issue/comment and storing as an array $timeoutput
		elseif (strpos($value,'"created_at"')!==false)
		{
			$time = $value;
			// trimming
			$time = strip_useless($time);
			$time = strip_quote($time);
			$time = time_link($time);
			$timeoutput[]=$time;
		}
		// Searching for body of issue/comment and storing as an array $bodyoutput.
		elseif (strpos($value,'"body"')!==false)
		{
			$body = $value;
			// trimming
			$body = strip_useless($body);
			$body = str_replace("\r\n"," \r\n",$body);
			// Some preprocessing before parsing down.
			$body = strip_quote_body($body);
			// Parsing down the markups by parsedown function (derived from parsedown.php)
			$body = parsedown($body);
			// Doing some minor changes to correct the output of parsedown.
			$body = post_parsedown($body);
			// trimming
			$body = strip_quote_body($body);
			$bodyoutput[]=$body;
		}
	}	
//fputs ($outfile,commentheader('drdhaval2785','2015-05-07T08:47:20'));
	for ($j=0;$j<count($usernameoutput);$j++)
	{
		// Putting the username and time in the heading of each issue / comment in HTML file
		fputs($outfile,commentheader($usernameoutput[$j],$timeoutput[$j]));
		// Putting the body of the issue / comment in HTML file
		fputs($outfile,commentbody($bodyoutput[$j]));
	}
// Setting back the arrays to null for use again in the loop.
$usernameoutput=array(); $timeoutput=array(); $bodyoutput=array();
// Putting the endings in HTML
fputs($outfile,"</body></html>");
// Closing the HTML file
fclose($outfile);
// Deleting the stopgap.txt file which was used as intermediate state file from .txt -> .html
unlink("stopgap.txt");



function strip_useless($text)
{
	$text = trim($text);
	// removing unnecessary data from txt file
	$text = str_replace(array('"url": ','"title": ','"body": ','"login": ','"html_url": ','"created_at": '),array('','','','','',''),$text);
	$text = trim($text);
	$text = str_replace('\r\n\r\n\r\n','\r\n',$text);
	$text = str_replace('\r\n\r\n','\r\n',$text);
	// parsedown.php didnt handle \r\n properly. So a patch is made
	$text = str_replace(array('\r\n'),array('
	~~~'),$text);
	return $text;
}
function strip_quote($text)
{
	$text = str_replace(array('"',',','&quot;'),array('','',''),$text);
	return $text;
}
function strip_quote_body($text)
{
	$text = trim($text);
	$text = preg_replace('/^(["])/','',$text);
	$text = preg_replace('/(["],?)$/','',$text);
	$text = str_replace('&quot;','',$text);
	return $text;
}
function time_link($text)
{
		$text = str_replace(array("T","Z"),array(" ",""),$text);
		return $text;
}
function parsedown($text)
{
	// sometimes users place line breaks after triple quotes. To overcome that issue this patch has been created.
	$text = preg_replace('/\r\n```\r\n([^`]*)\r\n```\r\n/', '\r\n```$1```\r\n', $text);
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
function post_parsedown($input)
{
	$input = preg_replace('/\r\n```\r\n([^`]*)\r\n```\r\n/', '\r\n```$1```\r\n', $input); // Treating the blob
	$split = explode('\r\n',$input);
	for ($i=0;$i<count($split);$i++)
	{
		$text = $split[$i];
		// Multiple underscores pending.
		$text = preg_replace('/!\[([^\]]*)\]\(([^\)]*)\)/', '<image src="$2"></image>', $text); // Capture
		$text = preg_replace('/\*\*([^~]*)\*\*/', '<b>$1</b>', $text); // bold
		$text = preg_replace('/\*([^~]*)\*/', '<i>$1</i>', $text); // italics
		$text = preg_replace('/^\*([^\*]*)$/', '<li>$1', $text); // unordered list
		$text = preg_replace('/^[0-9][.]([^\*]*)/', '<li>$1', $text); // unordered list 
		$text = str_replace('<br />','<br/>',$text); // removing space after br.
		$text = preg_replace('/@([^\W_ ]*)([\W_ ])/', '<strong><a href="https://github.com/$1" target="_blank">@$1</a></strong>$2', $text); // mentioning		
		$text = str_replace('<pre><code></code></pre>','',$text); // removing superfluous codeblocks
		$text = str_replace('<pre><code>~~~','<p>',$text); // removing superfluous codeblocks
		$text = str_replace('</code></pre>','</p>',$text); // removing superfluous codeblocks
		$text = preg_replace('/(~~~&gt;)([^~]*)(~~~)/','<blockquote>$2</blockquote>',$text);// blockquotes
		$text = str_replace('<pre><code class="language-and">','and',$text); // Sometimes the codeblocks having 'and' is not properly treated, because parsedown takes it as a language.
		$text = str_replace('&lt;','<',$text); // <
		$text = str_replace('&gt;','>',$text); // >
		$text = str_replace('~~~','<br/>',$text); // for browser display \r\n is converted to <br/>
		$text = str_replace('<p><p>','<p>',$text); // removed superfluous 
		$text = str_replace('</p></p>','</p>',$text); // removed superfluous
		$splitout[$i] = $text;
	}
	$output = implode('<br/>',$splitout); // Joining via <br/> for browser display
	return $output;
}

function findissue($filepath)
{
	// read the file e.g. 1.txt
	$data = file_get_contents($filepath);
	// separate the issue and comments.
	$issue_comment_separator = explode('BODY STARTS FROM HERE',$data);
	$issue = $issue_comment_separator[0];
	$comment = $issue_comment_separator[1];
	// The regex is to prevent split on basis of [ in case of captured images or github flavored URL linking.
	$split_issue = preg_split('/[^!][\[][^ ]/',$issue);
	// Exploding to get appropriate piece of information from .txt to put into stopgap.txt
	$closed_at=explode('"comments":',$split_issue[1]);
	$closed_by=explode('"closed_by":',$closed_at[1]);
	// storing the data in stopgap.txt
	file_put_contents('stopgap.txt',$split_issue[0].'['.$closed_by[0].$comment);
}

function commentheader($username,$time)
{
	// Copy pasted the github div class etc for heading of issue / comment
	$output = '<div class="comment previewable-edit timeline-comment js-comment js-task-list-container owner-comment current-user" data-body-version="3d69eb2502aec4738da37c0867d635da">
  <div class="timeline-comment-header ">
  <div class="timeline-comment-header-text">
    <strong>
      <a href="https://github.com/'.$username.'" class="author">'.$username.'</a>
    </strong>
    commented on 
    <a href="#issuecomment-99777162" class="timestamp">
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