<?php

namespace App\Entity;

class User
{
    private $id;
    private $username;
    private $email;

    public function __construct($id,$username, $email)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
