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

/* Session erneuern */
session_start();
session_regenerate_id();


//function loadfromDatabase(){

    try {
        $user = 'root';
        $password = '';
        $host = 'localhost';
        $database = 'lyricsweb';
        $GLOBALS['con'] = new mysqli($host,$user,$password,$database);
    }
    catch(PDOException $e){
        echo '<p>Verbindung fehlgeschlagen';
        if(ini_get('display_errors')){
            echo $e -> getMessage();
        }
        exit;
    }
//}


function getGenres(){

    //if (isset($_SESSION['username'])) {
        $query = "select * from genre";

        try {
            $result = mysqli_query($GLOBALS['con'], $query);
            if ($result == null) {
                session_destroy();
                $genres = null;
            } else {
                while($row = mysqli_fetch_array($result)){
                    $genres[] = $row['genre'];
                }
            }
        } catch (Exception $e) {
            echo "Etwas ist schief gelaufen. Bitte erneut versuchen";
        }
   /* } else {
        session_destroy();
        header('Location: ../index.php');
    }*/
    return $genres;
}

function getSongtext($id){
   // if (isset($_SESSION['username'])) {

        try {
            if ($stmt = mysqli_prepare($GLOBALS['con'], "SELECT lyrics.songtext FROM lyrics where (titel = ?)")) {
                mysqli_stmt_bind_param($stmt, "s", $song);
                $song = $_GET['song'];

                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $songtext);

                mysqli_stmt_fetch($stmt);

                if ($songtext == null) {
                    session_destroy();
                    $songtext = null;
                }
                mysqli_stmt_close($stmt);

            }

            } catch (Exception $e) {
            echo "Etwas ist schief gelaufen. Bitte erneut versuchen";
        }
    /* } else {
         session_destroy();
         header('Location: ../index.php');
     }*/
    return $songtext;

}

function getSongs(){
    //if (isset($_SESSION['username'])) {
        try {
            if ($stmt = mysqli_prepare($GLOBALS['con'], "SELECT lyrics.titel FROM lyrics join genre on genre.id = lyrics.fk_genre where (genre.genre = ?)")) {
                mysqli_stmt_bind_param($stmt, "s", $genre);
                $genre = $_GET['id'];
                mysqli_stmt_execute($stmt);

                mysqli_stmt_bind_result($stmt, $titel);

                while (mysqli_stmt_fetch($stmt)) {
                    $songs[$titel] = makeName($titel);
                }

                if($titel == null){
                    session_destroy();
                    $songs = null;
                }

                mysqli_stmt_close($stmt);
            }

        } catch (Exception $e) {
            echo "Etwas ist schief gelaufen. Bitte erneut versuchen";
        }
    /*} else {
        session_destroy();
        header('Location: ../index.php');
    }*/
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
