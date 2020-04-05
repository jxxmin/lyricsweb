<?php


class User
{
    private $isAdmin = false;
    private $username;
    private $hashedPassword;

    public function __construct($username, $hashedPassword, $isAdmin)
    {
        $this->isAdmin = $isAdmin;
        $this->hashedPassword = $hashedPassword;
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }
    public function getHashedPassword()
    {
        return $this->hashedPassword;
    }

    public function isAdmin(): bool
    {
        return $this->isAdmin;
    }

}