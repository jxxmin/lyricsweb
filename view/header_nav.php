<?php
/*
Songtexte und Akkorde
Module 151 / 426
Webapplikation mit Datenbank / Agile Softwareentwicklung
Feb/Mär/Apr 2020 Sofia Horlacher, Jasmin Fitz, Luisa Stückelberger
*/
header("Content-Type: text/html; charset=utf-8");

//Location of home
$location = "./songs";
$activetext = 'class="active"';
$active = '';


//variables
$genre;


?><header>
	<h1>Songtexte und Akkorde</h1>
</header>

<nav id='mainnav'>
	<ul>

		<?php
        if($id == ""|| $id=='login' || $id=='edit'){
            $active = $activetext;
        }
        if(isLoggedIn()) {
            echo "<li><a $active href='index.php'>Edit</a>";
        } else {
            echo "<li><a $active href='index.php'>Login</a>";
        }
        $genres = getGenres();
        foreach ($genres as $genre){
            if ($id == $genre) {
                $active = $activetext;
            } else {
                $active = '';
            }
            echo "<li><a $active href='index.php?id=$genre'>" . ucfirst($genre) . "</a>";
        }
		?>
	</ul>
</nav>
