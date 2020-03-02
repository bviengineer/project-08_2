<?php
require_once __DIR__ . '/../inc/bootstrap.php';

$username->get('username');
$password->get('password');
$confirmedPwd->get('confirm_password');

// Verifies whether passwords match
if ($password != $confirmedPwd) {
    $session->getFlashBag()->add('error', 'Passwords do NOT match');
    redirect('/register.php');
}
