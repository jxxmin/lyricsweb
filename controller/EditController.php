<?php


class EditController
{
    private $action;
    private $genres;

    public function __construct($actionId, $genres)
    {
        $this->genres = $genres;
        $this->action = $actionId;
    }

    public function render()
    {
        $isSong = false;
        $genreId = 'edit';
        $activeId = $this->action;
        $array['add_song'] = 'Add Song';
        $array['add_genre'] = 'Add Genre';
        //parms: $array[song_id => Song Title], $activeId, $genreId, $isSong
        require_once "./view/Navigation/songnavigation.php";

        if (isset($_POST['add'])) {
            $token = LoginController::getToken();
            if(isset($_POST['token'])&&strcmp($_POST['token'],$token)) {
                switch (true) {
                    case isset($_POST['addsong']):
                        $this->saveSong();
                        break;
                    case isset($_POST['addgenre']):
                        $this->saveGenre();
                        break;
                    case isset($_POST['cancel']):
                        header("Location: ./index.php?id=edit&action=$activeId");
                        break;
                }
            } else{
                $message = "A session Timeout occured... Try refreshing or logging back in.";
                //PARMS: $title, $message
                require_once './view/info_message.php';
            }
        } else {
            //form not submitted

            //parms: $genres
            $token = LoginController::getToken();
            $genres = $this->genres;
            if ($activeId) require_once("./view/Edit/$activeId.php");
        }
    }

    private function saveSong(){
        $songtext = new Songtext(0, FormController::sanitize($_POST['songtext']), FormController::sanitize($_POST['title']), FormController::sanitize($_POST['genre']));        if(DBAccess::getController()->addSongtext($songtext)){
            $title = "Success!";
            $message = "The songtext was successfully added<br>";
            $message .= "<a href='index.php?id=edit&action=add_song' ><p class='song'>Add another</p></a>";;
            //PARMS: $title, $message,
            require_once './view/info_message.php';
        }
    }
    private function saveGenre(){
        $genre = new Genre(0, FormController::sanitize($_POST['genre']));
        if(DBAccess::getController()->addGenre($genre)){
            $title = "Success!";
            $message = "The genre was successfully added<br>";
            $message .= "<a href='index.php?id=edit&action=add_genre' ><p class='song'>Add another</a>";;
            //PARMS: $title, $message,
            require_once './view/info_message.php';
        }
    }
}

/*$token = LoginController::getToken();
        if(isset($_POST['token'])&&strcmp($_POST['token'],$token)) { // token needs to be same as session token
            switch (true) {
                case isset($_POST['save']):
                    if(isset($_POST['songtext'])&&isset($_POST['title'])){
                        $this->saveToDb();
                    } else{
                        //unknown reason -> default message
                        //PARMS: $title, $message
                        require_once './view/info_message.php';
                    }
                    break;
                case isset($_POST['cancel']):
                    header("Location: ./index.php?genre=$this->genre&song=$this->song");
                    break;
                default:
                    $title = $this->songtext->getTitle();
                    $songtext = $this->songtext->getSongtext();
                    $song = $this->song;
                    $genre = $this->genre;

                    //parms: title, token, songtext, $genre, $song
                    require_once './view/Edit/edit_song.php';
                    break;
            }
        }else {
            $message = "A session Timeout occured... Try refreshing or logging back in.";
            //PARMS: $title, $message
            require_once './view/info_message.php';
        }
*/