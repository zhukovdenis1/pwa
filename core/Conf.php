<?php

declare(strict_types=1);

namespace App;

class Conf
{
    private static ?Conf $instance = null;
    private array $data = [
        'db' => array (
            'default' => array(
                'name' => '***',
                'user' => '***',
                'password' => '***',
                'host' => 'localhost',
                'charset' => 'utf8'
            )
        ),
    ];

    private function __construct()
    {

    }

    public static function getInstance(): Conf
    {
        if (!static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * @param mixed|null $key
     * @return mixed
     */
    public function get(mixed $key = null): mixed
    {
        if (! is_null($key)) {
            $keyArr = explode('.', $key);
            $value = $this->data;
            foreach ($keyArr as $k) {
                $value = $value[$k] ?? null;
            }
        } else {
            $value = $this->data;
        }

        return $value;
    }
}
