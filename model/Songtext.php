<?php


class Songtext
{
    private $id;
    private $songtext;
    private $title;
    private $genre;

    public function __construct($id, $songtext, $title, $genre){
        $this->id = $id;
        $this->songtext = $songtext;
        $this->title = $this->makeName($title);
        $this->genre = $genre;
    }

    public function getSongtext()
    {
        return $this->songtext;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getGenre(): Genre
    {
        return $this->genre;
    }

    public function getId()
    {
        return $this->id;
    }


    private function makeName($input){
        $input = ucwords($input);
        $input = str_replace('_', ' ', $input);
        return $input;
    }

    /**
     * @param mixed|string $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @param mixed $songtext
     */
    public function setSongtext($songtext): void
    {
        $this->songtext = $songtext;
    }
}