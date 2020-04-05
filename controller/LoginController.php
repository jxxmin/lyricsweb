<?php

class LoginController
{
    private $userValid = true;
    private $passwordValid = true;
    private $passwordMatch = true;
    private $registerSuccess = true;
    private $loginSuccess = true;

    private $user;
    private $username = '';
    private $password = '';
    private $repeat = '';

    private $action;

    public function checkLoginOrRegistration($action){
        $this->action = $action;
        return ($this->action == 'register') ? $this->checkRegistration() : $this->checkLogin();
    }

    private function checkLogin()
    {
        if(isset($_POST['login'])) {
            if (isset($_POST['username'])) { //Form submitted
                $this->checkUsername($_POST['username']);
            }
            if (isset($_POST['password'])) {
                $this->checkPassword($_POST['password']);
            }
            return $this->userValid && $this->passwordValid ? $this->tryLogin() : false;

        } else return false;
    }

    private function checkRegistration()
    {
        if (isset($_POST['register'])) {
            if (isset($_POST['username'])) {
                $this->checkUsername($_POST['username']);
            }
            if (isset($_POST['password'])) {
                $this->checkPassword($_POST['password']);
            }
            if (isset($_POST['secondPassword'])) {
                $this->checkRepeat($_POST['secondPassword']);
            }
            if($this->userValid && $this->passwordValid && $this->passwordMatch) {
                $this->user = new User($this->username, $this->hashPassword($this->password), false);
                return $this->tryRegistration() ? $this->tryLogin() : false;
            } else return false;

        } else return false;
    }

    public function logout(){
        session_destroy();
    }

    public function render(){
        $isSong = false;
        $genreId = 'login';
        $activeId = $this->action;
        $array['login'] = 'Login';
        $array['register'] = 'Register';
        //parms: $array[song_id => Song Title], $activeId, $genreId, $isSong
        require_once "./view/Navigation/songnavigation.php";

        $userValid = $this->userValid;
        $passwordValid = $this->passwordValid;
        $passwordMatch = $this->passwordMatch;
        $registerSuccess = $this->registerSuccess;
        $loginSuccess = $this->loginSuccess;
        //parms: $activeId, $userValid, $passwordValid, $passwordMatch, $registerSuccess, $loginSuccess,
        require_once("./view/Login/login.php");

    }


    private function checkUsername($un){
        $this->username = FormController::sanitize($un);
        //match email pattern
        if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/i", $this->username )){
            $this->userValid = false;
        }
    }
    private function checkPassword($pw){
        //Minimum eight characters, at least one uppercase, one lowercase, one number:
        $uppercase = preg_match('@[A-Z]@', $pw);
        $lowercase = preg_match('@[a-z]@', $pw);
        $number    = preg_match('@[0-9]@', $pw);
        $this->passwordValid = (!$uppercase || !$lowercase || !$number || strlen($pw) < 8) ? false : true;
        $this->password = FormController::sanitize($pw);
    }
    private function checkRepeat($repeat){
        $this->repeat = FormController::sanitize($repeat);
        $this->passwordMatch = ($this->repeat == $this->passwordMatch);
    }


    private function tryLogin(){
        $this->user = DBAccess::getController()->getUser($this->username);
        $hashedPw = ($this->user) ? $this->user->getHashedPassword() : "";

        var_dump($hashedPw);
        echo "<br/>";
        var_dump($this->password);
        if (password_verify($this->password, $hashedPw)) {
            $this->user->isAdmin() ? $this->setAdminSession() : $this->setUserSession();
            $this->loginSuccess = true;
        } else {
            $this->user=null;
            $this->username = '';
            $this->password = '';
            session_destroy();
            $this->loginSuccess = false;
        }
        return $this->loginSuccess;
    }

    private function tryRegistration(){
        $this->registerSuccess = DBAccess::getController()->insertUser($this->user);
        return $this->registerSuccess;
    }

    public static function isLoggedIn(){
        return isset($_SESSION['loggedIn']) ? $_SESSION['loggedIn'] : false;
    }

    public static function isAdmin(){
        return isset($_SESSION['admin']) ? $_SESSION['admin'] : false;
    }

    private function setAdminSession(){
        $_SESSION['admin'] = "true";
        $this->setUserSession();
    }

    private function setUserSession(){
        $_SESSION['loggedIn'] = "true";
        $_SESSION['token'] = $this->makeToken();
        session_write_close();
    }

    public static function getToken(){
        return $_SESSION['token'];
    }
    private function makeToken(){
        $length = 32;
        return substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $length);
    }

    private function hashPassword($input) {
        $options = [
            'salt' => bin2hex(random_bytes(32)), //write your own code to generate a suitable salt
            'cost' => 12 // the default cost is 10
        ];
        $hash = password_hash($input, PASSWORD_DEFAULT);
        return $hash;
    }

}