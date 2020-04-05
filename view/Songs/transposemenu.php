<?php
/*
Songtexte und Akkorde
Module 151 / 426
Webapplikation mit Datenbank / Agile Softwareentwicklung
Feb/Mär/Apr 2020 Sofia Horlacher, Jasmin Fitz, Luisa Stückelberger
*/
//parms: $genre, $song, $font, $trans, $col
$genre = (isset($genre)) ? $genre : "";
$song = (isset($song)) ? $song : "";
$font = (isset($font)) ? $font : 0;
$trans = (isset($trans)) ? $trans : 0;
$col = (isset($col)) ? $col : 1;

//New Form
?>
<div class="hovermenu" id="transposemenu">
	<form method="post" action="index.php?genre=<?php echo $genre?>&song=<?php echo $song?> ">
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

?>