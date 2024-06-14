<?php

declare(strict_types=1);

function getApp(): \App\App
{
    return \App\App::getInstance();
}
function getRequest(): \App\Request
{
    return \App\Request::getInstance();
}
function getTemplate(): \App\Template
{
    return \App\Template::getInstance();
}
function getSession(): \App\Session
{
    return \App\Session::getInstance();
}

/*
function getUser(): \App\User
{
    return \App\User::getInstance();
}*/

function getConfig(): \App\Conf
{
    return \App\Conf::getInstance();
}
function getDb(): \App\Db
{
    return \App\Db::getInstance();
}
