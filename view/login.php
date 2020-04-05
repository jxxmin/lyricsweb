<?php
/*
Songtexte und Akkorde
Module 151 / 426
Webapplikation mit Datenbank / Agile Softwareentwicklung
Feb/Mär/Apr 2020 Sofia Horlacher, Jasmin Fitz, Luisa Stückelberger
*/

//parms: $activeId, $userValid, $passwordValid, $passwordMatch, $registerSuccess, $loginSuccess,
$userValid = (isset($userValid)) ? $userValid : true;
$passwordValid= (isset($passwordValid)) ? $passwordValid : true;
$passwordMatch= (isset($passwordMatch)) ? $passwordMatch : true;
$registerSuccess= (isset($registerSuccess)) ? $registerSuccess : true;
$loginSuccess= (isset($loginSuccess)) ? $loginSuccess : true;
if($activeId) {?>
    <form method="post" action="index.php?id=login&action=<?php echo $activeId?> ">
    <p>Username:</p>
    <input type="email" name="username" required />
        <?php if(!$userValid) { echo "<span  class='error'>the username needs to be an e-mail address</span>";}?>
    <br />
    <p>Passwort:</p>
    <input type="password" name="password" required/>
        <?php if(!$passwordValid) { echo "<span  class='error'>the password needs to contain 8 characters, 1 uppercase, 1 lowercase, 1 number</span>";}?>
<?php
if($activeId == 'login'){
    echo "<br /><input type='submit' name='login' value='Anmelden' />";
    if(!$loginSuccess) { echo "<br/><span  class='error'>invalid username or password</span>";}
} elseif($activeId == 'register') {//($action == 'register')
    ?>
    <br /><p>Passwort wiederholen:</p>
    <input type="password" name="secondPassword" required/>
        <?php if(!$passwordMatch) { echo "<span  class='error'>the passwords don't match</span>";}?>
        <br /><input type='submit' name='register' value='Registrieren' />
        <?php if(!$registerSuccess) { echo "<br/><span  class='error'>the username is already taken</span>";}?>    <?php
}
echo "</form>";
}
?>