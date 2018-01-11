<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
session_start();


$client = new Google_Client();

$redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . "/live.php?id=".$_GET['id'];
$client->setAuthConfig('creds/client_secret_103117502738-48bc16vrs6ht4kci3cojcfcg0vs1hop5.apps.googleusercontent.com.json');
$client->setRedirectUri($redirect_uri);
$client->setScopes('https://www.googleapis.com/auth/drive.readonly');

$service = new Google_Service_Drive($client);
// set the access token as part of the client
if (!empty($_SESSION['upload_token'])) {
  $client->setAccessToken($_SESSION['upload_token']);
  if ($client->isAccessTokenExpired()) {
    unset($_SESSION['upload_token']);
          header('Location: ' . $redirect_uri."?id=".$_GET['id']);
  }
}
if (!$client->getAccessToken()) {
	  header('Location: ' . $redirect_uri."?id=".$_GET['id']);
}
$data = $service->files->get($_GET['id'],['fields'=>'capabilities']);
if(!$data->capabilities->canEdit){
          header('Location: ' . $redirect_uri."?id=".$_GET['id']);
}

$css = "";
if(isset($_GET['id']) && file_exists("styles/".$_GET['id'].".css")){
        $css = file_get_contents("styles/".$_GET['id'].".css");
}
        if(isset($_GET['id']) && isset($_POST['css'])){
                $css = $_POST['css'];
                file_put_contents("styles/".$_GET['id'].".css",$css);
        }


?>
<html>
<head>
<style type="text/css">
iframe{ width:60%; height:95%;float:left;}
textarea{ width:35%; height:80%;float:left;}
</style>
</head>
<body>
<?php echo "<iframe src=\"live.php?id=".$_GET['id']."\"></iframe>"; ?>

<form action="" method="post">
<textarea name="css"><?php
        echo htmlentities($css);
?></textarea>
<button type="submit">save</button><a href="live.php?id=<?php echo $_GET['id']; ?>">view</a>
</form>

