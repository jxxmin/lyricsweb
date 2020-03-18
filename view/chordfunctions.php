<?php
/*
Songtexte und Akkorde
Module 151 / 426
Webapplikation mit Datenbank / Agile Softwareentwicklung
Feb/Mär/Apr 2020 Sofia Horlacher, Jasmin Fitz, Luisa Stückelberger
*/
header("Content-Type: text/html; charset=utf-8");


//constants
$opentag = "<span class='chord'>";
$closetag = "</span>";
$chordregex = '/(\b[A-G][#|b]?m?[2-9]?((sus|maj|min|aug|dim|Maj)[2-9]?)?\b)/';
$replace = $opentag."$1".$closetag;


//array for chords with sharps
$sharpchord[$opentag."A#"] = $opentag."B";
$sharpchord[$opentag."C#"] = $opentag."D";
$sharpchord[$opentag."D#"] = $opentag."E";
$sharpchord[$opentag."F#"] = $opentag."G";
$sharpchord[$opentag."G#"] = $opentag."A";
$sharpchord[$opentag."A"] = $opentag."A#";
$sharpchord[$opentag."B"] = $opentag."C";
$sharpchord[$opentag."C"] = $opentag."C#";
$sharpchord[$opentag."D"] = $opentag."D#";
$sharpchord[$opentag."E"] = $opentag."F";
$sharpchord[$opentag."F"] = $opentag."F#";
$sharpchord[$opentag."G"] = $opentag."G#";


$flatchord[$opentag."Ab"] = $opentag."A";
$flatchord[$opentag."A"] = $opentag."Bb";
$flatchord[$opentag."Bb"] = $opentag."B";
$flatchord[$opentag."B"] = $opentag."C";
$flatchord[$opentag."C"] = $opentag."Db";
$flatchord[$opentag."Db"] = $opentag."D";
$flatchord[$opentag."D"] = $opentag."Eb";
$flatchord[$opentag."Eb"] = $opentag."E";
$flatchord[$opentag."E"] = $opentag."F";
$flatchord[$opentag."F"] = $opentag."Gb";
$flatchord[$opentag."Gb"] = $opentag."G";
$flatchord[$opentag."G"] = $opentag."Ab";



		
//functions=======================
function tochords($input){
	global $chordregex;
	global $replace;
	return preg_replace($chordregex, $replace, $input);
	
}


function transposeUp($input){
	global $sharpchord;
	global $flatchord;
	global $sharp;
	
	
	if($sharp){
		$input = strtr($input, $sharpchord);		
	} else {
		$input = strtr($input, $flatchord);		
	}
	return $input;
}


function transposeDown($input){
	global $sharpchord;
	global $flatchord;
	global $sharp;
	
	if($sharp){
		$input = strtr($input, array_flip($sharpchord));		
	} else {
		$input = strtr($input, array_flip($flatchord));		
	}
	return $input;
}

?>