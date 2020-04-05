<?php

//parms: $array[song_id => Song Title], $activeId, $genreId, $isSong
$array = (isset($array)) ? $array : [];
$activeId = (isset($activeId)) ? $activeId : "";
$genreId = (isset($genreId)) ? $genreId : "";
$firstId = (isset($isSong)&&$isSong) ? "genre" : "id";
$secondId = (isset($isSong)&&$isSong) ? "song" : "action";

$print = false;
$list = '<nav id="subnav"><ul>';
foreach ($array as $i => $s) {
    $active = ($activeId == $i) ? 'class="active"' : '';
    $list .= "<li><a $active href='index.php?$firstId=$genreId&$secondId=$i'>$s</a>";
    $print = true;
}
$list .= '</ul></nav>';
if($print){
    echo $list;
}