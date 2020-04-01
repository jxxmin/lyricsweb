<?php
/*
Songtexte und Akkorde
Module 151 / 426
Webapplikation mit Datenbank / Agile Softwareentwicklung
Feb/Mär/Apr 2020 Sofia Horlacher, Jasmin Fitz, Luisa Stückelberger
*/
header("Content-Type: text/html; charset=utf-8");

//New Form

$style = "";
$fontsize = 15;

$trans = 0;
$font = 0;
$col = 1;
if(isset($_POST['col'])){
    $col = $_POST['col'];
}

checkfont();
checktrans();

?>
<div class="transposemenu">
	<form method="post" action="index.php?id=<?php echo $id?>&song=<?php echo $song?> ">
		<input type="hidden" name="font" value="<?php echo ($font) ?> ">
		<input type="hidden" name="trans" value="<?php echo ($trans) ?> ">
		<input type="hidden" name="col" value="<?php echo ($col) ?> ">
		<ul>
			<li><fieldset>
					<div class="beschriftung">
						<legend>Text:<?php if($font>0)echo " +$font";elseif($font<0) echo " $font";?></legend>
					</div>
					<div class="buttons">
						<button type="submit" name="font" value="<?php echo ($font -1) ?>">-</button>
						<button type="submit" name="font" value="<?php echo ($font +1) ?>">+</button>
					</div>
				</fieldset>
			</li>
			<li>
				<fieldset>
					<div class="beschriftung">
						<legend>Spalten:<?php echo " $col";?></legend>
					</div>
					<div class="buttons">
						<button type="submit" name="col" value="<?php echo ($col -1) ?>">-</button>
						<button type="submit" name="col" value="<?php echo ($col +1) ?>">+</button>
					</div>
				</fieldset>
			</li>
			<li>
				<fieldset>
					<div class="beschriftung">
						<legend>Transponieren:<?php if($trans>0)echo " +$trans";elseif($trans<0) echo " $trans";?></legend>
					</div>
					<div class="buttons">
						<button type="submit" name="trans" value="<?php echo ($trans -1) ?>">-</button>
						<button type="submit" name="trans" value="<?php echo ($trans +1) ?>">+</button>
					</div>
				</fieldset>
			</li>
		</ul>
	</form>
</div>
<?php

function checkfont(){
    global $fontsize;
    global $style;
    global $font;

    $fontsize = intval($fontsize);

    if(isset($_POST['font'])){
        $font = intval($_POST['font']);
        if($font<-4){
            $font=-4;
        }
        if($font>5){
            $font=5;
        }
        if($font>0){
            for($i=0;$i<$font;$i++){
                $fontsize = ($fontsize + 2);
            }
        }
        elseif($font<0){
            for($i=0;$i>$font;$i--){
                $fontsize = ($fontsize - 2);
            }
        }
    }

    $style = "style='line-height:1;font-size: ".$fontsize."px;'";

}

function formatSongtext($text){
    $songtext = '';
    foreach(preg_split("/((\r?\n)|(\r\n?))/", $text) as $line){
        $line = "<p class='song'>".tochords($line);
        if(empty($line) or ctype_space($line)){
            $line = "<br><p class='song'>";
        } else {
            $line .="<br>";
        }
        $songtext .= $line;
    }
    return styleSongtext($songtext);
}

function checktrans(){
    global $songtext;
    global $trans;
    if(isset($_POST['trans'])){

        $trans = intval($_POST['trans']);
        if($trans==12||$trans==-12){
            $trans=0;
        }
        if($trans>0){
            for($i=0;$i<$trans;$i++){
                $songtext = transposeUp($songtext);
            }
        }
        elseif($trans<0){
            for($i=0;$i>$trans;$i--){
                $songtext = transposeDown($songtext);
            }
        }

    }
}

function styleSongtext($songtext){
    global $style;
    $search = "<p class='song'>";
    $replace = "<p class='song' $style>";
    $songtext = str_replace($search, $replace, $songtext);
    return $songtext;
}

function getColumns(){
    global $col;
    $r = "";
    if($col>3){
        $col = 4;
    }
    if($col<2){
        $col = 1;
    }
    if ($col>1){
        $r= "style='columns:$col'";
    }
    return $r;
}
?>