<?php
/*
Songtexte und Akkorde
Module 151 / 426
Webapplikation mit Datenbank / Agile Softwareentwicklung
Feb/Mär/Apr 2020 Sofia Horlacher, Jasmin Fitz, Luisa Stückelberger
*/

$id = '';
if(isset($_GET['id'])) {
    $id = $_GET['id'];
}
$song = '';
if(isset($_GET['song'])) {
    $song = $_GET['song'];
}

require "view/sessionfunctions.php";
include "view/chordfunctions.php";
include "controller/dbfunctions.php";
$file = 'home.php';

?>
<!doctype html>
<html lang="de-CH">
	<head>
		<meta charset="utf-8">
		<title>Songtexte und Akkorde</title>
		<link rel="stylesheet" href="view/layout.css" type="text/css">
		<link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
	</head>
	<body>
		
	<?php include "view/header_nav.php"; ?>
		<div id="wrapper">

            <?php
            include "view/smallnav.php";

            switch($id){
                case '':
                case 'login':
                case 'edit':
                    if(isLoggedIn()){
                        // logged in: Edit Page
                        $id = 'edit';
                        if($song==''){
                            $song = 'add_song';
                        }
                        $options = ['add_song' => 'Add Song', 'add_genre' => 'Add Genre'];
                        echoSmallNav($options);
                        include "view/edit.php";
                    } else{
                        // not logged in: Login Page
                        $id = 'login';
                        if($song==''){
                            $song = 'login';
                        }
                        $options = ['login' => 'Login', 'registrieren' => 'Registrieren'];
                        echoSmallNav($options);
                        include "view/login.php";
                    }
                    break;
                default:
                    $songs = getSongs();
                    echoSmallNav($songs);
                    if(isAdmin() && !$songs){
                        include "view/deletegenre.php";
                    }else if ($songs && isset($_GET['song'])){
                        /// song page
                        $song = $_GET['song'];
                        include "view/transposemenu.php";
                        $columns = getColumns();
                        $songtext = formatSongtext(getSongtext($id, $song));
                        $title = makeName($song);
                        echo "<section>";
                        echo "<h2>$title</h2><pre><div $columns class='songbox'>$songtext</div></pre></section>";
                    }
                    break;
            }

            ?>
		</div>

		<footer>
			<p>&copy; 2020, Fitz, Horlacher, Stückelberger
		</footer>
	</body>
</html>

