<?php
require_once __DIR__.'/../inc/bootstrap.php';

$username = request()->get('username');
$password = request()->get('password');
$confirmedPwd = request()->get('confirm_password');

// Verifies whether passwords match
if ($password != $confirmedPwd) {
    $session->getFlashBag()->add('error', 'Passwords do NOT match');
    redirect('/register.php');
}

// Hashing of user password if an existing user is not found 
$hashedPwd = password_hash($password, PASSWORD_DEFAULT);

// Verifies whether a user already exists with that username, redirects if there is 
$user = findUserByUsername($username);
if (!empty($user)) {
    $session->getFlashBag()->add('error', 'Username already in use. Please try again.');
    redirect('/register.php');
}

// Adds new user to database if password & username verification steps are successful
$user = createNewUser($username, $hashedPwd);

// Registration success and logged in confirmation message
$session->getFlashBag()->add('success', "Account Created!");

// Redirect user to the home page after creating user 
redirect('/');