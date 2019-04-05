<?php

function menu($id,$edit,$live,$cache){

$ret = '<style type="text/css">@import url(\'https://fonts.googleapis.com/css?family=Roboto\'); .docstylecornerpocket {z-index: 1000; font-size:small; padding:5px;text-align:right;border-radius:3px; border:1px solid #999;background-color:#efefef;color:#333;position:fixed; bottom:1%; right:1%;font-family:"Roboto",sans-serif;} @media print { .docstylecornerpocket{ display:none !important;}} .docstylecornerpocket a,div.alertspace a{color:#66b;text-decoration:none;font-weight:bold;display:block;} .docstylecornerpocket a:hover,div.alertspace a:hover{text-decoration:underline;}.docstylecornerpocket input{background-color:#eee;font-family:"Roboto",san-serif;color:#333;display:inline-block;width:100px;font-size:x-small;} .docstylecornerpocket .docstylehidden{position:relative;height:0em;width:0em;overflow:hidden;transition: all 250ms;} .docstylecornerpocket:hover .docstylehidden{height:8.5em !important; width:7em;} @media only screen and (max-width: 1000px){ .docstylecornerpocket{width:96% !important;} .docstylehidden{height:auto !important;width:auto !important;float:left;} .docstylehidden a, .docstylehidden form, .docstylehidden label, .docstylehidden input{display: inline;margin-left:2em;} .docstylehidden br{display:none;}} div.alertspace{font-family:"Roboto",sans-serif;max-width:900px;margin-left:auto;margin-right:auto;color:#222;}</style></head> <body> <div class="docstylecornerpocket"><div class="docstylehidden">';
if($id != null){
if($edit){
$ret .= '<a href="edit.php?id='.$id.'">EDIT</a>';
}
if($live){
$ret .= '<a href="live.php?id='.$id.'">VIEW LIVE</a>';
}
if($cache){
$ret .= '<a href="index.php?id='.$id.'">VIEW PUBLIC</a>';
}
$ret .= '<a href="live.php?pdf=true&id='.$id.'">VIEW PDF</a>';
$ret .= '<a href="live.php?raw=true&id='.$id.'">VIEW RAW</a>';
}
$ret .= '<form action="interperet.php" method="get"><label>Google Doc:<br><input type="text" name="id"></label></form>';
$ret .= '</div>⚙</div>';
return $ret;

}
