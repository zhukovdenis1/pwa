<?php

declare(strict_types=1);

$app = getApp();
$session = getSession();

$session->destroy();

$app->redirect('/');
