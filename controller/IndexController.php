<?php


class IndexController
{
    private const editid = 'edit';
    private const loginid = 'login';
    private const logoutid = 'logout';

    private $songId;
    private $id;
    private $genreId;
    private $actionId;

    private $songtext;
    private $genres;
    private $genre;

    private $isSong;

    private $formController;
    private $loginController;
    private $editController;
    private $songtextController;
    private $genreController;

    public function render(){
        $this->renderNav();
        echo '<div id="wrapper">';
        switch(true){
            case $this->id == self::logoutid:
                $this->loginController->logout();
                $this->loginController = new LoginController();
                header('Location: ./index.php');
                break;
            case $this->genreId: //
                $this->genre = DBAccess::getController()->getGenre($this->genreId);
                $this->genreController = new GenreController($this->genre, $this->songId);
                $this->genreController->render();
                if($this->songId) {
                    $this->songtext = DBAccess::getController()->getSongtext($this->genre, $this->songId);
                    $this->songtextController = new SongtextController($this->songtext);
                    $this->songtextController->render($this->loginController->isAdmin());
                }
                break;
            case $this->id == self::loginid: //Login Tab
                if($this->loginController->isLoggedIn()){
                    header("Location: ./index.php?id=".self::editid);
                } else{
                    if($this->loginController->checkLoginOrRegistration($this->actionId)){
                        header("Location: ./index.php?id=".self::editid);
                    } else{
                        $this->loginController->render();
                    }
                }
                break;
            case $this->id == self::editid : //Edit Tab
                if(!$this->loginController->isLoggedIn()) {
                    header('Location: ./index.php?id='.self::loginid);
                } else{
                    $this->editController = new EditController($this->actionId);
                    $this->editController->render();
                }


                break;
            default:
                //header('Location: ./index.php');
                break;
        }

    }
    public function __construct()
    {
        if (isset($_GET['id'])) {
            $this->id = $_GET['id'];
        }
        if (isset($_GET['genre'])) {
            $this->genreId = $_GET['genre'];
        }
        if (isset($_GET['song'])) {
            $this->songId = $_GET['song'];
        }
        if (isset($_GET['action'])) {
            $this->actionId = $_GET['action'];
        }
        $this->loginController = new LoginController();
        $this->genres = DBAccess::getController()->loadAllGenres();

    }


    private function renderNav(){
        if($this->loginController->isLoggedIn()){
            $actionArray = array(self::logoutid => 'Logout',self::editid =>"Edit" );
        } else{
            $actionArray['login'] = 'Login';
        }
        $activeTab = ($this->genreId) ? $this->genreId : $this->id;
        if(!empty($this->genres)) {
            foreach ($this->genres as $genre) {
                if(is_a($genre, "Genre")) {
                    $id = $genre->getId();
                    $genreName = $genre->getGenre();
                    $genreArray[$id] = $genreName;
                }
            }
        }
        //parms: $genreArray, $actionArray, $activeTab
        include "./view/header_nav.php";
    }
}

?>


<?php /*include "view/header_nav.php"; ?>
<div id="wrapper">

    <?php
    include "view/smallnav.php";

    switch($id){
        case '':
        case 'login':
        case 'edit':
            if(isLoggedIn()){
                // logged in: Edit Page
                $id = 'edit';
                if($song==''){
                    $song = 'add_song';
                }
                $options = ['add_song' => 'Add Song', 'add_genre' => 'Add Genre'];
                echoSmallNav($options);
                include "view/edit.php";
            } else{
                // not logged in: Login Page
                $id = 'login';
                if($song==''){
                    $song = 'login';
                }
                $options = ['login' => 'Login', 'registrieren' => 'Registrieren'];
                echoSmallNav($options);
                include "view/login.php";
            }
            break;
        default:
            $songs = getSongs();
            echoSmallNav($songs);
            if(isAdmin() && !$songs){
                include "view/deletegenre.php";
            }else if ($songs && isset($_GET['song'])){
                /// song page
                $song = $_GET['song'];
                include "view/transposemenu.php";
                $columns = getColumns();
                $songtext = formatSongtext(getSongtext($id, $song));
                $title = makeName($song);
                echo "<section>";
                echo "<h2>$title</h2><pre><div $columns class='songbox'>$songtext</div></pre></section>";
            }
            break;
    }
*/
    ?>
