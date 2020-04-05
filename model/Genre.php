<?php


class Genre
{
    private $id;
    private $songs;
    private $genre;

    public function __construct($id, $genre){
        $this->id = $id;
        $this->genre = ucfirst($genre);
    }

    public function getSongs(){
        return $this->songs;
    }

    public function getGenre()
    {
        return $this->genre;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setSongs(array $songs): void
    {
        $this->songs = $songs;
    }

}