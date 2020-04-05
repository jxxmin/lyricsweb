<?php


class SongtextEditController
{
    private $songtext;

    public function __construct(Songtext $songtext){
        $this->songtext = $songtext;
    }

    public function render(){
        $token = LoginController::getToken();
        if(isset($_POST['token'])&&strcmp($_POST['token'],$token)){ //&&$_POST['token']==$token
            if(isset($_POST['save'])){
                echo "save successful but not committed to db"; //TODO: update Songzext
            } else {
                $title = $this->songtext->getTitle();
                $songtext = $this->songtext->getSongtext();
                $song = $this->songtext->getId();
                $genre = $this->songtext->getGenre()->getId();

                //PARMS: title, columns, songtext, $token, $song, $genre
                require_once './view/Edit/edit_song.php';
            }
        }


    }

}