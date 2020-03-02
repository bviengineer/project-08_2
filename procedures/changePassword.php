<?php
requireAuth();

$currentPwd = request()->get('current_password');
$newPassword = request()->get('password');
$confirmedPwd = request()->get('confirm_password');


// Verifies whether new password matches the confirmed version of the new password
if ($newPassword != $confirmedPassword) {
    $session->getFlashBag()->add('error', 'New passwords do not match, please try again');
    redirect('/account.php');
}