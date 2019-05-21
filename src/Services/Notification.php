<?php

namespace App\Services;


class Notification {

    private $email;

    public function __construct($email)
    {

        // je kan dan nu email zien
//        dump($email); die;
        $this->email = $email;
    }

    public function sendNotification(){

    }
}