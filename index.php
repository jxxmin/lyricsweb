<?php
/*
Songtexte und Akkorde
Module 151 / 426
Webapplikation mit Datenbank / Agile Softwareentwicklung
Feb/Mär/Apr 2020 Sofia Horlacher, Jasmin Fitz, Luisa Stückelberger
*/
header("Content-Type: text/html; charset=utf-8");
include "view/chordfunctions.php";
include "controller/dbfunctions.php";
$file = 'home.php';

$admin = false;
$loggedin = false;

// initialize target location
if($loggedin){
    $id = 'edit';
} else {
    $id = 'login';
};
if(isset($_GET['id'])){
    $id = $_GET['id'];
}

// initialize target location
if(!$loggedin){
    $song = 'login';
}
if(isset($_GET['song'])){
	$song = $_GET['song'];
}


$style = "";
$fontsize = 15;

$sharp = true;

//active Link


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

                // Genre is selected
				if($id!='edit'&&$id!='login'){
					echoSmallNav(getSongs());
					// Song is selected
					if(isset($_GET['song'])){
					    /// song page
                            include "view/transposemenu.php";
                            $columns = getColumns();
                            $songtext = styleSongtext(getSongtext($id, $song));
                            $title = makeName($song);
                            echo "<section>";
                            echo "<h2>$title</h2><pre><div $columns class='songbox'>$songtext</div></pre></section>";
						///

                    } else{
                        if($admin){
                            include "view/deletegenre.php";
                        }
					}
                // Nothing selected and not logged in: Login Page
				} else if($id = 'login'){
                    $options = ['login' => 'Login', 'registrieren' => 'Registrieren'];
                    echoSmallNav($options);
                    include "view/login.php";

                // Nothing selected and logged in: Edit Page
                } else {
                    $options = ['add_song' => 'Add Song', 'add_genre' => 'Add Genre'];
                    echoSmallNav($options);
                    include "view/edit.php";
                }
				
				?>
		</div>

		<footer>
			<p>&copy; 2019, Luisa Stückelberger
		</footer>
	</body>
</html>

