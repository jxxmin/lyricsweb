<?php


class SongtextEditController
{
    private $songtext;

    public function __construct(Songtext $songtext){
        $this->songtext = $songtext;

    }

    public function render(){
        $title = $this->songtext->getTitle();

        //PARMS: title, columns, songtext
        require_once './view/Edit/edit_song.php';

    }

}