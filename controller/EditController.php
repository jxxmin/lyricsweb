<?php


class EditController
{
    private $action;

    public function __construct($actionId)
    {
        $this->action = $actionId;
    }

    public function render(){
        $isSong = false;
        $genreId = 'edit';
        $activeId = $this->action;
        $array['add_song'] = 'Add Song';
        $array['add_genre'] = 'Add Genre';
        //parms: $array[song_id => Song Title], $activeId, $genreId, $isSong
        require_once "./view/songnavigation.php";

        //parms: $action
        require_once ("./view/edit.php");

    }
}