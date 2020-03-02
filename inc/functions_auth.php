<?php
// Verifies whether a user is authenticated 
function isAuthenticated() {
    global $session;
    return $session->get('auth_logged_in', false);
}

function saveUserData($user) {
    global $session;
    $session->set('auth_logged_in', true);
    $session->set('auth_user_id', (int) $user['id']);
    
    $session->getFlashBag()->add('success', "Successfully Logged In"); 
}