<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
require_once 'menu.php';

use mikehaertl\wkhtmlto\Pdf;

session_start();


$client = new Google_Client();

$redirect_uri = 'https://' . $_SERVER['HTTP_HOST'] . "/live.php?id=".$_GET['id'];
$client->setAuthConfig('creds/client_secret_1099178135878-0r27j1cemrmtl37qvsav4tic12sebkrs.apps.googleusercontent.com.json');
$client->setRedirectUri($redirect_uri);
$client->setScopes('https://www.googleapis.com/auth/drive.readonly');

$service = new Google_Service_Drive($client);
// set the access token as part of the client
if (!empty($_SESSION['upload_token'])) {
  $client->setAccessToken($_SESSION['upload_token']);
  if ($client->isAccessTokenExpired()) {
    unset($_SESSION['upload_token']);
          header('Location: ' . $redirect_uri);
  }
}
if (!$client->getAccessToken()) {
	  header('Location: ' . $redirect_uri);
}
$data = $service->files->get($_GET['id'],['fields'=>'capabilities']);
if(!$data->capabilities->canEdit){
          header('Location: ' . $redirect_uri);
}

$css = "";
if(isset($_GET['id']) && file_exists("styles/".$_GET['id'].".css")){
        $css = file_get_contents("styles/".$_GET['id'].".css");
}
        if(isset($_GET['id']) && isset($_POST['css'])){
                $css = $_POST['css'];
                file_put_contents("styles/".$_GET['id'].".css",$css);
        }
	if(isset($_GET['id']) && isset($_POST['itchApi']) && isset($_POST['itchName']) && isset($_POST['itchGame']) ){
		$key = $_POST['itchApi'];
		file_put_contents("tokens/".$_GET['id'],$key);
		file_put_contents("tokens/".$_GET['id'].".json",json_encode(array("name"=>$_POST['itchName'],"game"=>$_POST['itchGame'])));
	}
$apikey = "";
$apiname = "";
$apigame = "";
if(isset($_GET['id']) && file_exists("tokens/".$_GET['id'])){
	$apikey = file_get_contents("tokens/".$_GET['id']);
	$deets = json_decode(file_get_contents("tokens/".$_GET['id'].".json"),true);
	$apiname = $deets['name'];
	$apigame = $deets['game'];
}

if(isset($_GET['id']) && isset($_POST['push']) && file_exists("tokens/".$_GET['id'])){
        if(!file_exists('docs/'.$_GET['id'].'.htm')){
                $fileId = $_GET['id'];
		echo "no file";
		exit();
        }else{
                $title = preg_replace("`^.*?<title>(.*?)</title>.*?$`","$1",file_get_contents('docs/'.$_GET['id'].'.htm'));

                $pdf = new Pdf('https://docstyle.machinespirit.net/view.php?id='.$_GET['id']);
                $pdf->setOptions(array('disable-smart-shrinking','print-media-type','margin-top'=>15,'margin-bottom'=>15,'margin-left'=>5,'margin-right'=>5));
                $pdf->saveAs("build/".$title.".pdf");
		$zip = new ZipArchive;
		if ($zip->open('build/'.$_GET['id'].'.zip', ZipArchive::CREATE) === TRUE) {
			$zip->addFile('build/'.$title.'.pdf', $title.'.pdf');
			$zip->close();
			echo '<div class="note">';
			echo shell_exec("/opt/butler/butler -i tokens/".$_GET['id']." push build/".$_GET['id'].".zip ".$apiname."/".$apigame.":pdf"); // no $output
			echo '</div>';
		} else {
			echo 'failed';
			exit();
		}


        }

}

?>
<html>
<head>
<style type="text/css">
iframe{ width:60%; height:95%;float:left;}
textarea{ width:35%; height:80%;float:left;}
.note{font-size:x-small;}
</style>
<?php
echo menu($_GET['id'],false,true,true);

echo "<iframe src=\"live.php?id=".$_GET['id']."&framed=true\"></iframe>"; ?>

<form action="" method="post">
<textarea name="css"><?php
        echo htmlentities($css);
?></textarea>
<button type="submit">save</button>

<?php
echo '<br><input type="text" name="itchName" value="'.$apiname.'" placeholder="itch Username">';
echo '<input type="text" name="itchGame" value="'.$apigame.'" placeholder="itch game name">';
echo '<input type="password" name="itchApi" value="'.$apikey.'" placeholder="itch Api Key">';
?>
<br><input type="checkbox" name="push" value="push">Push to Itch
</form>
</body></html>

