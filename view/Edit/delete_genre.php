<?php
//parms:  token, genreId
$token = (isset($token)) ? $token : "";
$genreId = (isset($genreId)) ? $genreId : "";
?>
<section>
    <form method="post" action="index.php?genre=<?php echo $genreId ?>">
        <input type="hidden" name="token" value=" <?php echo $token ?> ">
        <input type="hidden" name="genre" value="<?php echo $genreId ?>">
        <button class='loginButton' type='submit' name='delete' value='delete'>Delete Genre</button>
    </form>
</section>