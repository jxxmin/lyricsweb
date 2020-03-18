<?php
/*
Songtexte und Akkorde
Module 151 / 426
Webapplikation mit Datenbank / Agile Softwareentwicklung
Feb/Mär/Apr 2020 Sofia Horlacher, Jasmin Fitz, Luisa Stückelberger
*/
header("Content-Type: text/html; charset=utf-8");

function echoSmallNav($array){
    global $song;
    global $id;
    $print = false;
    $list = '<nav id="subnav"><ul>';
    foreach ($array as $i => $s) {
        if ($song === $i) {
            $active = 'class="active"';
        } else {
            $active = '';
        }
        $list .= "<li><a $active href='index.php?id=$id&song=$i'>" . $s . "</a>";
        $print = true;
    }
    $list .= '</ul></nav>';
    if($print){
        echo $list;
    }

}

?>