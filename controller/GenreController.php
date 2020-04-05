<?php


class GenreController
{
    private $action;
    private $genre;

    public function __construct($genre, $actionId)
    {
        $this->action = $actionId;
        $this->genre = $genre;
    }

    public function render(){

        $isSong = true;
        $genreId = $this->genre->getId();
        $activeId = $this->action;
        foreach ($this->genre->getSongs() as $song) {
            if(is_a($song, "Songtext")) {
                $id = $song->getId();
                $title = $song->getTitle();
                $array[$id] = $title;
            }
        }
        //parms: $array[song_id => Song Title], $activeId, $genreId, $isSong
        require_once "./view/songnavigation.php";


    }

}