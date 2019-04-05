<?php
require_once('vendor/autoload.php');
require_once('menu.php');

if(!isset($_GET['id'])){
        echo '<html><head><title>Docstyle</title>'.menu(null,false,false,false);
	echo '<div class="alertspace">Welcome to DocStyle! Docstyle provides a way to enhance Google Docs with additional styling. The gear/bar at the bottom of the screen gives you options for navigating around documents.<br>Use it to specify a Google Doc to load!</div>';
	echo '</body></html>';
	exit();
}

$fileId = $_GET['id'];

if(!file_exists('docs/'.$fileId.'.htm')){
        echo '<html><head><title>Docstyle</title>'.menu($fileId,true,true,false);
	echo '<div class="alertspace">No stored version of that document is available. Click <a href="live.php?id='.$fileId.'">here</a> to view the live Google Doc</div>';
        echo '</body></html>';
        exit();
}else{
        echo preg_replace("`</head><body *>`",menu($fileId,false,true,false),file_get_contents('docs/'.$fileId.'.htm'));
}
