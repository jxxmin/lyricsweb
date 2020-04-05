<?php
//parms: $genre, $song, $token,
?>
<div class="hovermenu" id="editmenu">
    <form method="post" action="index.php?genre=<?php echo $genre?>&song=<?php echo $song?> ">
        <input type="hidden" name="token" value="<?php echo $token ?> ">
        <input type="hidden" name="edit">
        <input type="hidden" name="delete">
        <ul>
            <li><fieldset>
                    <div class="buttons">
                        <button type="submit" name="edit" value="edit">Edit</button>
                        <button type="submit" name="delete" value="delete">Delete</button>
                    </div>
                </fieldset>
            </li>
        </ul>
    </form>
</div>