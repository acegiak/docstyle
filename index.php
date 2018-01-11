<?php

$fileId = $_GET['id'];

if(!file_exists('docs/'.$fileId.'.htm')){
	echo 'No stored version of that document is available. Click <a href="live.php?id='.$fileId.'">here</a> to view the live Google Doc';
	exit();
}else{
	echo file_get_contents('docs/'.$fileId.'.htm');
}
