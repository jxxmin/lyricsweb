<?php
/*
Songtexte und Akkorde
Module 151 / 426
Webapplikation mit Datenbank / Agile Softwareentwicklung
Feb/Mär/Apr 2020 Sofia Horlacher, Jasmin Fitz, Luisa Stückelberger
*/
global $id;
global $song;

if(isset($_POST['username'])&&isset($_POST['password'])) { //Form submitted
    $username = sanitize($_POST['username']);
    $pw = sanitize($_POST['password']);
    $user = false;
    if(isset($_POST['secondPassword'])){ //Registration submitted
        $secondpw = sanitize($_POST['secondPassword']);
        $registration = registerOnDb($username, $pw);
        if($registration){
            $user = loginOnDB($username,$pw);
        } else{
            $message = $registration;
        }
    } else {
        $user = loginOnDB($username, $pw);
    }
    if(!$user){
        echo "wrong password";
        $username = null;
        session_destroy();
    } else {
        if ($user == 'admin'){
            setAdminSession();
        } else {
            setUserSession();
        }
        header('Location: ./index.php?id=edit');
    }
}else{
    session_destroy();
}

?>
    <form method="post" action="index.php?id=<?php echo $id?>&song=<?php echo $song?> ">
    <p>Username:</p>
    <input type="email" name="username" required /><br />
    <p>Passwort:</p>
    <input type="password" name="password" required/><br />
<?php
if($song == 'login'){
    echo "<input type='submit' value='Anmelden' /></form>";
} else //($song == 'register')
    {
    ?>
    <p>Passwort wiederholen:</p>
    <input type="password" name="secondPassword" required/><br />
    <input type='submit' value='Registrieren' /></form>
    <?php
}



?>








