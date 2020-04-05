
<?php
//parms: title, columns, songtext
$title = (isset($title)) ? $title : "Ups...";
$columns = (isset($columns)) ? $columns : "";
$songtext = (isset($songtext)) ? $songtext : "Der Songtext konnte leider nicht gefunden werden.";

echo "<section>";
echo "<h2>$title</h2>
        <pre>
            <div $columns class='songbox'>
                $songtext
            </div>
        </pre>
      </section>";