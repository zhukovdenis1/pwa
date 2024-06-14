<?php

declare(strict_types=1);

namespace App;

use App\User;

use function mysql_xdevapi\getSession;

final class App
{
    private static ?App $instance = null;
    private ?Request $request;

    public static function getInstance(): App
    {
        if (!static::$instance) {
            static::$instance = new App();
        }

        return static::$instance;
    }

    private function __construct()
    {
        $this->request = getRequest();
    }


    public function start(): void
    {
        $session = \getSession();
        $session->start();

        $uri = $this->request?->getUri();

        $controller = '';

        if ($session->get('auth')) {
            switch ($uri) {
                case '/':
                    $controller = 'main';
                    break;
                case '/install':
                    $controller = 'install';
                    break;
                case '/english':
                    $controller = 'english/index';
                    break;
                case '/english/save':
                    $controller = 'english/save';
                    break;
                case '/logout':
                    $controller = 'auth/logout';
                    break;
                case '/remind':
                    $controller = 'remind/index';
                    break;
                case '/raw':
                    $controller = 'raw/index';
                    break;
                case '/raw/save':
                    $controller = 'raw/save';
                    break;
            }
        } else {
            $controller = 'auth/index';
        }

        $file = 'src/' . $controller . '.php';

        $contentFile = '';

        if (is_file($file)) {
            ob_start();
            include($file);
            $contentFile = (string) ob_get_clean();
        } else {
            $this->error404();
        }

        $template = getTemplate();

        $template->display($contentFile);
    }


    public function error404(): never
    {
        header("HTTP/1.0 404 Not Found");
        die('404');
    }

    public function error403(): never
    {
        header('HTTP/1.0 403 Forbidden');
        ?><form action="/auth?back=1" method="post">
            <label>403<input name="password" type="password" style="width: 30px;border: 0; background: none" /></label>
        </form><?php
        die();
    }

    public function redirect(string $url): never
    {
        header('Location: ' . $url);
        die();
    }
}
