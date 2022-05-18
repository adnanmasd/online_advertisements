<?php

include_once './scripts/flash.php';
$flash = new FlashMessages();

if ($flash->hasMessages($flash::ERROR)) {
    $flash->display($flash::ERROR);
}

if ($flash->hasMessages($flash::WARNING)) {
    $flash->display($flash::WARNING);
}

if ($flash->hasMessages($flash::SUCCESS)) {
    $flash->display($flash::SUCCESS);
}

if ($flash->hasMessages($flash::INFO)) {
    $flash->display($flash::INFO);
}



