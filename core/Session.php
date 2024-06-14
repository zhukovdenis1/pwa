<?php

declare(strict_types=1);

namespace App;

class Session
{
    private static ?Session $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        if (!static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function start(): bool
    {
        return session_start([
            'cookie_lifetime' => 864000
        ]);
    }

    public function destroy(): bool
    {
        return session_destroy();
    }

    public function set(string $key, mixed $value): void
    {
        $keyArr = explode('.', $key);
        $s = &$_SESSION;
        foreach ($keyArr as $k) {
            if (!isset($s[$k])) {
                $s[$k] = [];
            }
            $s = &$s[$k];
        }
        $s = $value;
    }

    public function get(?string $key): mixed
    {
        if (! is_null($key)) {
            $keyArr = explode('.', $key);
            $v = &$_SESSION;
            foreach ($keyArr as $k) {
                if (isset($v[$k])) {
                    $v = &$v[$k];
                } else {
                    return null;
                }
            }
            return $v;
        } else {
            return $_SESSION;
        }
    }
}
