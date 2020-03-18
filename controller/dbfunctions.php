<?php
/*
Songtexte und Akkorde
Module 151 / 426
Webapplikation mit Datenbank / Agile Softwareentwicklung
Feb/Mär/Apr 2020 Sofia Horlacher, Jasmin Fitz, Luisa Stückelberger
*/
header("Content-Type: text/html; charset=utf-8");

//directories, which don't count
$wrongdir = [".", "..", "include", "img", "pdfs", ".idea"];

function loadfromDatabase(){

}

function getSongtext($id, $song){
    $songtext = '';
    if($id != '' && $song != '') {

        $f = "./songs/$id/$song.txt";
        $resource = fopen($f, 'r');
        $songtext = "<p class='song'>" . tochords(fgets($resource));
        while (!feof($resource)) {
            $line = tochords(fgets($resource));
            if (ctype_space($line)) {
                $line = "<br><p class='song'>";
            } else {
                //$line .="<br>";
            }
            $songtext .= $line;

        }
    }
    return $songtext;
}

function getGenres(){
    $location = "./songs";
    global $id;
    $genres = array();
    if($dir = opendir($location)) {
        while (false !== ($genre = readdir($dir))) {
            if(isGenre($genre)) {
                array_push($genres, $genre);
            }
        }
        closedir($dir);


    }
    return $genres;
}


function getSongs(){
    global $id;
    global $song;
    $location = "./songs/$id";
    $songs = array();
    if($dir = opendir($location)) {
        while (false !== ($file = readdir($dir))) {
            if (!is_dir($file) && $file != "index.php") {
                $file = str_replace('.txt', '', $file);
                $songs[$file] = makeName($file);
            }
        }
        closedir($dir);
    }
    return $songs;
}

function makeName($input){
    $input = str_replace('.php', '', $input);
    $input = str_replace('_', ' ', $input);
    $input = ucwords($input);
    return $input;
}

function isGenre($input){
    global $wrongdir;
    $res = false;
    if(!(in_array($input,$wrongdir))){
        $res = true;
    }
    return $res;
}


?>
