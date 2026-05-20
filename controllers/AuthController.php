<?php

require_once __DIR__ . '/../models/User.php';

class AuthController
{
    public function login($email, $password)
    {
        $user = User::findByEmail($email);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return null;
    }
    
    public function register(
        $name,
        $email,
        $password
    ) {
        return User::create(
            $name,
            $email,
            $password
        );
    }
}