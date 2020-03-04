<?php
require __DIR__.'/../inc/bootstrap.php';

// Verifies that the user entered a username
$user = findUserByUsername(request()->get('username'));
if (empty($user)) {
    $session->getFlashBag()->add('error', 'Username was not found');
    redirect('/login.php');
}
// Verfies whether the provided password matches the stored hashed password
if (!password_verify(request()->get('password'), $user['password'])) {
    $session->getFlashBag()->add('error', 'Username or password is incorrect. Please try again.');
    redirect('/login.php');
}
saveUserData($user);