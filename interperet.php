<?php
if(!isset($_GET['id'])){
header('Location: index.php');
}
$id = $_GET['id'];
if(strpos($id,"/")!==false){
$bits = explode("/",$id);
foreach($bits as $bit){
if(preg_match("`[a-z0-9A-Z]+[\-_][a-z0-9A-Z]+`",$bit)){
$id = $bit;
break;
}
}
}
header('Location: index.php?id='.$id);
