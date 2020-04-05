<?php


class IndexController
{
    private const editid = 'edit';
    private const loginid = 'login';
    private const logoutid = 'logout';

    private $canEditSongs;
    private $canDeleteSongs;
    private $canAddSongs;
    private $canAddGenres;
    private $canDeleteGenres;

    private $songId;
    private $id;
    private $genreId;
    private $actionId;

    private $songtext;
    private $genres;
    private $genre;

    private $loginController;
    private $editController;
    private $songtextController;
    private $songtextEditController;
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
                    if(isset($_POST['edit'])&&$this->canEditSongs){
                        $this->songtextEditController = new SongtextEditController($this->songtext);
                        $this->songtextEditController->render();
                    } else {
                        $this->songtextController = new SongtextController($this->songtext);
                        $this->songtextController->render($this->canEditSongs);
                    }
                }
                break;
            case $this->id == self::loginid: //Login Tab
                if(LoginController::isLoggedIn()){
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
                if(!LoginController::isLoggedIn()) {
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

        //Rights:
        $this->canAddGenres = LoginController::isLoggedIn();
        $this->canAddSongs = LoginController::isLoggedIn();
        $this->canEditSongs = LoginController::isAdmin();
        $this->canDeleteSongs = LoginController::isAdmin();
        $this->canDeleteGenres = LoginController::isAdmin();
    }


    private function renderNav(){
        if(LoginController::isLoggedIn()){
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
        require_once "./view/Navigation/header_nav.php";
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
                include "view/edit_song.php";
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
