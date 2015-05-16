<?php
include 'Parsedown.php';
//mkdir("$argv[1]/$argv[2]/html/");
// Copying the stylesheets of github
copy("github-c486157afcc5f58155a921bc675afb08733fbaa8dcf39ac2104d3.css","$argv[1]/$argv[2]/html/github-c486157afcc5f58155a921bc675afb08733fbaa8dcf39ac2104d3.css");
copy("github2-da2e842cc3f0aaf33b727d0ef034243c12ab008fd09b24868b97.css","$argv[1]/$argv[2]/html/github2-da2e842cc3f0aaf33b727d0ef034243c12ab008fd09b24868b97.css");
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

$i=intval($argv[3]);
	findissue("$argv[1]/$argv[2]/$i.txt");
	$input = file("stopgap.txt");
	$outfile = fopen("$argv[1]/$argv[2]/html/$i.html","w+");
	fputs($outfile,$header);
	$issuenumber = $input[5];
	$issuenumber = strip_useless($issuenumber);
	$issuenumber = strip_quote($issuenumber);
	foreach ($input as $value)
	{
		if (strpos($value,'"title"')!==false)
		{
			$title = $value;
			$title = strip_useless($title);
			$title = strip_quote($title);
			fputs($outfile,"<h1 align='center'><a href='$issuenumber' target='_blank'>$title</h1><hr/>");
		}
		elseif (strpos($value,'"login"')!==false)
		{
			$username = $value;
			$username = strip_useless($username);
			$username = strip_quote($username);
			$usernameoutput[]=$username;
		}
		elseif (strpos($value,'"created_at"')!==false)
		{
			$time = $value;
			$time = strip_useless($time);
			$time = strip_quote($time);
			$time = time_link($time);
			$timeoutput[]=$time;
		}
		elseif (strpos($value,'"body"')!==false)
		{
			$body = $value;
			$body = strip_useless($body);
			$body = str_replace("\r\n"," \r\n",$body);
			$body = strip_quote_body($body);
			$body = parsedown($body);
			$body = post_parsedown($body);
			$body = strip_quote_body($body);
			$bodyoutput[]=$body;
		}
	}	
//fputs ($outfile,commentheader('drdhaval2785','2015-05-07T08:47:20'));
	for ($j=0;$j<count($usernameoutput);$j++)
	{
		fputs($outfile,commentheader($usernameoutput[$j],$timeoutput[$j]));
		fputs($outfile,commentbody($bodyoutput[$j]));
	}
$usernameoutput=array(); $timeoutput=array(); $bodyoutput=array();
fputs($outfile,"</body></html>");
fclose($outfile);
unlink("stopgap.txt");

function strip_useless($text)
{
	$text = trim($text);
	$text = str_replace(array('"url": ','"title": ','"body": ','"login": ','"html_url": ','"created_at": '),array('','','','','',''),$text);
	$text = trim($text);
	$text = str_replace('\r\n\r\n\r\n','\r\n',$text);
	$text = str_replace('\r\n\r\n','\r\n',$text);
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
//	$text = str_replace(array('"',',','&quot;'),array('','',''),$text);
	return $text;
}
function make_link($text)
{
		$text = preg_replace('/https?:\/\/[^\s"<>]+/', '<a href="$0" target="_blank">$0</a>', $text);
		return $text;
}
function user_link($text)
{
		$text = "https://github.com/$text";
		return $text;
}
function time_link($text)
{
		$text = str_replace(array("T","Z"),array(" ",""),$text);
		return $text;
}
function parsedown($text)
{
	$text = preg_replace('/\r\n```\r\n([^`]*)\r\n```\r\n/', '\r\n```$1```\r\n', $text); // Treating the blob
	$text = str_replace('] (','](',$text); // Links error correction
	$text = str_replace('href=\"','href="',$text);
	$text = str_replace('\">','">',$text);
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
		$text = str_replace('<br />','<br/>',$text);
		$text = preg_replace('/@([^\W_ ]*)([\W_ ])/', '<strong><a href="https://github.com/$1" target="_blank">@$1</a></strong>$2', $text); // mentioning		
		$text = str_replace('<pre><code></code></pre>','',$text);
		$text = str_replace('<pre><code>~~~','<p>',$text);
		$text = str_replace('</code></pre>','</p>',$text);
		$text = preg_replace('/(~~~&gt;)([^~]*)(~~~)/','<blockquote>$2</blockquote>',$text);
		$text = str_replace('<pre><code class="language-and">','and',$text);
		$text = str_replace('&lt;','<',$text);
		$text = str_replace('&gt;','>',$text);
		$text = str_replace('~~~','<br/>',$text);
		$text = str_replace('<p><p>','<p>',$text);
		$text = str_replace('</p></p>','</p>',$text);
		$splitout[$i] = $text;
	}
	$output = implode('<br/>',$splitout);
	return $output;
}
function findissue($filepath)
{
	$data = file_get_contents($filepath);
	$issue_comment_separator = explode('BODY STARTS FROM HERE',$data);
	$issue = $issue_comment_separator[0];
	$split_issue = preg_split('/[^!][\[][^ ]/',$issue);
	$closed_at=explode('"comments":',$split_issue[1]);
	$closed_by=explode('"closed_by":',$closed_at[1]);
	$comment = $issue_comment_separator[1];
	file_put_contents('stopgap.txt',$split_issue[0].'['.$closed_by[0].$comment);
}
function commentheader($username,$time)
{
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