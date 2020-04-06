<?php
//parms: title, token, songtext
$title = "Title";
$token = (isset($token)) ? $token : "";
$songtext = "insert your songtext here";
$genres = (isset($genres)) ? $genres : [];
?>
<section>
    <form method="post" action="index.php?id=edit&action=add_genre">
        <h2><input type='text' id='genre' name='genre' value='Type your genre'></h2>
        <div class="hovermenu" id="savemenu">
            <input type="hidden" name="token" value=" <?php echo $token ?> ">
            <input type="hidden" name="add">
            <ul>
                <li>
                    <fieldset>
                        <div class="buttons">
                            <button type="submit" name="addgenre" value="add">Save</button>
                            <button type="submit" name="cancel" value="cancel">Cancel</button>
                        </div>
                    </fieldset>
                </li>
            </ul>
        </div>
    </form>
</section>