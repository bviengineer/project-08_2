<?php
require __DIR__.'/../inc/bootstrap.php';
requireAuth();

$currentPwd = request()->get('current_password');
$newPwd = request()->get('password');
$confirmedPwd = request()->get('confirm_password');


// Verifies whether new password matches the confirmed version of the new password
if ($newPwd != $confirmedPwd) {
    $session->getFlashBag()->add('error', 'New passwords do not match, please try again');
    redirect('/account.php');
}

// Get the user attempting to change their password
$user = getAuthenticatedUser();

// If a username could not be found 
if (empty($user)) {
    $session->getFlashBag()->add('error', 'Some error Happened. Try again.  If it continues, please 
    log out and log back in.');
    redirect('/account.php');
}

// Verifies whether the password in the database matches the user supplied password
if (!password_verify($currentPwd, $user['password'])) {
    $session->getFlashBag()->add('error', 'Current password is incorrect. Please try again');
    redirect('/account.php');
}

// Hashes the updated password
$updatedPwd = updatePassword(password_hash($newPwd, PASSWORD_DEFAULT), $user['username']);

// If password could not be updated
if (!$updatedPwd) {
    $session->getFlashBag()->add('error', 'Could not update password. Please try again.');
    redirect('/account.php');
}
// Displays success message if the password was updated successfully 
$session->getFlashBag()->add('success', 'Password updated');
redirect('/account.php');