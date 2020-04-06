<?php

//parms: $title, $message, $message2
$title = (isset($title)) ? $title :"Something went wrong...";
$message = (isset($message)) ? $message : "Please try again.";

?>
<section>
    <h2><?php echo $title ?></h2>
        <pre>
            <div style='columns:1' class='songbox'>
                <?php echo $message ?>
            </div>
        </pre>
      </section>