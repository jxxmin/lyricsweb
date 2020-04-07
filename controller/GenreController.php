<?php


class GenreController
{
    private $action;
    private $genre;
    private $canDelete;

    public function __construct(Genre $genre, $actionId, $canDelete)
    {
        $this->action = $actionId;
        $this->genre = $genre;
        $this->canDelete = $canDelete;
    }

    public function render(){
        if($this->checkDeleted()) {
            //header('Location: ./index.php');
        } else{

            $genreId = $this->genre->getId();

            $songs = $this->genre->getSongs();
            if (empty($songs) && $this->canDelete) {
                $token = LoginController::getToken();
                //parms:  token, genreId
                require_once "./view/Edit/delete_genre.php";
            } else {
                $isSong = true;
                $activeId = $this->action;
                foreach ($songs as $song) {
                    if (is_a($song, "Songtext")) {
                        $id = $song->getId();
                        $title = $song->getTitle();
                        $array[$id] = $title;
                    }
                }
                //parms: $array[song_id => Song Title], $activeId, $genreId, $isSong
                require_once "./view/Navigation/songnavigation.php";
            }
        }

    }


    private function checkDeleted(){
        $return = false;
        $token = LoginController::getToken();
        if(isset($_POST['token'])&&strcmp($_POST['token'],$token)&&isset($_POST['genre'])&&$_POST['genre']==$this->genre->getId()) {
            if(DBAccess::getController()->deleteGenre($this->genre)){
                $title = "Success!";
                $message = "The Genre was successfully deleted<br>";
                $message .= "<a href='index.php' ><p class='song'>OK</p></a>";;
                //PARMS: $title, $message,
                require_once './view/info_message.php';
                $return = true;
            } else{
                //unknown reason -> default message
                //PARMS: $title, $message
                require_once './view/info_message.php';
                return false;
            }
        }
        return $return;
    }
}