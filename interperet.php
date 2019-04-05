<?php
if(!isset($_GET['id'])){
	header('Location: index.php');
	}
	$id = $_GET['id'];
	if(strpos($id,"/")!==false){
		$bits = explode("/",$id);
		$held = "";
		foreach($bits as $bit){
			if(strlen($bit) > strlen($held) && !stristr(".",$bit) ){
				$held = $bit;
			}
		}
		$id = $held;
	}

header('Location: index.php?id='.$id);
