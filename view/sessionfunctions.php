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



function setAdminSession(){
    $_SESSION['admin'] = "true";
    setUserSession();
}

function setUserSession(){
    $_SESSION['loggedIn'] = "true";
    $_SESSION['token'] = getToken();
    echo "success";
    session_write_close();
}

function isLoggedIn(){
    if (isset($_SESSION['loggedIn'])) {
        return true;
    } else {
        return false;
    }
}

function isAdmin(){
    if (isset($_SESSION['admin'])) {
        return true;
    } else {
        return false;
    }
}

function getToken(){
    $length = 32;
    return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
}
