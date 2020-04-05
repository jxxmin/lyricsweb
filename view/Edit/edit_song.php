<?php
//parms: title, token, songtext, $genre, $song
$title = (isset($title)) ? $title : "Ups...";
$token = (isset($token)) ? $token : "";
$songtext = (isset($songtext)) ? $songtext : "Der Songtext konnte leider nicht gefunden werden.";
;
?>
<section>
    <form method="post" action="index.php?genre=<?php echo $genre?>&song=<?php echo $song?> ">
        <h2><input type='text' id='title' name='title' value='<?php echo $title ?>'></h2>
        <pre>
            <div class='songbox'>
                 <textarea class='song' id='songtext' name='songtext'><?php echo $songtext ?></textarea>

            </div>
        </pre>
        <div class="hovermenu" id="savemenu">
            <input type="hidden" name="token" value=" <?php echo $token ?> ">
            <input type="hidden" name="edit">
                <ul>
                    <li>
                        <fieldset>
                            <div class="buttons">
                                <button type="submit" name="save" value="save">Save</button>
                                <button type="submit" name="cancel" value="cancel">Cancel</button>
                            </div>
                        </fieldset>
                    </li>
                </ul>
                </div>
</form>
</section>
