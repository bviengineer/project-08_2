<?php
require __DIR__.'/../inc/bootstrap.php';

$session->remove('auth_logged_in');
$session->remove('auth_user_id');

// Log out confirmation message
$session->getFlashBag()->add('success', "Successfully Logged Out");
redirect('/login.php');