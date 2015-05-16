<?php
include 'Parsedown.php';
function parsedown($text)
{
	$text = str_replace('\r\n','<br/>',$text);
	$text = str_replace('href=\"','href="',$text);
	$text = str_replace('\">','">',$text);
	$Parsedown = new Parsedown();
	echo $Parsedown->text($text);
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
function findissue($filepath)
{
	$data = file_get_contents($filepath);
	$issue_comment_separator = explode('BODY STARTS FROM HERE',$data);
	$issue = $issue_comment_separator[0];
	$split_issue = preg_split('/[^!][\[][^ ]/',$issue);
	$closed_at=explode('"comments":',$split_issue[1]);
	$closed_by=explode('"closed_by":',$closed_at[1]);
	$comment = $issue_comment_separator[1];
	echo $split_issue[0].'['.$closed_by[0].$comment;
	file_put_contents('stopgap.txt',$split_issue[0].'['.$closed_by[0].$comment);
}
findissue('E:\C_drive\xampp\htdocs\github_issue_backup\sanskrit-lexicon\CORRECTIONS\4.txt');
?>