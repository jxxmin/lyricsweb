<?php
//parms: title, token, songtext
$title = "Title";
$token = (isset($token)) ? $token : "";
$songtext = "insert your songtext here";
$genres = (isset($genres)) ? $genres : [];
?>
<section>
    <form method="post" action="index.php?id=edit&action=add_song">
        <h2><label for="genre">Choose Genre:</label>
            <select id="genre" name="genre">
            <?php
                foreach($genres as $genre){
                    echo '<option value='.$genre->getId().'>'.$genre->getGenre().'</option>';
                }
            ?>
        </select></h2>
        <h2><input type='text' id='title' name='title' value='<?php echo $title ?>'></h2>
        <pre>
            <div class='songbox'>
                 <textarea class='song' id='songtext' name='songtext'><?php echo $songtext ?></textarea>

            </div>
        </pre>
        <div class="hovermenu" id="savemenu">
            <input type="hidden" name="token" value=" <?php echo $token ?> ">
            <input type="hidden" name="add">
            <ul>
                <li>
                    <fieldset>
                        <div class="buttons">
                            <button type="submit" name="addsong" value="add">Save</button>
                            <button type="submit" name="cancel" value="cancel">Cancel</button>
                        </div>
                    </fieldset>
                </li>
            </ul>
        </div>
    </form>
</section>