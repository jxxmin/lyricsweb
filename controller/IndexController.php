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
                $this->genreController = new GenreController($this->genre, $this->songId, $this->canDeleteGenres);
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
                    $this->editController = new EditController($this->actionId, $this->genres);
                    $this->editController->render();
                }


                break;
            default:
                echo "<img src='./view/img/gitarre.png' alt='Guitar' width='400vw' height='400vw'>";
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
