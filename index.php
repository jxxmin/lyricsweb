<?php
/*
Songtexte und Akkorde
Module 151 / 426
Webapplikation mit Datenbank / Agile Softwareentwicklung
Feb/Mär/Apr 2020 Sofia Horlacher, Jasmin Fitz, Luisa Stückelberger
*/

header('Content-Type: text/html; charset=utf-8; X-Frame-Options: DENY; X-Content-Type-Options: nosniff');
session_start();
session_regenerate_id();

require_once("controller/IndexController.php");
require_once("controller/FormController.php");
require_once("controller/LoginController.php");
require_once("controller/SongtextController.php");
require_once("controller/SongtextEditController.php");
require_once("controller/EditController.php");
require_once("controller/GenreController.php");
require_once("model/DBAccess.php");
require_once("model/Genre.php");
require_once("model/Songtext.php");
require_once("model/User.php");
$indexController = new IndexController();

?>
<!doctype html>
<html lang="de-CH">
<head>
    <meta charset="utf-8">
    <title>Songtexts and Chords</title>
    <link rel="stylesheet" href="view/layout.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
</head>
<body>

<?php
$indexController->render();
//require_once("view/footer.php");
?>
