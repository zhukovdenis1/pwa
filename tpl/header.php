<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="manifest" href="manifest.json">
    <script>
        if (typeof navigator.serviceWorker !== 'undefined') {
            navigator.serviceWorker.register('js/sw.js')
        }
    </script>

    <title>NOTE</title>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

    <meta name="keywords" content="" />
    <meta name="description" content="" />


    <!--[if lt IE 9]>
    <script>
        document.createElement('header');
        document.createElement('nav');
        document.createElement('section');
        document.createElement('article');
        document.createElement('aside');
        document.createElement('footer');
    </script>
    <![endif]-->

    <link href="/css/style.css?<?php echo time();?>" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<body>
<div class="wrapper">
    <div class="center-wrap">
        <header>
            <a href="/logout" class="fa fa-sign-out" style="float: right"></a>
            <?php
            /** @phpstan-ignore variable.undefined */
            $this->displayBrcr();
            ?>
        </header>
        <main>
            <!--aside></aside-->
            <article>


