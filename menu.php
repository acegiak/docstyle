<?php

function menu($id,$edit,$live,$cache){

$ret = '<style type="text/css">@import url(\'https://fonts.googleapis.com/css?family=Roboto\');div.docstylecornerpocket {font-size:small;opacity:0.3;padding:5px;text-align:right;border-radius:3px; border:1px solid #999;background-color:#efefef;color:#333;position:fixed; bottom:1%; right:1%;font-family:"Roboto",sans-serif;}div.docstylecornerpocket a{color:#66b;text-decoration:none;font-weight:bold;display:block;}div.docstylecornerpocket a:hover{text-decoration:underline;}div.docstylecornerpocket input{background-color:#eee;font-family:"Roboto",san-serif;color:#333;display:inline-block;width:100px;}</style></head><body><div class="docstylecornerpocket">';
if($id != null){
if($edit){
$ret .= '<a href="edit.php?id='.$id.'">EDIT</a>';
}
if($live){
$ret .= '<a href="live.php?id='.$id.'">LIVE</a>';
}
if($cache){
$ret .= '<a href="index.php?id='.$id.'">PUBLIC</a>';
}
}
$ret .= '<form action="interperet.php" method="get"><label>Google Doc:<br><input type="text" name="id"></label></form>';
$ret .= '</div>';
return $ret;

}
