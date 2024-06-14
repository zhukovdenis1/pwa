<?php

declare(strict_types=1);

namespace App;

final class Template
{
    private static ?Template $instance = null;
    private string $dir = 'tpl/';

    private string $name = 'default';

    private array $brcr = [];

    private function __construct()
    {
    }

    public static function getInstance(): Template
    {
        if (!static::$instance) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function addBrcr(string $name, string $link): void
    {
        $this->brcr[] = [
            'name' => $name,
            'link' => $link
        ];
    }

    public function displayBrcr(): void
    {
        $result = '<div class="brcr"><a href="/" class="fa fa-home home"></a>';
        foreach ($this->brcr as $brcr) {
            $result .= ' &rarr; <a href="' . $brcr['link'] . '">' . $brcr['name'] . '</a>';
        }
        $result .= '<div>';

        echo $result;
    }

    public function display(string $content): void
    {
        if ($this->name == 'none') {
            echo $content;
        } else {
            include $this->dir . 'header.php';
            echo $content;
            include $this->dir . 'footer.php';
        }
    }
}
