<?php

include('simple_html_dom.php');

$url = $_POST['url'];

$ch = curl_init();
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1944.0 Safari/537.36");
curl_setopt($ch, CURLOPT_COOKIE, "---ADD-A-VALID-SESSION-COOKIE-HERE---");	//Add a valid logged in session cookie
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

if(!($output = curl_exec($ch)))
{
	echo json_encode(array('status' => false));
	return;
}

curl_close($ch);

$html = str_get_html($output);
$commentBlock = $html->find('code', 1);

if(empty($commentBlock))
{
	echo json_encode(array('status' => false));
	return;
}


$innerDiv = str_get_html(substr($commentBlock->innertext, 4));
$imgTag = $innerDiv->find('.profilePic');

if(empty($imgTag))
{
	echo json_encode(array('status' => false));
	return;
}

$imgUrl = $imgTag[0]->src;

$array = explode('/', $imgUrl);
$array = preg_grep('~160x160~', $array, PREG_GREP_INVERT);
$array = preg_grep('~c\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}~', $array, PREG_GREP_INVERT);

$newUrl = implode('/', $array);

echo json_encode(array('status' => true, 'url' => $newUrl));
