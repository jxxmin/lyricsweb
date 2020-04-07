<?php
/*
Songtexte und Akkorde
Module 151 / 426
Webapplikation mit Datenbank / Agile Softwareentwicklung
Feb/Mär/Apr 2020 Sofia Horlacher, Jasmin Fitz, Luisa Stückelberger
*/
header("Content-Type: text/html; charset=utf-8");

//parms: $navArray, $activeTab
$genreArray = (isset($genreArray)) ? $genreArray : [];
$actionArray = (isset($actionArray)) ? $actionArray : [];
$activeTab = (isset($activeTab)) ? $activeTab : "";

?>

    <header><a href="index.php">
        <h1>Songtexts and Chords</h1>
        </a>
    </header>

    <nav id='mainnav'>
        <ul>
            <?php
            foreach ($actionArray as $id => $actionTitle) {
                $active = ($activeTab == $id) ? 'class="active"' : '';
                echo "<li><a $active href='index.php?id=$id'>$actionTitle</a>";
            }
            foreach ($genreArray as $genreId => $genreTitle) {
                $active = ($activeTab == $genreId) ? 'class="active"' : '';
                echo "<li><a $active href='index.php?genre=$genreId'>$genreTitle</a>";
            }
            ?>
        </ul>
    </nav>


