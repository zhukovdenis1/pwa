<?php

declare(strict_types=1);

$app = getApp();
$request = getRequest();
$session = getSession();

if ($password = $request->post('password')) {
    if ($password == '***') {
        $session->set('auth', true);
        $app->redirect('/');
    }
}
?>

<form action="/auth" method="POST">
    <input type="text" placeholder="Enter password" name="password" />
    <button type="submit">войти</button>
</form>


