<?php


class SongtextEditController
{
    private $songtext;

    private $song ;
    private $genre;

    public function __construct(Songtext $songtext){
        $this->songtext = $songtext;
        $this->song = $this->songtext->getId();
        $this->genre = $this->songtext->getGenre()->getId();
    }

    public function render(){
        $token = LoginController::getToken();
        if(isset($_POST['token'])&&strcmp($_POST['token'],$token)) { //&&$_POST['token']==$token
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

                    //PARMS: title, columns, songtext, $token, $song, $genre
                    require_once './view/Edit/edit_song.php';
                    break;
            }
        }else {
            $message = "A session Timeout occured... Try refreshing or logging back in.";
            //PARMS: $title, $message
            require_once './view/info_message.php';
        }


    }
    private function saveToDb(){
        $this->songtext->setTitle($_POST['title']);
        $this->songtext->setSongtext($_POST['songtext']);
        if(DBAccess::getController()->updateSongtext($this->songtext)){
            $title = "Success!";
            $message = "<p class='song'>The songtext was successfully updated</p><br>";
            $message .= "<a href='index.php?genre=$this->genre&song=$this->song' ><p class='song'>OK</p></a>";
            //PARMS: $title, $message,
            require_once './view/info_message.php';
        }
    }

}