<?php
include_once __DIR__ . '/vendor/autoload.php';

$fileId = $_GET['id'];
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


/************************************************
 * The redirect URI is to the current page, e.g:
 * http://localhost:8080/large-file-download.php
 ************************************************/
$redirect_uri = 'https://'.$_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'].'?id='.$fileId;
$client = new Google_Client();
$client->setAuthConfig('creds/client_secret_103117502738-48bc16vrs6ht4kci3cojcfcg0vs1hop5.apps.googleusercontent.com.json');
$client->setRedirectUri($redirect_uri);
$client->addScope("https://www.googleapis.com/auth/drive");
$service = new Google_Service_Drive($client);

/************************************************
 * If we have a code back from the OAuth 2.0 flow,
 * we need to exchange that with the
 * Google_Client::fetchAccessTokenWithAuthCode()
 * function. We store the resultant access token
 * bundle in the session, and redirect to ourself.
 ************************************************/
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token);
  // store in the session also
  $_SESSION['upload_token'] = $token;
  // redirect back to the example
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
}
if(isset($_SESSION['upload_token'])){
// set the access token as part of the client if (!empty($_SESSION['upload_token'])) {
  $client->setAccessToken($_SESSION['upload_token']);
  if ($client->isAccessTokenExpired()) {
    unset($_SESSION['upload_token']);
  }
} else {
  $authUrl = $client->createAuthUrl();
}
if (!$client->getAccessToken()) {
?> <div class="box"> <?php if (isset($authUrl)): ?>
  <div class="request">
    <a class='login' href='<?= $authUrl ?>'>Connect Me!</a>
 </div> <?php endif ?> </div>
<?php
}else{

$response = $service->files->export($fileId,'text/html', array( 'alt' => 'media'));
$content = $response->getBody()->getContents();
$data = $service->files->get($fileId,array('fields'=>'capabilities,name'));

if(isset($_GET['raw'])){
	echo $content;
	exit();
}
//remove styles
$content = preg_replace("`<style.*?</style>`","",$content);
$content = preg_replace("`<span[^>]*font-weight:700[^>]*>(.*?)</ *span *>`","<strong>$1</strong>",$content);
$content = preg_replace("`<span[^>]*font-style:italic[^>]*>(.*?)</ *span *>`","<em>$1</em>",$content);
$content = preg_replace("`style=\".*?\"`","",$content);

//remove spans
$content = preg_replace("`<span.*?>(.*?)</span>`","$1",$content);


//remove ids
$content = preg_replace("`id=\".*?\"`","",$content);

//remove classes
$content = preg_replace("`<p[^>]*class=\"title\"[^>]*>(.*?)</p>`","<b>$1</b>",$content);
$content = preg_replace("`class=\".*?\"`","",$content);

//add headers
$content = preg_replace("`</head>`","<title>".$data->name."</title><link rel=\"stylesheet\" type=\"text/css\" href=\"styles/".$fileId.".css\"></head>",$content);

//clear out empties
$content = preg_replace("`<h[0-9][^>]*> *</h[0-9]>`","",$content);

//use linebreaks
$content = preg_replace("`< *p *> *</ *p *>`","<!-- para -->",$content);
$content = preg_replace("`</ *p>[^a-z0-9A-Z]*<p *>`","<br>",$content);
$content = preg_replace("`<!-- para -->`","",$content);


function safe($string){
	return strtolower(preg_replace("`[^a-zA-Z0-9]+`","",substr($string,0,20)));
}

//structural divs
$deep = 0;
$last = -1;
$content = preg_replace_callback("`<(b|h[0-9]|p) *>(.*?)</(b|h[0-9]|p) *>`",function($matches){
	global $deep, $last;
	$levels = ['b'=>0,'h1'=>1,'h2'=>2,'h3'=>3,'h4'=>4,'h5'=>5,'h6'=>6,'p'=>null];
	$current = $levels[$matches[1]];

	$identifier = safe($matches[2]);

	$ret = '<'.$matches[1].' class="'.$identifier.'">'.$matches[2].'</'.$matches[1].'>';

	if($current != null){
		$ret = '</div><div class="'.$identifier.'wrapper"><div class="'.$identifier.'section">'.$ret;

		if($current <= $last){
			$deep -= $last-$current;
			for($i = 0; $i <= $last-$current; $i++){
				$ret = '</div>'.$ret;
			}
		}else{
			$deep += $current - $last;
		}

		$last = $current;
	}
	return $ret;
},$content);

file_put_contents('docs/'.$fileId.'.htm',$content);
echo $content;

}
