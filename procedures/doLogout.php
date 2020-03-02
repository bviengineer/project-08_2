<?php
require __DIR__.'/../inc/bootstrap.php';

$session->remove('auth_logged_in');
$session->remove('auth_user_id');

// Log out confirmation message
$session->getFlashBag()->add('success', "Successfully Logged Out");
$cookie = setAuthCookie('expired', 1);
redirect('/login.php', ['cookies' => [$cookie] ]);