<?php
require __DIR__.'/../inc/bootstrap.php';

// Verifies that the user entered a username
$user = findUserByUsername(request()->get('username'));
if (empty($user)) {
    $session->getFlashBag()->add('error', 'Username was not found');
    redirect('/login.php');
}