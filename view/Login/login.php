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

<section>
    <form method="post" action="index.php?id=login&action=<?php echo $activeId?> ">
   <h2> <label for="username">E-mail address:</label><br/>    <input type="email" id="username" name="username" required /></h2>
        <?php if(!$userValid) { echo "<div  class='error'>the username needs to be an e-mail address</div><br />";}?>
    <h2><label for="password">Password:  </label> <br/>  <input type="password" id="password" name="password" required/></h2>
        <?php if(!$passwordValid) { echo "<div  class='error'>the password needs to contain 8 characters, 1 uppercase, 1 lowercase, 1 number</div><br />";}?>
<?php
if($activeId == 'login'){
    echo "<button class='loginButton' type='submit' name='login' value='login'>Login</button>";
    if(!$loginSuccess) { echo "<br/><div  class='error'>invalid username or password</div><br />";}
} elseif($activeId == 'register') {//($action == 'register')
    ?>
    <h2><label for="repeat">Repeat password:</label> <br/>   <input type="password" id="repeat" name="secondPassword" required/></h2>
        <?php if(!$passwordMatch) { echo "<div  class='error'>the passwords don't match</div><br>";}?>
        <button class="loginButton" type='submit' name='register' value='register'>Register</button>
        <?php if(!$registerSuccess) { echo "<br/><div  class='error'>the username is already taken</div>";}
}
echo "</form>
</section>";
}
?>