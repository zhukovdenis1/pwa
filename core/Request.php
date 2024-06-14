<?php

declare(strict_types=1);

namespace App;

final class Request
{
    private static ?Request $instance = null;
    private string $uri = '';

    private function __construct()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uriParts = explode('?', $uri);
        $this->uri = (string) $uriParts[0];
    }

    public static function getInstance(): Request
    {
        if (!static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function get(string $param, string|int|null $default = null): string|int|null
    {
        return isset($_GET[$param]) ? $_GET[$param] : $default;
    }

    public function post(string $param): ?string
    {
        return isset($_POST[$param]) ? $_POST[$param] : null;
    }

    public function request(string $param): ?string
    {
        return isset($_REQUEST[$param]) ? $_REQUEST[$param] : null;
    }

    public function getReferrer(): string
    {
        return $_SERVER['HTTP_REFERER'];
    }
}
