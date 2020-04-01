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

$songtext = '';

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

function getGenres(){

        $query = "select * from genre";

        try {
            $result = mysqli_query($GLOBALS['con'], $query);
            if ($result == null) {
                $genres = null;
            } else {
                while($row = mysqli_fetch_array($result)){
                    $genres[] = $row['genre'];
                }
            }
        } catch (Exception $e) {
            echo "Etwas ist schief gelaufen. Bitte erneut versuchen";
        }

    return $genres;
}

function getSongtext(){
 global $songtext;
    try {
        if ($stmt = mysqli_prepare($GLOBALS['con'], "SELECT lyrics.songtext FROM lyrics where (titel = ?)")) {
            mysqli_stmt_bind_param($stmt, "s", $song);
            $song = $_GET['song'];

            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $text);

            mysqli_stmt_fetch($stmt);

            mysqli_stmt_close($stmt);

            $songtext = $text;
        }

    } catch (Exception $e) {
        echo "Etwas ist schief gelaufen. Bitte erneut versuchen";
    }
    return $songtext;

}



function getSongs(){
    $songs = [];
    try {
        if ($stmt = mysqli_prepare($GLOBALS['con'], "SELECT lyrics.titel FROM lyrics join genre on genre.id = lyrics.fk_genre where (genre.genre = ?)")) {
            mysqli_stmt_bind_param($stmt, "s", $genre);
            $genre = $_GET['id'];
            mysqli_stmt_execute($stmt);

            mysqli_stmt_bind_result($stmt, $titel);

            while (mysqli_stmt_fetch($stmt)) {
                $songs[$titel] = makeName($titel);
            }

            mysqli_stmt_close($stmt);
        }

    } catch (Exception $e) {
        echo "Etwas ist schief gelaufen. Bitte erneut versuchen";
        $songs = false;
    }
    return $songs;

}

function loginOnDB($username, $pw){

        $query = "select * from login where username='" . $username . "'";

        try {
            $result = mysqli_query($GLOBALS['con'], $query);
            if ($result == null) {
                $username = null;
                echo "unknown user";
                return false;
            } else {
                $row = mysqli_fetch_array($result);
                $dbpw = $row['password'];
                if(passwordIsCorrect($pw, $dbpw)){
                    return ($row['admin']==1);
                } else {
                    return false;
                }
            }

        } catch (Exception $e) {
            echo "Etwas ist schief gelaufen. Bitte erneut versuchen";
            return false;
        }
}

function registerOnDb($user, $pw){

    $hashedpassword = hashPassword($pw);
    //Insert username and the hashed password to MySQL database
    return mysqli_query($GLOBALS['con'],"INSERT INTO `login` (`username`, `password`) VALUES ('$user', '$hashedpassword')") or die("no insert");
}


function passwordIsCorrect($password, $dbPassword){
    $salt = substr($dbPassword, 0, 64);
    $correcthash = substr($dbPassword, 64, 64);
    $userhash = hash("sha256", $salt . $password);
    if ($userhash == $correcthash) {
        return true;
    } else {
        return false;
    }
}

function hashPassword($input) {
    $salt = bin2hex(random_bytes(64));
    $hash = hash("sha256", $salt . $input);
    $final = $salt . $hash;
    return $final;
}

function sanitize($data){
    $data = trim($data);
    $data = htmlspecialchars($data);
    return mysqli_real_escape_string($GLOBALS['con'], $data);
}

function makeName($input){
    $input = ucwords($input);
    $input = str_replace('_', ' ', $input);
    return $input;
}
?>
