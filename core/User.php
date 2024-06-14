<?php

namespace App;

final class User
{
    private static ?User $instance = null;
    private ?Session $session = null;

    private function __construct()
    {
        $this->session = getSession();
    }

    public static function getInstance(): User
    {
        if (!static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function isAuth(): bool
    {
        if ($this->session->get('auth')) {
            return true;
        }

        return false;
    }

    public function auth()
    {
        $this->session->set('auth', true);
    }
}
